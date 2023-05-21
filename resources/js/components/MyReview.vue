<template>
    <v-card>
        <v-card-item style="display: inline-block;">
            <v-card-title>Moja recenzja</v-card-title>
            <v-card-subtitle>{{ myReview.created_at }}</v-card-subtitle>
        </v-card-item>
        <v-card-text>
            {{ myReview.review_text }}
        </v-card-text>
        <v-card-actions>
            <v-rating
                class="ps-3"
                v-model="myReview.rating"
                size="x-small"
                readonly
            ></v-rating>
            <v-btn icon="mdi-delete" class="ml-auto" @click="deleteFunction"></v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>

export default {
    props: ['myReview', 'deleteFunction'],
}
</script>
