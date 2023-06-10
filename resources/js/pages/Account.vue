<template>
    <v-container style="overflow-y: auto;" class="mt-15">
        <v-card>
            <v-list lines="two" subheader>
                <v-list-subheader>Dane rejestracyjne</v-list-subheader>
                <v-list-item title="Imię:" :subtitle="user.name"></v-list-item>
                <v-list-item title="Email:" :subtitle="user.email"></v-list-item>
                <v-list-item title="Data zarejestracji:" :subtitle="formatDate(user.created_at)"></v-list-item>
            </v-list>
            <div style="display: inline-block" class="ma-4">
                <v-btn prepend-icon="mdi-lock-outline" variant="plain" class="mr-2">
                    Zmień hasło
                </v-btn>

                <v-btn prepend-icon="mdi-pencil" variant="plain" class="ml-2">
                    Aktualizuj dane
                </v-btn>
            </div>

            <v-divider class="border-opacity-75" color="info"></v-divider>
            <v-list lines="two" header>
                <v-list-subheader>Statystyki</v-list-subheader>
                <v-list-item>
                    <template v-slot:title>Liczba ulubionych: {{ user.review_stats.count_favorites }}</template>
                </v-list-item>
                <v-list-item>
                    <template v-slot:title>Liczba recenzji: {{ user.review_stats.reviews_count }}</template>
                </v-list-item>
                <v-list-item>
                    <template v-slot:title>Liczba polubień recenzji: {{ user.review_stats.likes_count }}</template>
                </v-list-item>
            </v-list>
        </v-card>
    </v-container>
    <AppBar></AppBar>
</template>

<script>
import {onMounted, ref} from "vue";
import {request} from "@/helper";
import AppBar from "@/components/AppBar.vue";
import router from "@/route";


export default {
    components: {
        AppBar
    },

    methods: {
        formatDate(date) {
            const options = {day: '2-digit', month: '2-digit', year: 'numeric'};
            return new Date(date).toLocaleDateString('pl-PL', options);
        }
    },

    setup() {
        const user = ref()

        onMounted(() => {
            userDetails()
        });

        const userDetails = async () => {
            try {
                const res = await request('get', '/api/user-details')
                user.value = res.data
            } catch (e) {
                await router.push('/')
            }
        }
        return {
            user
        }
    }
}
</script>
