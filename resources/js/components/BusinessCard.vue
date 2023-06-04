<template>
    <v-card
        :loading="loading"
        class="mx-auto my-12"
        height="460"
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
            :src="business.main_photo"
        ></v-img>
        <router-link :to="{name: 'businessDetails', params: {id: business.id}}">
            <v-card-item>
                <v-card-title>{{ business.name }}</v-card-title>

                <v-card-subtitle>
                    <span class="me-1">{{ business.address }}, {{ business.city }}</span>
                </v-card-subtitle>
            </v-card-item>
        </router-link>
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
        </v-card-text>

        <v-card-actions>
            <v-btn class="ml-auto" @click="handleFavorite(business)">
                <v-icon v-if="business.is_favorite === true" color="red">mdi-heart</v-icon>
                <v-icon v-else>mdi-heart-outline</v-icon>
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import review from "@/components/Review.vue";

export default {

    props: ['business', 'handleFavorite']

}
</script>
