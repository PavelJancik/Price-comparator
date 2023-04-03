<template>
    <v-container v-if="$route.name !== 'home' && !loading" class="pt-3">

        <router-link :to="{ name: 'home'}" class="item">
            Domů
        </router-link>

        <span v-if="$route.name === 'productsList' || $route.name === 'productDetails'" v-for="category in categories">
            /
            <span>
                <a @click="replaceProductList(category)"
                   class="item"
                   :class="{'current': category === currentCategory && $route.name !== 'productDetails'}">
                    {{ category }}
                </a>
            </span>
        </span>
        <span v-if="$route.name === 'productDetails'">
                / <a class="current">{{ $route.params.productName }}</a>
        </span>

        <span v-if="$route.name === 'cart'">
            /
            <a class="current">
                Nákupní seznam
            </a>
        </span>

        <span v-if="$route.name === 'helper'">
            /
            <a class="current">
                Nákupní poradce
            </a>
        </span>

    </v-container>
</template>

<script>
    export default {
        name: "Breadcrumbs",
        props: {
            currentCategory: {
                type: String,
                required: true,
            }
        },
        data() {
            return {
                categories: [],
                loading: true
            }
        },
        methods: {
            updateCurrentCategory(category){
                this.$emit('currentCategoryChange', category);
            },
            replaceProductList(category){
                if (this.$route.params.category !== category || this.$route.name !== 'productsList'){
                    this.updateCurrentCategory(category);
                    this.$router.replace({name: 'productsList', params: {category: category, page: 1}});
                }
            },
            async loadCategories(){
                try {
                    const categoryEncoded = encodeURIComponent(encodeURIComponent(this.currentCategory));
                    const response = await this.$http.get(`/api/getCategoryPath/${categoryEncoded}`);
                    this.categories = response.data;
                    this.loading = false;
                } catch (e) {
                    console.log(e);
                }
            },
        },
        created() {
            this.loadCategories();
        },

    }
</script>

<style scoped>
    .item {
        text-decoration: none;
        color: black;
    }
    .current {
        text-decoration: none;
        color: #AAAAAA;
    }
</style>
