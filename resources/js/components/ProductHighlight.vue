<template>
    <div v-if="product" class="ma-4">
        <div class=" link">
            <v-card class="pa-4 tile" width="250" height="300" @click="route">
                <v-img
                    :src="product.img_url"
                    height="180px"
                    contain
                ></v-img>
                <v-card-text class="text-center">
                    <span class="productName">{{ product.name }}</span>
                    <br>
                    <span v-if="product.min_price">již od <strong>{{ product.min_price }} Kč</strong></span>
                </v-card-text>
            </v-card>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ProductHighlight",
        data() {
            return {
                product: null,
            }
        },
        props: {
            specificProduct: {
                type: Object,
                required: false
            },
        },
        methods: {
            async loadRandomProduct(){
                const response = await this.$http.get(`/api/getRandomProduct`);
                this.product = response.data;
            },
            route(){
                if (this.$router.name !== 'productDetails') this.$router.push({ name: 'productDetails', params: {productName: this.product.name}});
                this.$emit('reloadProductDetails');
            }
        },
        mounted() {
            if (this.specificProduct) this.product = this.specificProduct;
            else this.loadRandomProduct();
        }
    }
</script>

<style scoped>
    .tile:hover .productName {
        color: #FB8C00;
    }
    .link {
        text-decoration: none;
    }
</style>
