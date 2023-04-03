<template>
    <v-container>

        <div v-for="shop in shops" :key="shop.shop_name">
            <v-card class="pa-4 mb-3 mx-auto" outlined >
            <v-row align="center">


                <a :href="shop.site_url" target="_blank">
                    <v-img
                        class="ma-2"
                        max-height="50"
                        max-width="100"
                        :src="shop.logo_url"
                        contain
                    ></v-img>
                </a>

                <a :href="shop.site_url" target="_blank">
                    <v-card-title class="title">
                        {{ shop.shop_name }}
                    </v-card-title>
                </a>

                <v-spacer></v-spacer>

                <div class="text-center">
                    <div>
                        <strong>{{ shop.price }}</strong> Kč
                    </div>
                    <div>
                    doprava
                        <span v-if="shop.min_delivery_price > 0">{{ shop.min_delivery_price }} Kč</span>
                        <span v-if="shop.min_delivery_price === 0">zdarma</span>
                    </div>
                </div>



                <a :href="shop.url" target="_blank" class="ma-4">
                    <v-btn outlined color="orange darken-1">
                        Do Obchodu
                    </v-btn>
                </a>

            </v-row>
            </v-card>
        </div>

    </v-container>
</template>

<script>
    export default {
        name: "ShopOptions",
        props: {
            shops: {
                type: Array,
                required: true
            }
        }
    }
</script>

<style scoped>
    a {
        text-decoration: none;
    }
    .title {
        color: black;
    }
</style>
