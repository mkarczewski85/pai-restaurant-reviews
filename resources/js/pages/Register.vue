<template>
    <div class="mx-auto w-4/12 mt-10 bg-blue-200 p-4 rounded-lg">
        <!-- component -->
        <div
            class="bg-white shadow-lg rounded-lg px-8 pt-6 pb-8 mb-2 flex flex-col">
            <h1 class="text-gray-600 py-5 font-bold text-3xl"> Rejestracja konta </h1>
            <v-alert
                type="error"
                v-if="errors"
            >{{ errors }}</v-alert>
            <br>
            <v-form @submit.prevent="handleSubmit">
                <v-text-field
                    label="Imię"
                    v-model="form.name"
                    :rules="nameRules"
                    type="name"
                    required
                ></v-text-field>

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

                <v-btn color="primary" type="submit">Zarejestruj</v-btn>

                <p class="mt-4">Masz już konto?
                    <router-link to="login" class="link">Zaloguj się</router-link>
                </p>
            </v-form>
        </div>
    </div>
</template>

<script>
import {reactive, ref} from 'vue';
import axios from 'axios';
import {createRouter as router, useRouter} from "vue-router";

export default {
    setup() {
        const errors = ref();
        let router = useRouter();
        const form = reactive({
            name: '',
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
                if (value.length >= 6) return true
                return 'Hasło musi mieć min. 6 znaków.'
            },
            value => {
                if (/^(?=.*[A-Z])(?=.*\d)(?=.{1,}[!@#$%&*()-=_+{};":|,.<>/?])\S{6,}$/.test(value)) return true;
                return 'Nieprawidłowe hasło.'
            },
            value => {
                if (value) return true
                return 'Pole wymagane.'
            },
        ];

        const nameRules = [
            value => {
                if (value.length >= 3) return true
                return 'Imię musi mieć min. 3 znaki.'
            },
            value => {
                if (value) return true
                return 'Pole wymagane.'
            },
        ];

        const handleSubmit = async (evt) => {
            evt.preventDefault()
            try {
                const res = await axios.post('/api/auth/register', form);
                if (res.data.status && res.data.token) {
                    localStorage.setItem('APP_USER_TOKEN', res.data.token)
                    await router.push('home')
                }
            } catch (e) {
                if (e.response.data && e.response.data.errors) {
                    errors.value = Object.values(e.response.data.errors).join('; ')
                }
            }
        }
        return {
            form,
            errors,
            emailRules,
            passwordRules,
            nameRules,
            handleSubmit,
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
