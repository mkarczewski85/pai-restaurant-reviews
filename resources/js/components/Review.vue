<template>
    <v-sheet border rounded elevation="12" class="ma-6">
        <div class="ps-5 pt-3">{{ review.author }} ({{ review.created_at }})
        </div>
        <v-rating
            class="ps-3"
            v-model="review.rating"
            size="x-small"
            readonly
        ></v-rating>
        <v-banner :lines="lines" :text="review.review_text" :stacked="false">
            <template v-slot:actions>
                <v-btn icon="mdi-thumb-up-outline"></v-btn>
                <v-btn @click="changeLines()" class="pt-2">{{ btnLabel }}</v-btn>
            </template>
        </v-banner>
    </v-sheet>
</template>

<script>
import {ref} from 'vue'
export default {
    props: ['review'],

    setup() {
        const lines = ref('three')
        const btnLabel = ref("rozwiń")

        function changeLines() {
            lines.value =  lines.value == 'default' ? 'three' : 'default'
            btnLabel.value = lines.value == 'default' ? "zwiń" : 'rozwiń'
        }

        return {
            lines,
            btnLabel,
            changeLines
        }
    }
}
</script>
