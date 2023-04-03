<template>
    <v-row>
        <v-progress-linear
            v-if="loading"
            indeterminate
            color="orange darken-1"
            class="ma-4"
        ></v-progress-linear>

        <span v-for="item in recommendedItems" :key="item.name">
            <product-highlight
                :specificProduct="item"
                @reloadProductDetails="$emit('reloadProductDetails')">
            </product-highlight>
        </span>

    </v-row>
</template>

<script>
    import ProductHighlight from "./ProductHighlight";
    import {
        BASS_AMP_LIKE, BASS_STRINGS_LIKE,
        GUITAR_A_AMP_LIKE, GUITAR_A_STRINGS_LIKE,
        GUITAR_E_AMP_LIKE,
        GUITAR_E_STRINGS_LIKE,
        GUITAR_PICK_LIKE,
        GUITAR_STRAP_LIKE,
        GUITAR_E_CASE_LIKE,
        GUITAR_A_CASE_LIKE,
        BAASS_CASE_LIKE,
        GUITAR_STAND_LIKE,
        GUITAR_TUNER_LIKE,
    } from "../../code/constants";

    export default {
        name: "Recommended",
        components: {
            ProductHighlight
        },
        data() {
            return {
                categories: null,
                recommendedItems: [],
                loading: true,
            }
        },
        props: {
            productName: {
                type: String,
                required: true
            }
        },
        methods: {
            async loadCategories() {
                const encodedProductName = encodeURIComponent(encodeURIComponent(this.productName));
                const deepestCategoryResponse = await this.$http.get(`/api/getDeepestProductCategory/${encodedProductName}`);
                const deepestCategoryEncoded = encodeURIComponent(encodeURIComponent(deepestCategoryResponse.data));
                const categoryPathResponse = await this.$http.get(`/api/getCategoryPath/${deepestCategoryEncoded}`);
                this.categories = categoryPathResponse.data;
            },
            loadRecommendedItems(){
                if (this.isInCategoryPath('Kytary')) this.addItems([GUITAR_STRAP_LIKE, GUITAR_PICK_LIKE, GUITAR_STAND_LIKE, GUITAR_TUNER_LIKE]);
                if (this.isInCategoryPath('Elektrické kytary')) this.addItems([GUITAR_E_STRINGS_LIKE, GUITAR_E_AMP_LIKE, GUITAR_E_CASE_LIKE]);
                if (this.isInCategoryPath('Akustické kytary')) this.addItems([GUITAR_A_STRINGS_LIKE, GUITAR_A_CASE_LIKE]);
                if (this.isInCategoryPath('Elektroakustické kytary')) this.addItems([GUITAR_A_AMP_LIKE, GUITAR_A_STRINGS_LIKE]);
                if (this.isInCategoryPath('Baskytary')) this.addItems([BASS_AMP_LIKE, BASS_STRINGS_LIKE, BASS_CASE_LIKE]);
            },
            isInCategoryPath(p_category){
                return (this.categories.filter( category => category.includes(p_category))).length > 0;
            },
            addItems(array){
                array.forEach( nameLike => { this.addItem(nameLike)});
            },
            async addItem(nameLike){
                const encodedNameLike = encodeURIComponent(encodeURIComponent(nameLike));
                const response = await this.$http.get(`/api/getRandomProduct/${encodedNameLike}`);
                this.recommendedItems.push(response.data);
            },
        },
        async mounted() {
            await this.loadCategories();
            await this.loadRecommendedItems();
            this.loading = false;
        }
    }
</script>

<style scoped>

</style>
