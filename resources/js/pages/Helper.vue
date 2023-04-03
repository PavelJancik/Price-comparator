<template>
    <v-container min-height="100vh">

        <v-divider class="mt-0 mb-8"></v-divider>

        <v-card max-width="600" class="mx-auto" v-if="$route.params.category !== 'Kytary'">
            <v-card-title>
                Pro tuto kategorii není rádce dostupný :(
            </v-card-title>
            <v-card-subtitle>
                Na zprovoznění rádce i pro tuto kategorii usilovně pracujeme.
            </v-card-subtitle>
        </v-card>
        <v-card max-width="600" class="mx-auto" v-else>

            <guitar-helper @dataChanged="atDataChanged" v-if="$route.params.category === 'Kytary'"></guitar-helper>

            <v-card-actions class="justify-center">
                <v-btn outlined
                       @click="send"
                       color="orange darken-1"
                       :disabled="disabled"
                       class="ma-4"
                >
                    Najít vhodné produkty
                </v-btn>
            </v-card-actions>
        </v-card>


    </v-container>
</template>

<script>
    import GuitarHelper from "../components/helperComponents/GuitarHelper";

    export default {
        name: "Helper",
        components: {
            GuitarHelper
        },
        data() {
            return {
                category: this.$route.params.category,
                filters: null,
                disabled: false,
            }
        },
        props: {

        },
        methods: {
            send(){
                // console.log(this.category);
                // console.log(this.filters);
                if (this.filters !== null) this.$emit('filtersChanged', this.filters);
                if (this.filters !== undefined) this.$emit('currentCategoryChange', this.category);
                this.$router.push({name: 'productsList', params: {category: this.category, page: 1}});
            },
            atDataChanged(data) {
                this.category = data.category;
                this.filters = data.filters;
            }
        },
        mounted() {

        }
    }
</script>

<style scoped>

</style>
