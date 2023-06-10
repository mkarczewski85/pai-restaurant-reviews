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

                <v-dialog v-model="passwordDialog" persistent width="1024">
                    <template v-slot:activator="{ props }">
                        <v-btn v-bind="props" prepend-icon="mdi-lock-outline" variant="plain" class="mr-2">
                            Zmień hasło
                        </v-btn>
                    </template>
                    <v-form @submit.prevent="handlePasswordChange">
                        <v-card>
                            <v-card-title>
                                <span class="text-h5">Zmiana hasła</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field
                                                label="Stare hasło"
                                                type="password"
                                                :rules="[v => v.length > 0 || 'Pole wymagane']"
                                                v-model="passwordForm.current_password"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field
                                                label="Nowe hasło"
                                                :rules="newPasswordRules"
                                                type="password"
                                                v-model="passwordForm.new_password"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field
                                                label="Potwierdź nowe hasło"
                                                :rules="confirmPasswordRules"
                                                type="password"
                                                v-model="passwordForm.confirm_password"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue-darken-1" variant="text" @click="passwordDialog = false">
                                    Anuluj
                                </v-btn>
                                <v-btn color="blue-darken-1" variant="text" type="submit">
                                    Zmień
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-form>
                </v-dialog>

                <v-dialog v-model="dataDialog" persistent width="1024">
                    <template v-slot:activator="{ props }">
                        <v-btn v-bind="props" prepend-icon="mdi-pencil" variant="plain" class="ml-2">
                            Aktualizuj dane
                        </v-btn>
                    </template>
                    <v-form @submit.prevent="handleDataChange">
                        <v-card>
                            <v-card-title>
                                <span class="text-h5">Aktualizacja danych</span>
                            </v-card-title>
                            <v-card-text>
<!--                                <v-alert v-if="updateError" :text="updateErrors" type="error" variant="outlined"></v-alert>-->
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field
                                                label="Imię"
                                                :rules="[v => v.length > 0 || 'Pole wymagane']"
                                                v-model="dataForm.name"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field
                                                label="Email"
                                                :rules="emailRules"
                                                v-model="dataForm.email"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue-darken-1" variant="text" @click="dataDialog = false">
                                    Anuluj
                                </v-btn>
                                <v-btn color="blue-darken-1" variant="text" type="submit">
                                    Zapisz
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-form>
                </v-dialog>
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
import {onMounted, reactive, ref} from "vue";
import {request} from "@/helper";
import AppBar from "@/components/AppBar.vue";
import router from "@/route";
import axios from "axios";
import {value} from "lodash/seq";


export default {
    components: {
        AppBar
    },

    methods: {
        value,
        formatDate(date) {
            const options = {day: '2-digit', month: '2-digit', year: 'numeric'};
            return new Date(date).toLocaleDateString('pl-PL', options);
        }
    },

    setup() {
        const user = ref()
        const passwordDialog = ref(false);
        const dataDialog = ref(false);
        const passwordForm = reactive({
            current_password: '',
            new_password: '',
            confirm_password: '',
        });
        const dataForm = reactive({
            name: '',
            email: '',
        });

        const newPasswordRules = [
            value => {
                if (value.length >= 6) return true
                return 'Hasło musi mieć min. 6 znaków.'
            },
            value => {
                if (/^(?=.*[A-Z])(?=.*\d)(?=.{1,}[!@#$%&*()-=_+{};":|,.<>/?])\S{6,}$/.test(value)) return true;
                return 'Min. 6 znaków, min. 1 duża litera, min. 1 cyfra, min. 1 znak specjalny.'
            },
            value => {
                if (value) return true
                return 'Pole wymagane.'
            },
        ];

        const confirmPasswordRules = [
            value => {
                if (value === passwordForm.new_password) return true
                return 'Hasła się różnią.'
            },
        ];

        const emailRules = [
            value => {
                if (value) return true
                return 'Pole wymagane.'
            },
            value => {
                if (/.+@.+\..+/.test(value)) return true
                return 'Nieprawidłowy adres email.'
            },
        ];


        onMounted(() => {
            userDetails()
        });

        const userDetails = async () => {
            try {
                const res = await request('get', '/api/user-details')
                user.value = res.data
                dataForm.name = user.value.name
                dataForm.email = user.value.email
            } catch (e) {
                await router.push('/')
            }
        };

        const handlePasswordChange = async (evt) => {
            evt.preventDefault()
            try {
                const res = await request('put', '/api/user-password', passwordForm)
            } catch (e) {
                if (e.response.data.errors) {
                    console.log(e)
                }
            }
            passwordDialog.value = false;
        };

        const handleDataChange = async (evt) => {
            evt.preventDefault();
            try {
                const res = await request('put', '/api/user-data', dataForm)
                user.value.name = res.data.name
                user.value.email = res.data.email
            } catch (e) {
                if (e.response.data.errors) {
                    console.log(e)
                }
            }
            dataDialog.value = false;
        };

        return {
            user,
            passwordForm,
            passwordDialog,
            newPasswordRules,
            confirmPasswordRules,
            dataForm,
            dataDialog,
            emailRules,
            handlePasswordChange,
            handleDataChange,
        }
    }
}
</script>
