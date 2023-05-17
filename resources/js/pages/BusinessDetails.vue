<template>
    <v-container style="overflow-y: auto;">

                <Review v-for="review in reviews" :review="review">

                </Review>
    </v-container>
</template>
<script>
import Review from "@/components/Review.vue";
import {ref, onMounted} from 'vue'
import {createRouter as router, useRouter} from "vue-router";
import {request} from '../helper'
import Loader from '../components/Loader.vue';
import route from "@/route";

export default {

    components: {
        Review,
        Loader,
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
        const isLoading = ref()

        let router = useRouter();
        onMounted(() => {
            retrieveReviews()
        });

        let id = router.currentRoute.value.params.id;

        const retrieveReviews = async () => {
            isLoading.value = true
            try {
                const res = await request('get', '/api/businesses/' + id + '/reviews')
                reviews.value = res.data
            } catch (e) {
                await router.push('/')
            }
        }

        return {
            reviews,
            isLoading,
        }
    },
}
</script>
