<template>
    <div class="w-6/12 p-10 mx-auto">
        <div class="flex justify-between">
            <h1 class="text-2xl"> Aplikacja </h1>
            <span class="capitalize">Witaj {{ user && user.name }}, <button
                class="text-orange-500 underline hover:no-underline rounded-md"
                @click="handleLogout">Wyloguj</button></span>
        </div>
    </div>

    <v-container style="overflow-y: auto;">
        <v-row align="center">
            <v-col v-for="business in businesses" :key="business.id" cols="4">
                <BusinessCard :business="business" :showDetails="showDetails">

                </BusinessCard>
            </v-col>
        </v-row>
    </v-container>
    <v-layout class="overflow-visible" style="height: 56px;">
        <v-bottom-navigation grow>
            <v-btn value="list">
                <v-icon>mdi-apps</v-icon>

                Lista
            </v-btn>

            <v-btn value="favorites">
                <v-icon>mdi-heart</v-icon>

                Ulubione
            </v-btn>

            <v-btn value="reviews">
                <v-icon>mdi-typewriter</v-icon>

                Moje opinie
            </v-btn>
        </v-bottom-navigation>
    </v-layout>
</template>
<script>
import {ref, onMounted} from 'vue'
import {createRouter as router, useRouter} from "vue-router";
import {request} from '../helper'
import Loader from '../components/Loader.vue';
import BusinessCard from "@/components/BusinessCard.vue";

export default {
    components: {
        BusinessCard,
        Loader,
    },

    data: () => ({
        loading: false,
        selection: 1,
    }),

    methods: {
        reserve() {
            this.loading = true

            setTimeout(() => (this.loading = false), 2000)
        },
    },

    setup() {
        const user = ref()
        const businesses = ref()
        const isLoading = ref()

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
                const res = await request('get', '/api/businesses')
                businesses.value = res.data
            } catch (e) {
                await router.push('/')
            }
        }

        const handleLogout = () => {
            localStorage.removeItem('APP_USER_TOKEN')
            router.push('/')
        }

        const showDetails = (business_id) => {
            router.push({name: 'businessDetails', params: {id: business_id}})
        }

        return {
            user,
            businesses,
            isLoading,
            handleLogout,
            showDetails
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
