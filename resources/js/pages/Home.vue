<template>
    <div class="w-6/12 p-10 mx-auto">
        <div class="flex justify-between">
            <h1 class="text-2xl"> Aplikacja </h1>
            <span class="capitalize">Witaj {{ user && user.name }}, <button
                class="text-orange-500 underline hover:no-underline rounded-md"
                @click="handleLogout">Wyloguj</button></span>
        </div>
    </div>

    <v-card
        :loading="loading"
        class="mx-auto my-12"
        max-width="374"
    >
        <template v-slot:loader="{ isActive }">
            <v-progress-linear
                :active="isActive"
                color="deep-purple"
                height="4"
                indeterminate
            ></v-progress-linear>
        </template>

        <v-img
            cover
            height="250"
            src="https://cdn.vuetifyjs.com/images/cards/cooking.png"
        ></v-img>

        <v-card-item>
            <v-card-title>Cafe Badilico</v-card-title>

            <v-card-subtitle>
                <span class="me-1">Local Favorite</span>

                <v-icon
                    color="error"
                    icon="mdi-fire-circle"
                    size="small"
                ></v-icon>
            </v-card-subtitle>
        </v-card-item>

        <v-card-text>
            <v-row
                align="center"
                class="mx-0"
            >
                <v-rating
                    :model-value="3"
                    color="amber"
                    density="compact"
                    readonly
                    size="small"
                ></v-rating>

                <div class="text-grey ms-4">
                    4.5 (413)
                </div>
            </v-row>

            <div class="my-4 text-subtitle-1">
                $$ • Italian, Cafe
            </div>

            <div>Small plates, salads & sandwiches - an intimate setting with 12 indoor seats plus patio seating.</div>
        </v-card-text>

        <v-divider class="mx-4 mb-1"></v-divider>

        <v-card-actions>
            <v-btn
                color="deep-purple-lighten-2"
                variant="text"
                @click="review"
            >
                Add Review
            </v-btn>
        </v-card-actions>
    </v-card>

</template>
<script>
import {ref, onMounted} from 'vue'
import {createRouter as router, useRouter} from "vue-router";
import {request} from '../helper'
import Loader from '../components/Loader.vue';

export default {
    components: {
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
        const user = ref()
        const isLoading = ref()

        let router = useRouter();
        onMounted(() => {
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

        const handleLogout = () => {
            localStorage.removeItem('APP_USER_TOKEN')
            router.push('/')
        }

        return {
            user,
            isLoading,
            handleLogout,
        }
    },
}
</script>
