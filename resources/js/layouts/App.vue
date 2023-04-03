<template>
    <v-app id="main">
        <v-main  class="grey lighten-5">

            <my-header
                :cart-items="cartItems"
                @menuClicked="drawer = !drawer"
                @reloadProductDetails="reloadProductDetails"
                @filterByName="filterByName"
            ></my-header>

            <breadcrumbs
                :current-category="currentCategory"
                :key="currentCategory"
                @currentCategoryChange="setCurrentCategory">
            </breadcrumbs>

            <router-view
                :current-category="currentCategory"
                @currentCategoryChange="setCurrentCategory"
                @addToCart="addToCart"
                :products="cartItems"
                :drawerProp="drawer"
                :productDetailsKey="productDetailsKey"
                :productNameFilter="productNameFilter"
                :helperFilters="helperFilters"
                @drawerVarChange="atDrawerVarChange"
                @cartItemRemoved="onCartItemRemoved"
                @cartDeleted="unsetToken"
                @checkCurrentCategory="checkCurrentCategory"
                @reloadProductDetails="reloadProductDetails"
                @filtersChanged="setFilters"
            >
            </router-view>

            <my-footer></my-footer>

        </v-main>
    </v-app>
</template>

<script>
    import myHeader from "../components/Header"
    import breadcrumbs from "../components/Breadcrumbs"
    import {tokenManager} from "../app"
    import myFooter from "../components/Footer"

    export default {
        nam: 'App',
        components: {
            myHeader,
            breadcrumbs,
            myFooter
        },
        data() {
            return {
                currentCategory: "",
                cartItems: [],
                drawer: null,
                productDetailsKey: 0,
                productNameFilter: "",
                helperFilters: null,
            }
        },
        methods: {
            setCurrentCategory(category) {
                this.currentCategory = category;
            },
            async addToCart(productName){
                try {
                    if (localStorage.getItem(tokenManager.getTokenKey()) === null) {
                        await this.createNewCart();
                    }
                    const productNameEncoded = encodeURIComponent(encodeURIComponent(productName));
                    const response = await this.$http.post(`/api/cart/addItem/${productNameEncoded}`);
                    const {token} = response.data;
                    tokenManager.setToken(token);
                    this.setCartItems();
                } catch(e) {
                    console.log(e);
                }
            },
            async createNewCart(){
                try {
                    const response = await this.$http.post(`/api/cart/new`);
                    const {token} = response.data;
                    tokenManager.setToken(token);
                } catch (e) {
                    console.log(e);
                }
            },
            onCartItemRemoved(newToken){
                tokenManager.setToken(newToken);
                this.setCartItems();
            },
            setCartItems(){
                const {items} = tokenManager.getPayload();
                this.cartItems = items;
            },
            unsetToken(){
                tokenManager.unsetToken();
                this.cartItems = [];
            },
            atDrawerVarChange(value) {
                this.drawer = value;
            },
            checkCurrentCategory(productName){
                if (this.currentCategory === "") {
                    this.setCurrentCategoryByProductName(productName)
                }
            },
            async setCurrentCategoryByProductName(productName){
                const productNameEncoded = encodeURIComponent(encodeURIComponent(productName));
                const response = await this.$http.get(`/api/getDeepestProductCategory/${productNameEncoded}`);
                this.currentCategory = response.data;
            },
            async reloadProductDetails(productName) {
                this.productDetailsKey++;
                this.setCurrentCategoryByProductName(productName);
            },
            filterByName(productName){
                this.productNameFilter = productName;
            },
            setFilters(filters) {
                this.helperFilters = filters;
            }
        },
        mounted() {
            if (localStorage.getItem(tokenManager.getTokenKey())){
                tokenManager.setToken(localStorage.getItem(tokenManager.getTokenKey()));
                this.setCartItems();
            }
        },
    };
</script>
