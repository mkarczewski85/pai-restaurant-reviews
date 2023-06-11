<template>
    <v-container style="overflow-y: hidden" class="mt-10">

<!--        TODO: implement search-->
<!--        <v-card-->
<!--            class="pa-4"-->
<!--            flat-->
<!--            height="150px"-->
<!--        >-->
<!--                <v-text-field-->
<!--                    hide-details-->
<!--                    prepend-icon="mdi-magnify"-->
<!--                    single-line-->
<!--                    clearable="true"-->
<!--                    onsubmit="searchFor"-->
<!--                    on-click:clear="resetResult"-->
<!--                    class="mt-5"-->
<!--                ></v-text-field>-->
<!--        </v-card>-->

        <v-infinite-scroll mode="manual" @load="load" style="overflow-x: hidden;">
            <v-row align="center">
                <v-col v-for="business in businesses" :key="business.id" cols="4">
                    <BusinessCard :business="business"
                                  :handleFavorite="handleFavorite">
                    </BusinessCard>
                </v-col>
            </v-row>
            <template v-slot:load-more="{ props }">
                <v-btn
                    text="Więcej..."
                    variant="outlined"
                    color="primary"
                    v-bind="props"
                ></v-btn>
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
    }),

    setup() {
        const businesses = ref([])
        const isLoading = ref()
        const limit = ref(10)
        const offset = ref(0)

        let router = useRouter();
        onMounted(() => {
            retrieveBusinesses()
        });

        const retrieveBusinesses = async (done) => {
            isLoading.value = true
            let init = businesses.value.length === 0
            try {
                const res = await request('get', '/api/businesses' + '?limit=' + limit.value + '&offset=' + offset.value)
                if (res.data.length) {
                    businesses.value.push(...res.data)
                    offset.value += limit.value
                    if (init) return
                    done('ok')
                } else {
                    done('empty')
                }

            } catch (e) {
                console.log(e)
                done('error')
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
                retrieveBusinesses(done)
            }, 1000)
        }

        return {
            businesses,
            isLoading,
            handleLogout,
            handleFavorite,
            load
        }
    },
}
</script>
