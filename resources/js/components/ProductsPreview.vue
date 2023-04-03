<template>
    <v-container>

        <loader :loadingProps="loading" text="načítám produkty"></loader>

        <div v-if="products.length===0 && !loading">Nebyly nalezeny žádné produkty</div>

        <div v-for="product in products" :key="product.name">
            <v-card class="pa-4 mb-3">
                <v-row>

                    <v-col xs="2" sm="2" md="2" lg="2" xl="2">
                        <v-img
                            lazy-src="/images/lazy-img.png"
                            max-height="90"
                            max-width="120"
                            :src="product.img_url"
                            contain
                        ></v-img>
                    </v-col>

                    <v-col xs="5 " sm="5" md="6" lg="7" xl="7">
                        <b> {{ product.name }} </b>
                        <br>
                        <p>{{ product.description | truncate(170) }}</p> <!--clss="text-truncate"-->
                    </v-col>

                    <v-col xs="5" sm="5" md="4" lg="3" xl="3" align="center" >
                        <v-btn icon @click="addToCart(product.name)">
                            <v-icon>mdi-cart</v-icon>
                        </v-btn>
                        <p>{{ product.minPrice }}<span v-if="product.minPrice !== product.maxPrice"> - {{ product.maxPrice }}</span> Kč</p>
                        <router-link :to="{ name: 'productDetails', params: {productName: product.name}}" >
                            <v-btn outlined color="orange darken-1">
                                Porovnat ceny
                            </v-btn>
                        </router-link>
                    </v-col>

                </v-row>
            </v-card>
        </div>

        <v-snackbar
            v-model="snackbar.display"
            color="blue-grey darken-2"
            timeout="3000"
        >
            {{ snackbar.text }}
            <template v-slot:action="{ attrs }">
                <v-btn
                    color="orange darken-1"
                    text
                    v-bind="attrs"
                    @click="snackbar.display = false"
                >
                    Zavřít
                </v-btn>
            </template>
        </v-snackbar>

    </v-container>
</template>

<script>
    import {tokenManager} from "../app"
    import Loader from "./Loader";

    export default {
        name: "ProductsPreview",
        components: {
            Loader
        },
        data() {
            return {
                products: [],
                lastPage: 0,
                loading: true,
                snackbar: {
                    display: false,
                    text: ""
                },
            }
        },
        props: {
            currentCategory: {
                type: String,
                required: true
            },
            page: {
                type: Number,
                required: true
            },
            filters: {
                type: Object,
                required: false,
            },
            sort: {
                type: String,
                required: false
            }
        },
        methods: {
            async loadProducts(){
                try {
                    this.$http.defaults.headers.common["filters"] = null;
                    this.$http.defaults.headers.common["sort"] = null;
                    if(this.filters) this.$http.defaults.headers.common["filters"] = unescape(encodeURIComponent(JSON.stringify(this.filters)));
                    if(this.sort) this.$http.defaults.headers.common["sort"] = this.sort;
                    const categoryEncoded = encodeURIComponent(encodeURIComponent(this.currentCategory));
                    const response = await this.$http.get(`/api/products/${categoryEncoded}?page=${this.page}`);
                    this.products = response.data.data;
                    this.lastPage = response.data.last_page; // Math.ceil(response.data.total / response.data.per_page);
                    this.$emit('productsLoaded', this.lastPage);
                    this.loading = false;
                    if (this.filters) console.log(JSON.stringify(this.filters));
                    if (this.sort) console.log(this.sort);
                } catch (e) {
                    console.log(e);
                }
            },
            addToCart(productName){
                this.$emit('addToCart', productName);
                this.showSnackbar(productName);
            },
            showSnackbar(productName){
                this.snackbar.display = true;
                this.snackbar.text = 'Produkt byl přidán na seznam';
                if (localStorage.getItem(tokenManager.getTokenKey())) {
                    const {items} = tokenManager.getPayload();
                    const filteredCartItems = items.filter( item => item.name.includes(productName));
                    if (filteredCartItems.length>0) this.snackbar.text = 'Tento produkt je již na seznamu';
                }
            },
        },
        mounted() {
            this.loadProducts();
        },
    }
</script>

<style scoped>
    a {
        text-decoration: none;
    }
</style>
