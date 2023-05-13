<template>
    <div class="mx-auto w-4/12 mt-10 bg-blue-200 p-4 rounded-lg">
        <!-- component -->
        <div class="bg-white shadow-lg rounded-lg px-8 pt-6 pb-8 mb-2 flex flex-col">
            <h1 class="text-gray-600 py-5 font-bold text-3xl"> Logowanie </h1>
            <v-alert
                type="error"
                v-if="errors"
            >{{ errors }}</v-alert>
            <br>
            <v-form @submit.prevent="handleLogin">
                <v-text-field
                    label="Email"
                    v-model="form.email"
                    :rules="emailRules"
                    type="email"
                    required
                ></v-text-field>

                <v-text-field
                    label="Hasło"
                    v-model="form.password"
                    :rules="passwordRules"
                    type="password"
                    required
                ></v-text-field>

                <v-btn color="primary" type="submit">Zaloguj</v-btn>

                <p class="mt-4">Nie masz jeszcze konta?
                    <router-link to="register" class="link">Zarejestruj sie</router-link>
                </p>
            </v-form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import {reactive, ref} from 'vue';
import { useField, useForm } from 'vee-validate'
import {createRouter as router, useRouter} from "vue-router";

export default {
    setup() {
        const errors = ref()
        const router = useRouter();
        const form = reactive({
            email: '',
            password: '',
        })

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

        const passwordRules = [
            value => {
                if (value) return true
                return 'Pole wymagane.'
            },
        ];

        const handleLogin = async () => {
            try {
                const res = await axios.post('/api/auth/login', form)
                if (res.data.status && res.data.token) {
                    localStorage.setItem('APP_USER_TOKEN', res.data.token)
                    await router.push('home')
                }
            } catch (e) {
                if (e && e.response.data && e.response.data.errors) {
                    errors.value = Object.values(e.response.data.errors)
                } else {
                    errors.value = e.response.data.message || ""
                }
            }
        }

        return {
            form,
            errors,
            emailRules,
            passwordRules,
            handleLogin,
        }
    }
}
</script>

<style>
.link {
    color: blue;
    text-decoration: underline;
}
</style>
