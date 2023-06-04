<template>
    <v-card
        :loading="loading"
        class="mx-auto my-12 pt-4">
        <template v-slot:loader="{ isActive }">
            <v-progress-linear
                :active="isActive"
                color="deep-purple"
                height="4"
                indeterminate
            ></v-progress-linear>
        </template>

        <v-carousel show-arrows="hover" v-if="businessDetails != null">
            <v-carousel-item v-for="(photo, index) in businessDetails.photos" :key="photo" :src="photo" cover></v-carousel-item>
        </v-carousel>

        <v-card-item>
            <v-card-title>{{ businessDetails.name }}</v-card-title>

            <v-card-subtitle>
                <span class="me-1">{{ businessDetails.address }}, {{ businessDetails.city }}</span>
            </v-card-subtitle>

        </v-card-item>

        <v-card-text>
            <v-row
                align="center"
                class="mx-0"
            >
                <v-rating
                    v-model="businessDetails.avg_rating"
                    color="amber"
                    density="compact"
                    readonly
                    size="small"
                ></v-rating>

                <div class="text-grey ms-4">
                    ({{ businessDetails.total_reviews }})
                </div>
            </v-row>

            <div class="my-4 text-subtitle-1">
                <div class="repeat">
                    <div v-for="n in businessDetails.price_level" :key="n" class="dollar" style="display: inline-block;">
                        $
                    </div>
                    <span style="display: inline-block;"> • {{ businessDetails.category_name }}</span>
                </div>
            </div>

            <v-banner lines="default" text="..." :stacked="false">
                <template v-slot:text>
                    <div>{{ businessDetails.description }}</div>
                </template>
            </v-banner>
        </v-card-text>

        <v-card-actions>
            <v-btn @click="handleFavorite(businessDetails)">
                <v-icon v-if="businessDetails.is_favorite === true" color="red">mdi-heart</v-icon>
                <v-icon v-else>mdi-heart-outline</v-icon>
            </v-btn>
            <v-btn :disabled="!businessDetails.homepage" :href="'//' + businessDetails.homepage" target="_blank">
                <v-icon>mdi-web</v-icon>
            </v-btn>
            <v-btn :disabled="!businessDetails.facebook_profile" :href="'//' + businessDetails.facebook_profile" target="_blank">
                <v-icon>mdi-facebook</v-icon>
            </v-btn>
            <v-btn :disabled="!businessDetails.instagram_profile" :href="'//' + businessDetails.instagram_profile" target="_blank">
                <v-icon>mdi-instagram</v-icon>
            </v-btn>
        </v-card-actions>
    </v-card>

</template>
<script>
import {request} from '../helper'
import {createRouter as router, useRouter} from "vue-router";

export default {

    props: ['businessDetails', 'loading'],

    setup() {
        const handleFavorite = async (businessDetails) => {
            let op = businessDetails.is_favorite ? 'delete' : 'post'
            try {
                const res = await request(op, '/api/favorites/' + businessDetails.id)
                businessDetails.is_favorite = !businessDetails.is_favorite
            } catch (e) {
                await router.push('/')
            }
        }
        return {
            handleFavorite
        }
    }
}
</script>
