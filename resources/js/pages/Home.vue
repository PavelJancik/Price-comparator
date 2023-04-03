<template>
    <v-container>

        <v-row>
            <!-- Všechny kategorie -->
            <v-col>
                <v-card-title class="justify-center">Všechny kategorie</v-card-title>
                <category-menu></category-menu>
            </v-col>
            <!-- Nákupní seznam -->
            <v-col>
                <router-link :to="{name: 'cart'}" class="link">
                    <v-card-title class="justify-center">Nákupní seznam</v-card-title>
                </router-link>
                <v-card class="ma-3" tile>
                    <v-card-subtitle class="justify-center cart pa-2 ml-2" @click="displayCart = !displayCart">
                        <v-row class="pa-0 ma-0">
                            <span v-if="!displayCart" >Zorazit položky</span>
                            <span v-if="displayCart" >Skrýt položky</span>
                            <v-spacer></v-spacer>
                            <v-icon :class="{ 'iconAnimation': displayCart }" class="mr-3">
                                mdi-menu-down
                            </v-icon>
                        </v-row>
                    </v-card-subtitle>
                    <v-expand-transition>
                        <v-card-text v-show="displayCart">
                            <v-divider class="mb-2"></v-divider>
                            <div v-for="product in products" :key="product.name">
                                <router-link :to="{ name: 'productDetails', params: {productName: product.name}}" class="cartProduct">
                                    {{ product.name }}
                                </router-link>
                            </div>
                            <div v-if="products.length == 0">Nákupní seznam je prázdný</div>
                        </v-card-text>
                    </v-expand-transition>
                </v-card>
            </v-col>
        </v-row>


        <!-- Oblíbené kategorie -->
        <v-card-title class="justify-center">Oblíbené kategorie</v-card-title>
        <v-row class="justify-center">
            <category-highlight category="Kytary" categoryImg="Akustické kytary"></category-highlight>
            <category-highlight category="Bicí nástroje" categoryImg="Perkuse"></category-highlight>
            <category-highlight category="Smyčcové nástroje" categoryImg="Housle"></category-highlight>
            <category-highlight category="Klávesové nástroje" categoryImg="Digitální piana"></category-highlight>
            <category-highlight category="Nástrojové aparatury" categoryImg="Aparatury pro kytary"></category-highlight>
            <category-highlight category="Struny" categoryImg="Struny"></category-highlight>
            <category-highlight category="Dechové nástroje" categoryImg="Lesní rohy"></category-highlight>
            <category-highlight category="Zobcové flétny" categoryImg="Zobcové flétny"></category-highlight>
        </v-row>

        <!-- Prohlíželi jste -->
        <div v-if="recentlyViewed.length !== 0">
            <v-card-title class="justify-center" v-if="displayRecent">Prohlíželi jste</v-card-title>
            <v-row class="justify-center">
                <div v-for="product in recentlyViewed" :key="product.name">
                    <product-highlight :specificProduct="product"></product-highlight>
                </div>
            </v-row>
        </div>

        <!-- Tipy na nákup -->
        <v-card-title class="justify-center">Tipy na nákup</v-card-title>
        <v-row class="justify-center">
            <product-highlight></product-highlight>
            <product-highlight></product-highlight>
            <product-highlight></product-highlight>
            <product-highlight></product-highlight>
        </v-row>

    </v-container>
</template>

<script>
    import CategoryMenu from "../components/CategoryMenu";
    import CategoryHighlight from "../components/CategoryHighlight";
    import ProductHighlight from "../components/ProductHighlight";
    import {recentProductsManager} from "../app";

    export default {
        name: "Home",
        components: {
            CategoryMenu,
            CategoryHighlight,
            ProductHighlight,
        },
        data() {
            return {
                displayCart: false,
                displayRecent: false,
                recentlyViewed: []
            }
        },
        props: {
            products: [],
        },
        methods: {
            loadRecentlyViewed() {
                this.recentlyViewed = recentProductsManager.get();
                if (this.recentlyViewed.length != 0) this.displayRecent = true;
            },
        },
        mounted() {
            this.loadRecentlyViewed();
        }
    }
</script>

<style scoped>
    a, .link {
        text-decoration: none;
        color: black !important;
    }
    .cart {
        cursor: pointer;
    }
    .cart:hover, .link:hover {
        color: #FB8C00 !important;
    }
    .cartProduct {
        cursor: pointer;
        color: black !important;
    }
    .cartProduct:hover {
        color: #FB8C00 !important;
    }
    .iconAnimation {
        transform: rotate(180deg);
    }
</style>
