<template>
    <div>
        <div v-for="review in reviews" :key="review.review_id">
            <v-divider></v-divider>
            <v-card-subtitle>
                <strong>
                    {{ review.contributor }}
                </strong>
                - {{ review.created }}
                <br>
                <div>
                    <span v-for="n in review.stars.full"><v-icon>mdi-star</v-icon></span><span v-for="n in review.stars.half"><v-icon>mdi-star-half-full</v-icon></span><span v-for="n in review.stars.empty"><v-icon>mdi-star-outline</v-icon></span>
                </div>
            </v-card-subtitle>
            <v-card-text>{{ review.content }}</v-card-text>
        </div>
        <div v-if="reviews.length === 0 && loading === false">
            K tomuto produktu nejsou žádné recenze.
        </div>
    </div>
</template>

<script>
    export default {
        name: "reviews",
        data() {
            return {
                reviews: [],
                loading: true
            }
        },
        props: {
            productName: {
                type: String,
                required: true
            }
        },
        methods: {
            async loadReviews(){
                const encodedProductName = encodeURIComponent(encodeURIComponent(this.productName));
                const response = await this.$http.get(`/api/getReviews/${encodedProductName}`);
                this.reviews = response.data;
            },
            loadStars(){
                for (let i=0; i<this.reviews.length; i++){
                    this.reviews[i].stars = {
                        full: Math.floor(this.reviews[i].stars / 2),
                        half: this.reviews[i].stars % 2,
                        empty: 5 - (Math.floor(this.reviews[i].stars / 2)) - (this.reviews[i].stars % 2),
                    }
                }

            }
        },
        async mounted() {
            await this.loadReviews();
            await this.loadStars();
            this.loading = false;
        }
    }
</script>

<style scoped>

</style>
