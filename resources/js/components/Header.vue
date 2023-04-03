<template>
    <div>
        <v-app-bar
            app
            dark
            :shrink-on-scroll="windowWidth > 960"
            :prominent="windowWidth > 960"
            fade-img-on-scroll
            scroll-threshold="100"
            color="blue-grey darken-1"
            src="/images/app-bar.jpg"
        >

            <template v-slot:img="{ props }">
                <v-img
                    v-bind="props"
                    gradient="to top right, rgba(38, 50, 56, 0.75), rgba(84, 110, 122, 0.90)"
                ></v-img>
            </template>

            <v-app-bar-nav-icon
                class="d-flex d-md-none"
                @click="$emit('menuClicked')"
            ></v-app-bar-nav-icon>

            <v-app-bar-title >
                <span @click="sendMeHome" class="title">
                    HrajLevně.cz &nbsp&nbsp&nbsp&nbsp  <!-- &nbsp couse class="text-no-wrap" does not work; truncated title otherwise -->
                </span>
            </v-app-bar-title>

            <v-spacer></v-spacer>

            <header-search-bar
                @reloadProductDetails="reloadProductDetails"
                @filterByName="filterByName">
            </header-search-bar>

            <v-spacer></v-spacer>

            <router-link :to="{name: 'cart'}" class="link">
                <v-btn icon>
                    <v-icon>mdi-cart</v-icon>
                    <span class="cartItemsCount" v-if="cartItems">{{ cartItems.length }}</span>
                </v-btn>

            </router-link>

        </v-app-bar>
    </div>
</template>



<script>
    import HeaderSearchBar from "./HeaderSearchBar";

    export default {
        name: "Header",
        components: {
            HeaderSearchBar
        },
        data() {
            return {
                windowWidth: window.innerWidth,
            }
        },
        props: {
            cartItems: []
        },
        methods: {
            sendMeHome() {
                if (this.$route.name !== 'home') this.$router.push({name: 'home'});
            },
            onResize() {
                this.windowWidth = window.innerWidth;
            },
            reloadProductDetails(productName){
                this.$emit('reloadProductDetails', productName);
            },
            filterByName(productName){
                this.$emit('filterByName', productName);
            }
        },
        mounted() {
            this.$nextTick(() => {
                window.addEventListener('resize', this.onResize);
            })
        },
        beforeDestroy() {
            window.removeEventListener('resize', this.onResize);
        },
    }
</script>

<style scoped>
    .title {
        cursor: pointer;
    }
    .link {
        text-decoration: none;
    }
    .cartItemsCount {
        color: #FB8C00;
        font-weight: bold;
        font-size: 18px !important;
    }
</style>
