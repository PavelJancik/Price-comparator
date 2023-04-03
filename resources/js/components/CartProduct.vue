<template>
    <v-card  class="pa-3 mb-3" elevation="4">

        <v-btn icon color="red accent-4" title="Odstranit" @click="removeItem(selectedProduct.product_name)" class="deleteButton">
            <v-icon>mdi-delete-forever-outline</v-icon>
        </v-btn>

        <v-row class="align-center">

            <v-col cols="2">
                <v-img
                    class="ma-2"
                    lazy-src="/images/lazy-img.png"
                    max-height="90"
                    max-width="120"
                    :src="selectedProduct.img_url"
                    contain
                ></v-img>
            </v-col>

            <v-col cols="7"  justify="center">
                <router-link :to="{ name: 'productDetails', params: {productName: selectedProduct.product_name}}">
                    <div class="ma-2 title"> {{ selectedProduct.product_name }} </div>
                </router-link>
                <v-divider class="ma-3 mt-1"></v-divider>
                <v-select
                    :items="shopOptions"
                    v-model="selectedShop"
                    label="Obchod"
                    dense
                    outlined
                    :loading="shopOptionsLoading"
                    color="orange dargen-1"
                    item-color="orange dargen-1"
                    no-data-text="Načítám"
                    :menu-props="{ offsetY: true }"
                    @change="changeSelectedShop"
                    width="100px"
                ></v-select>
            </v-col>

            <v-spacer></v-spacer>

            <v-col cols="3" align="center">
                <div>
                    doprava
                    <span v-if="selectedProduct.min_delivery_price > 0">{{ selectedProduct.min_delivery_price }} Kč</span>
                    <span v-if="selectedProduct.min_delivery_price === 0">zdarma</span>
                </div>
                <a :href="selectedProduct.url" target="_blank">
                    <v-btn outlined color="orange darken-1"  class="ma-2">
                        Do Obchodu
                    </v-btn>
                </a>
            </v-col>

        </v-row>
    </v-card>
</template>

<script>
    export default {
        name: "CartProduct",
        data() {
            return {
                shopOptions: [],
                shopOptionsLoading: false,
                selectedShop: this.product.shop_name,
                selectedProduct: this.product,
            }
        },
        props: {
            product: {
                type: Object,
                required: true
            }
        },
        methods: {
            async removeItem(productName){
                this.$emit('cartItemRemoved', productName);
            },
            async loadShopOptions(productName){
                this.shopOptionsLoading = true;
                this.$http.defaults.headers.common["sort"] = "price_a";
                const productNameEncoded = encodeURIComponent(encodeURIComponent(productName));
                const response = await this.$http.get(`/api/item/getShopOptions/${productNameEncoded}`);
                response.data.forEach( shopOption => { this.shopOptions.push(
                    {
                        text: shopOption.shop_name + ' - ' + shopOption.price + ' Kč' ,
                        value: shopOption.shop_name,
                        divider: false,
                    }
                )});
                this.shopOptionsLoading = false;
                this.$http.defaults.headers.common["sort"] = null;
            },
            async changeSelectedShop() {
                const productNameEncoded = encodeURIComponent(encodeURIComponent(this.selectedProduct.product_name));
                const shopNameEncoded = encodeURIComponent(encodeURIComponent(this.selectedShop));
                const response = await this.$http.get(`/api/item/getSingleShopOption/${productNameEncoded}/${shopNameEncoded}`);
                const oldPrice = this.selectedProduct.price;
                const oldShopName = this.selectedProduct.shop_name;
                const oldShopPrice = this.selectedProduct.min_delivery_price;
                this.selectedProduct = response.data;
                const newPrice = response.data.price;
                const data = {
                    sumPriceChange: newPrice - oldPrice,
                    oldShop: {
                        name: oldShopName,
                        price: oldShopPrice
                    },
                    newShop: {
                        name: response.data.shop_name,
                        price: response.data.min_delivery_price
                    }
                };
                this.$emit('shopOptionChange', data);
            },
        },
        mounted() {
            this.loadShopOptions(this.product.product_name);
        },
    }
</script>

<style scoped>
    a {
        text-decoration: none;
    }
    .title {
        color: black;
    }
    .deleteButton {
        float: left;
        width: fit-content;
        margin: 5px;
    }
</style>
