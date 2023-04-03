<template>
    <v-container>

        <v-card class="mx-auto" tile>
            <category-tree v-if="!loading" :label="categoryTree.category.name" :nodes="categoryTree.sub_categories" :depth="0" @categoryChange="$emit('categoryChange')"></category-tree>
        </v-card>

    </v-container>
</template>

<script>
    import CategoryTree from "../components/CategoryTree";

    export default {
        name: "CategoryMenu",
        components: {
            CategoryTree,
        },
        data() {
            return {
                categoryTree: {},
                loading: true
            }
        },
        methods: {
            async loadCategoryTree() {
                try {
                    const response = await this.$http.get('/api/getCategoryTree');
                    this.categoryTree = response.data;
                    this.loading = false;
                } catch (e) {
                    console.log(e);
                    // todo fix 429 code (retry-after)
                    await this.$router.push({name: 'error', params: {code: 429}});

                }
            },
        },
        mounted() {
            this.loadCategoryTree();
        }
    }
</script>

<style scoped>

</style>
