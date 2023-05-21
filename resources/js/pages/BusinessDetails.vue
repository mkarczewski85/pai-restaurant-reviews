<template>

    <v-container style="overflow-y: auto;">

        <BusinessDetailsCard :businessDetails="businessDetails" :loading="loading"></BusinessDetailsCard>

        <MyReview :myReview="myReview" :deleteFunction="deleteMyReview" v-if="myReview"></MyReview>
        <NoReview v-else></NoReview>
        <Review v-for="review in reviews" :review="review">

        </Review>
    </v-container>
    <AppBar></AppBar>
</template>
<script>
import Review from "@/components/Review.vue";
import {ref, onMounted} from 'vue'
import {createRouter as router, useRouter} from "vue-router";
import {request} from '../helper'
import Loader from '../components/Loader.vue';
import route from "@/route";
import BusinessCard from "@/components/BusinessCard.vue";
import AppBar from "@/components/AppBar.vue";
import BusinessDetailsCard from "@/components/BusinessDetailsCard.vue";
import MyReview from "@/components/MyReview.vue";
import NoReview from "@/components/NoReview.vue";

export default {

    components: {
        NoReview,
        MyReview,
        BusinessDetailsCard,
        BusinessCard,
        Review,
        Loader,
        AppBar,
    },

    data: () => ({
        loading: false,
        selection: 1,
    }),

    methods: {
        reserve () {
            this.loading = true
            setTimeout(() => (this.loading = false), 2000)
        },
    },

    setup() {
        const reviews = ref()
        const myReview = ref()
        const businessDetails = ref()
        const isLoading = ref()

        let router = useRouter();
        onMounted(() => {
            retrieveBusinessDetails()
            retrieveMyReview()
            retrieveReviews()
        });

        let id = router.currentRoute.value.params.id;

        const retrieveBusinessDetails = async () => {
            isLoading.value = true
            try {
                const res = await request('get', '/api/businesses/' + id + '/reviews')
                reviews.value = res.data
            } catch (e) {
                await router.push('/')
            }
        }

        const retrieveReviews = async () => {
            isLoading.value = true
            try {
                const res = await request('get', '/api/businesses/' + id)
                businessDetails.value = res.data
            } catch (e) {
                await router.push('/')
            }
        }

        const retrieveMyReview = async () => {
            isLoading.value = true
            try {
                const res = await request('get', '/api/businesses/' + id + '/my-review')
                if (res.data) {
                    myReview.value = res.data
                }
            } catch (e) {
                await router.push('/')
            }
        };

        const deleteMyReview = async () => {
            try {
                const res = await request('delete', '/api/my/reviews/' + myReview.value.id)
                if (res.status === 204) {
                    myReview.value = null;
                }
            } catch (e) {
                await router.push('/')
            }
        }

        return {
            reviews,
            myReview,
            businessDetails,
            isLoading,
            deleteMyReview
        }
    },
}
</script>
