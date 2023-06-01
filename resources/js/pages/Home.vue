<template>
    <v-container style="overflow-y: hidden;" class="mt-10">
        <v-infinite-scroll @load="load">
            <v-row align="center">
                <v-col v-for="business in businesses" :key="business.id" cols="4">
                    <BusinessCard :business="business"
                                  :handleFavorite="handleFavorite">
                    </BusinessCard>
                </v-col>
            </v-row>
<!--            <template v-slot:load-more="{ props }">-->
<!--                <v-btn-->
<!--                    text="Więcej..."-->
<!--                    variant="outlined"-->
<!--                    color="primary"-->
<!--                    v-bind="props"-->
<!--                ></v-btn>-->
<!--            </template>-->
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
import {ref, onMounted} from 'vue'
import {createRouter as router, useRouter} from "vue-router";
import {request} from '../helper'
import Loader from '../components/Loader.vue';
import BusinessCard from "@/components/BusinessCard.vue";
import AppBar from "@/components/AppBar.vue";

export default {
    components: {
        AppBar,
        BusinessCard,
        Loader,
    },

    data: () => ({
        loading: false,
        selection: 1,
    }),

    setup() {
        const user = ref()
        const businesses = ref([])
        const isLoading = ref()
        const limit = ref(10)
        const offset = ref(0)
        const scrollStatus = ref('ok')

        let router = useRouter();
        onMounted(() => {
            authentication()
            retrieveBusinesses()
        });

        const authentication = async () => {
            isLoading.value = true
            try {
                const res = await request('get', '/api/user')
                user.value = res.data
            } catch (e) {
                await router.push('/')
            }
        }

        const retrieveBusinesses = async () => {
            isLoading.value = true
            try {
                const res = await request('get', '/api/businesses' + '?limit=' + limit.value + '&offset=' + offset.value)
                businesses.value.push(...res.data)
                if (res.data.length < limit.value) {
                    scrollStatus.value = 'empty'
                } else {
                    offset.value += limit.value
                }
            } catch (e) {
                console.log(e)
                scrollStatus.value = 'error'
                await router.push('/')
            }
        }

        const handleLogout = () => {
            localStorage.removeItem('APP_USER_TOKEN')
            router.push('/')
        }

        const handleFavorite = async (business) => {
            let op = business.is_favorite ? 'delete' : 'post'
            try {
                const res = await request(op, '/api/favorites/' + business.id)
                business.is_favorite = !business.is_favorite
            } catch (e) {
                await router.push('/')
            }
        }

        const load = ({done}) => {
            setTimeout(() => {
                retrieveBusinesses()
                done(scrollStatus.value)
            }, 1000)
        }

        return {
            user,
            businesses,
            isLoading,
            handleLogout,
            handleFavorite,
            load
        }
    },
}
</script>

<style>
.repeat {
    display: flex;
}

.dollar {
    margin-right: 2px;
}
</style>
