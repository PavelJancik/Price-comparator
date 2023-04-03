<template>
    <v-container>
        <v-row>

            <v-col cols="3" class="pa-0 d-none d-md-flex">
                <div>
                    <category-menu
                        @categoryChange="setCurrentCategory"
                    ></category-menu>
                    <filter-menu
                        @filtersChanged="atFiltersChange"
                        :currentCategory="currentCategory"
                        :key="currentCategory"
                        :productNameFilter="productNameFilter"
                    ></filter-menu>
                </div>
            </v-col>

            <v-col xs="12" sm="12" md="9" lg="9" xl="9" class="pa-0">
                <v-card flat color="transparent">
                    <v-card-text class="text-center pa-0">
                        Potřebujete poradit? Nechte nás Vám pomoci s výběrem.
                        <!-- Potřebujete poradit při výběru toho správného produktu? Nechte nás Vám pomoci.-->
                        <router-link :to="{ name: 'helper', params: {category: currentCategory}}" >
                            <v-btn color="orange darken-1" text>
                                Chci pomoci
                            </v-btn>
                        </router-link>
                    </v-card-text>
                </v-card>

                <v-container class="pb-0">
                    <sort-menu @sortSelected="setSort"></sort-menu>
                </v-container>
                <products-preview
                    v-if="currentCategory"
                    :currentCategory="currentCategory"
                    :page="page"
                    :filters="filters"
                    :key="key"
                    :sort="sort"
                    @productsLoaded="atProductsLoaded"
                    @addToCart="addToCart"
                >
                </products-preview>
            </v-col>

            <v-row class="pa-0">
                <v-col col="12" class="text-center">
                    <v-pagination
                        v-model="page"
                        :length="lastPage"
                        total-visible="7"
                        color="orange darken-1"
                        circle
                    ></v-pagination>
                </v-col>
            </v-row>

            <v-navigation-drawer
                class="drawer d-flex d-md-none"
                v-model="localDrawer"
                fixed
                hide-overlay
            >
                <div>
                    <category-menu
                        @categoryChange="setCurrentCategory"
                    ></category-menu>
                    <filter-menu
                        @filtersChanged="atFiltersChange"
                        :currentCategory="currentCategory"
                        :key="currentCategory"
                    ></filter-menu>
                </div>
            </v-navigation-drawer>

        </v-row>
    </v-container>
</template>

<script>
    import filterMenu from "../components/FilterMenu"
    import productsPreview from "../components/ProductsPreview"
    import CategoryMenu from "../components/CategoryMenu"
    import SortMenu from "../components/SortMenu";
    import {MAX_PRICE} from "../../code/constants";

    export default {
        name: "ProductsList",
        components: {
            filterMenu,
            productsPreview,
            CategoryMenu,
            SortMenu,
        },
        data() {
            return {
                page: 1,
                lastPage: 1,
                filters: null,
                sort: null,
                key: 0, // changes whenever page, filters or currentCategory is modified
                localDrawer: null,
            }
        },
        props: {
            currentCategory: {
                type: String,
                required: true
            },
            drawerProp: null,
            productNameFilter: {
                type: String,
                required: false
            },
            helperFilters: {
                type: Object,
                required: false
            },
        },
        methods: {
            setCurrentPage() {
                this.page = parseInt(this.$route.params.page);
            },
            setCurrentCategory(){
                this.$emit('currentCategoryChange', this.$route.params.category);
                this.page = 1;
            },
            atProductsLoaded(lastPage) {
                this.setLastPage(lastPage);
            },
            setLastPage(lastPage){
                this.lastPage = lastPage;
            },
            addToCart(productName){
                this.$emit('addToCart', productName);
            },
            atFiltersChange(filters){
                this.filters = filters;
                this.page=1;
            },
            setSort(sort){
                this.sort = sort;
                this.key++;
            }
        },
        beforeMount() {
            if (this.helperFilters) this.filters = this.helperFilters;
        },
        mounted() {
            this.setCurrentCategory();
            this.setCurrentPage();
        },
        watch: {
            // updates page number in URL
            page: function(val){
                history.replaceState (
                    {},
                    null,
                    this.$route.params.page = val
                );
                this.key++;
            },
            currentCategory: function(){
                this.filters = null;
                this.key++;
            },
            filters: {
                handler(){
                    this.key++;
                },
                deep: true
            },
            // helperFilters: {
            //     handler(){
            //         this.filters = this.helperFilters;
            //         console.log('filters changed in ProductList watch');
            //     },
            //     deep: true
            // },
            productNameFilter: function(productName){
                this.productNameFilter = productName;
                this.filters = {
                    price: [0, 999999],
                    productName: productName,
                    manufacturers: [],
                    strings: [],
                    colors: [],
                    surface: [],
                    electronics: [],
                    hands: [],
                    types: [],
                    materials: {},
                    size: [],
                }; // todo udelat nejak lepe + pridat productName filter do filterMenu, nebo nejak aby sel ten filter zrusit
                // todo vyresit  Error in nextTick: "TypeError: Cannot read properties of undefined (reading 'focus')" v Loader - Dialog
            },
            drawerProp: function(){
                this.localDrawer = this.drawerProp;
            },
            localDrawer: function() {
                this.$emit('drawerVarChange', this.localDrawer)
            }
        }
    }
</script>

<style scoped>
    .drawer {
        top: 56px !important;
    }
    a {
        text-decoration: none
    }
</style>
