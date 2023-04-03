<template>
    <v-container>

        <v-card>
            <v-row v-if="shopOptions.length > 0">
                <v-col cols="3">
                    <v-img
                        lazy-src="/images/lazy-img.png"
                        max-height="300"
                        max-width="300"
                        :src="product.img_url"
                        contain
                        class="image ma-3"
                        @click="overlay = !overlay"
                    ></v-img>
                </v-col>

                <v-col cols="8">
                    <v-card-title>
                        {{ product.product_name }}
                    </v-card-title>
                    <v-card-text>
                        {{ product.description }}
                    </v-card-text>
                </v-col>
                <v-col cols="1">
                    <v-btn icon @click="addToCart(shopOptions[0].product_name)" class="cartIcon">
                        <v-icon>mdi-cart</v-icon>
                    </v-btn>
                </v-col>
            </v-row>
        </v-card>

        <v-card>
            <v-tabs
                v-model="tab"
                light
                color="orange darken-1"
            >
                <v-tab>
                    Porovnat ceny
                </v-tab>
                <v-tab>
                    Podrobnosti o výrobku
                </v-tab>
                <v-tab>
                    Recenze
                </v-tab>
                <v-tab>
                    Doporučujeme přikoupit
                </v-tab>
            </v-tabs>

<!--            <v-divider class="pa-0 ma-0"></v-divider>-->

            <v-tabs-items v-model="tab">
                <v-tab-item>
                    <v-card>
                        <v-card-text>
                            <sort-menu @sortSelected="setSort"></sort-menu>
                            <shop-options :shops="shopOptions"></shop-options>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
                <v-tab-item>
                    <v-card>
                        <v-card-text>
                            <span v-for="param in params" :key="param.name + param.value">
                                <b>{{ param.name }}</b> : {{ param.value }} <br>
                            </span>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
                <v-tab-item>
                    <v-card>
                        <v-card-text>
                            <reviews
                                :productName="$route.params.productName"
                            ></reviews>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
                <v-tab-item>
                    <v-card>
                        <v-card-text>
                            <recommended
                                :productName="$route.params.productName"
                                @reloadProductDetails="reloadProductDetails"
                                :key="$route.params.productName">
                            </recommended>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
            </v-tabs-items>
        </v-card>

        <v-overlay :value="overlay">
            <div  class="zoomOutBackground" @click="overlay = false"></div>
            <v-container
                v-if="product"
                class="pa-5 zoomed_image"
                @click="overlay = false"
            >
                    <v-img
                        max-width="90vw"
                        max-height="80vh"
                        :src="product.img_url"
                        contain
                    ></v-img>
            </v-container>
            <v-card light v-else>
                <v-card-title>Obrazek se nepodařilo načíst :(</v-card-title>
                <v-card-text  class="text-center">
                    <v-btn outlined  color="orange darken-1" @click="overlay = false">
                        Zavřít
                    </v-btn>
                </v-card-text>
            </v-card>
        </v-overlay>

    </v-container>
</template>

<script>
    import shopOptions from '../components/ShopOptions';
    import SortMenu from "../components/SortMenu";
    import {recentProductsManager} from "../app";
    import Recommended from "../components/Recommended";
    import Reviews from "../components/Reviews";

    export default {
        name: "ProductDetails",
        components: {
            shopOptions,
            SortMenu,
            Recommended,
            Reviews
        },
        data() {
            return {
                shopOptions: [],
                params: [],
                overlay: false,
                tab: null,
                sort: null,
                product: null,
            }
        },
        props: {
            productDetailsKey: {
                type: Number,
                required: true
            }
        },
        methods: {
            async loadShopOptions() {
                try{
                    if(this.sort) this.$http.defaults.headers.common["sort"] = this.sort;
                    const productNameEncoded = encodeURIComponent(encodeURIComponent(this.$route.params.productName));
                    const response = await this.$http.get(`/api/item/getShopOptions/${productNameEncoded}`);
                    if (response.data !== undefined){
                        this.shopOptions = response.data;
                        this.product = response.data.filter( shopOption => shopOption.shop_name.includes('Muziker'))[0];
                        this.addToRecentProducts();
                    }
                    else this.$router.push({name: 'error', params: {code: 404}});
                } catch (e) {
                    console.log(e);
                }
            },
            async loadParams(){
                try{
                    const productNameEncoded = encodeURIComponent(encodeURIComponent(this.$route.params.productName));
                    const response = await this.$http.get(`/api/getParams/${productNameEncoded}`);
                    this.params = response.data;
                } catch (e) {
                    console.log(e);
                }
            },
            async addToCart(productName){
                this.$emit('addToCart', productName);
            },
            setSort(sort){
                this.sort = sort;
            },
            addToRecentProducts(){
                const product = {
                    name: this.$route.params.productName,
                    img_url: this.product.img_url,
                };
                recentProductsManager.add(product);
            },
            reloadProductDetails(){
                this.$emit('reloadProductDetails', this.$route.params.productName);
            }
        },
        mounted() {
            this.loadShopOptions();
            this.loadParams();
            this.$emit('checkCurrentCategory', this.$route.params.productName);
        },
        watch: {
            productDetailsKey: function() {
                this.loadShopOptions();
                this.loadParams();
                this.$emit('checkCurrentCategory', this.$route.params.productName);
            },
            sort: function(){
                this.loadShopOptions();
            },
            '$route.params.productName': function(){ // need when coming back in browser, from productDeials with diferent paramName
                this.reloadProductDetails();
            },
        }
    }
</script>

<style scoped>
    .image {
        cursor: zoom-in;
    }
    .zoomed_image {
        z-index: 2;
        cursor: zoom-out;
        background: white;
    }
    .cartIcon {
        float: right;
        margin-right: 10px;
    }
    .zoomOutBackground {
        opacity: 0;
        position: fixed;
        z-index: 0;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>
