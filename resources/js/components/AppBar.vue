<template>
    <v-layout class="overflow-visible" style="height: 56px;">
        <v-app-bar app color="deep-purple accent-4" dark dense>
            <router-link to="/home">
                <v-btn icon>
                    <v-icon
                        class="ml-3 mb-1"
                        size="x-large"
                        aria-hidden="false"
                        color="Yellow"> mdi-food
                    </v-icon>
                </v-btn>
            </router-link>
            <v-toolbar-title>FoodCritique - oceń restauracje</v-toolbar-title>
            <v-spacer></v-spacer>

            <v-btn icon>
                <v-icon>mdi-heart</v-icon>
            </v-btn>
            <v-btn icon v-if="handleSearch">
                <v-icon>mdi-magnify</v-icon>
            </v-btn>
            <v-btn icon v-if="handleFilter">
                <v-icon>mdi-filter</v-icon>
            </v-btn>
            <v-btn icon>
                <v-icon>mdi-account</v-icon>
            </v-btn>
            <v-btn icon @click="handleLogout">
                <v-icon> mdi-logout</v-icon>
            </v-btn>
        </v-app-bar>
    </v-layout>
</template>
<script>
import {useRouter} from "vue-router";

export default {

    props: ['handleSearch', 'handleFilter'],

    setup() {

        let router = useRouter();

        const handleLogout = () => {
            localStorage.removeItem('APP_USER_TOKEN')
            router.push('/')
        }

        return {
            handleLogout,
        }
    },
}
</script>
