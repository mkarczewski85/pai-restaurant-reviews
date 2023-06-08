<template>
    <v-container style="overflow-y: hidden" class="mt-10">
        <v-infinite-scroll @load="load" style="overflow-x: hidden;" v-if="favorites != null">
            <v-row align="center">
                <v-col v-for="favorite in favorites" :key="favorite.id" cols="4">
                    <BusinessCard :business="favorite"
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
        <div class="center" v-if="!favorites">
            <v-alert text="Brak ulubionych" variant="outlined"></v-alert>
        </div>
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
        const favorites = ref([])
        const isLoading = ref()
        const limit = ref(10)
        const offset = ref(0)

        let router = useRouter();
        onMounted(() => {
            retrieveFavorites()
            authentication()
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

        const retrieveFavorites = async (done) => {
            isLoading.value = true
            let init = !favorites.value.length
            try {
                const res = await request('get', '/api/favorites' + '?limit=' + limit.value + '&offset=' + offset.value)
                if (res.data.length) {
                    favorites.value.push(...res.data)
                    offset.value += limit.value
                    if (init) return
                    done('ok')
                } else {
                    if (!favorites.value.length) favorites.value = null
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

        const handleFavorite = async (favorite) => {
            let op = favorite.is_favorite ? 'delete' : 'post'
            try {
                const res = await request(op, '/api/favorites/' + favorite.id)
                favorite.is_favorite = !favorite.is_favorite
            } catch (e) {
                await router.push('/')
            }
        }

        const load = ({done}) => {
            setTimeout(() => {
                retrieveFavorites(done)
            }, 1000)
        }

        return {
            user,
            favorites,
            isLoading,
            handleLogout,
            handleFavorite,
            load
        }
    },
}
</script>

<style>
.center {
    margin: 0;
    position: absolute;
    top: 50%;
    left: 50%;
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
}
</style>
