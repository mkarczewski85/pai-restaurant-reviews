<template>
    <v-card
        :loading="loading"
        class="mx-auto my-12"
        height="450"
        width="374">
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
            <v-card-title>{{ business.name }}</v-card-title>

            <v-card-subtitle>
                <span class="me-1">{{ business.address }}, {{ business.city }}</span>
            </v-card-subtitle>
        </v-card-item>

        <v-card-text>
            <v-row
                align="center"
                class="mx-0"
            >
                <v-rating
                    v-model="business.avg_rating"
                    color="amber"
                    density="compact"
                    readonly
                    size="small"
                ></v-rating>

                <div class="text-grey ms-4">
                    ({{ business.total_reviews }})
                </div>
            </v-row>

            <div class="my-4 text-subtitle-1">
                <div class="repeat">
                    <div v-for="n in business.price_level" :key="n" class="dollar" style="display: inline-block;">
                        $
                    </div>
                    <span style="display: inline-block;"> • {{ business.category_name }}</span>
                </div>
            </div>

<!--            <v-banner lines="one" text="..." :stacked="false">-->
<!--                <template v-slot:text>-->
<!--                    <div>{{ business.description }}</div>-->
<!--                </template>-->
<!--            </v-banner>-->
        </v-card-text>

<!--        <v-divider class="mx-4 mb-1"></v-divider>-->

        <v-card-actions>
            <v-btn
                color="deep-purple-lighten-2"
                variant="text"
                @click="showDetails(business.id)">
                Szczegóły
            </v-btn>
            <v-btn
                color="deep-purple-lighten-2"
                variant="text"
                @click="review">
                Oceń
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import review from "@/components/Review.vue";

export default {

    props: ['business', 'showDetails']

}
</script>
