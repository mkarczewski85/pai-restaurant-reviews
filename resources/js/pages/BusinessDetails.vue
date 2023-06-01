<template>

    <v-container style="overflow-y: auto;">

        <BusinessDetailsCard :businessDetails="businessDetails" :loading="loading"></BusinessDetailsCard>

        <MyReview :myReview="myReview" :deleteFunction="deleteMyReview" v-if="myReview"></MyReview>
        <v-sheet
            v-else
            class="d-flex align-center justify-center flex-wrap text-center mx-auto"
            elevation="4"
            height="250"
            rounded
            max-width="800"
            width="100%">
            <div>
                <div class="text-h5 font-weight-medium mb-2">
                    Nie wystawiłeś jeszcze swojej opinii!
                </div>
                <v-dialog
                    v-model="dialog"
                    persistent
                    width="1024">
                    <template v-slot:activator="{ props }">
                        <v-btn v-bind="props" prepend-icon="mdi-typewriter" color="primary" class="mt-4">Recenzuj
                        </v-btn>
                    </template>
                    <ReviewForm :closeDialog="closeDialog" :handleReviewSubmit="storeMyReview"></ReviewForm>
                </v-dialog>
            </div>
        </v-sheet>

        <v-infinite-scroll @load="load">
            <template v-for="(review, index) in reviews" :key="review">
                <Review :review="review" :handleLike="handleLike"></Review>
            </template>
            <template v-slot:empty>
                <v-alert variant="outlined" density="compact" max-width="400">
                    <div style="display: flex; justify-content: center; align-items: center; height: 100%;">Brak więcej wyników</div>
                </v-alert>
            </template>
        </v-infinite-scroll>
    </v-container>
    <AppBar></AppBar>
</template>
<script>
import Review from "@/components/Review.vue";
import {ref, onMounted} from 'vue'
import {createRouter as router, useRouter} from "vue-router";
import {request} from '../helper'
import Loader from '../components/Loader.vue';
import BusinessCard from "@/components/BusinessCard.vue";
import AppBar from "@/components/AppBar.vue";
import BusinessDetailsCard from "@/components/BusinessDetailsCard.vue";
import MyReview from "@/components/MyReview.vue";
import ReviewForm from "@/components/ReviewForm.vue";

export default {

    components: {
        ReviewForm,
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

    setup() {
        const dialog = ref(false)
        const reviews = ref([])
        const myReview = ref()
        const businessDetails = ref()
        const isLoading = ref()
        const limit = ref(10)
        const offset = ref(0)
        const scrollStatus = ref('ok')

        let router = useRouter();
        let id = router.currentRoute.value.params.id;


        onMounted(() => {
            retrieveBusinessDetails()
            retrieveMyReview()
            // retrieveReviews()
        });

        const retrieveReviews = async () => {
            isLoading.value = true
            try {
                const res = await request('get', '/api/businesses/' + id + '/reviews' + '?limit=' + limit.value + '&offset=' + offset.value)
                reviews.value.push(...res.data)
                if (res.data.length < limit.value) {
                    scrollStatus.value = 'empty'
                } else {
                    offset.value += limit.value
                }

            } catch (e) {
                scrollStatus.value = 'error'
                await router.push('/')
            }
        }

        const retrieveBusinessDetails = async () => {
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
                const res = await request('delete', '/api/businesses/' + id + '/my-review')
                if (res.status === 204) {
                    myReview.value = null;
                }
            } catch (e) {
                console.log(e)
                await router.push('/')
            }
        }

        const storeMyReview = async (data) => {
            const res = await request('post', '/api/businesses/' + id + '/my-review', data)
            if (res.data) {
                myReview.value = data;
            }
            dialog.value = false;
        }

        const handleLike = async (review) => {
            let op = review.is_liked ? 'delete' : 'post'
            try {
                const res = await request(op, '/api/review/' + review.id + '/like')
                review.is_liked = !review.is_liked
            } catch (e) {
                await router.push('/')
            }
        }

        const closeDialog = () => {
            dialog.value = false;
        }

        const load = ({done}) => {
            setTimeout(() => {
                retrieveReviews()
                done(scrollStatus.value)
            }, 1000)
        }

        return {
            dialog,
            reviews,
            myReview,
            businessDetails,
            isLoading,
            deleteMyReview,
            storeMyReview,
            closeDialog,
            retrieveReviews,
            handleLike,
            load
        }
    },
}
</script>
