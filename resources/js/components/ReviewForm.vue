<template>
    <v-card title="Twoja recenzja">
        <v-form @submit.prevent="handleReviewSubmit(form)">
            <v-container fluid>
                <v-textarea
                    counter
                    variant="outlined"
                    no-resize
                    label="Treść"
                    :rules="reviewRules"
                    v-model="form.review_text"
                ></v-textarea>
                <div class="ml-2">
                    Ocena:
                </div>
                <v-rating
                    v-model="form.rating"

                    hover
                ></v-rating>
            </v-container>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    type="submit"
                    color="blue-darken-1"
                    variant="text"
                >
                    Wyślij
                </v-btn>
                <v-btn
                    color="blue-darken-1"
                    variant="text"
                    @click="closeDialog"
                >
                    Porzuć
                </v-btn>
            </v-card-actions>
        </v-form>
    </v-card>
</template>

<script>
import {reactive} from 'vue';
import {value} from "lodash/seq";

export default {
    props: ['handleReviewSubmit', 'closeDialog'],

    setup() {
        const form = reactive({
            review_text: '',
            rating: 1,
        })

        const reviewRules = [
            value => {
                if (value) return true
                return 'Pole wymagane.'
            },
            value => {
                if (value.length <= 2500) return true
                return 'Maksymalnie. 2500 znaków.'
            },
        ];

        const ratingRules = [
            value => {
                if (value) return true
                return 'Pole wymagane.'
            },
            value => {
                if (value >= 1) return true
                return 'Możesz dać minimum jedną gwiazdkę'
            },
        ];

        return {
            form,
            reviewRules,
            ratingRules,
        }
    }
}

</script>
