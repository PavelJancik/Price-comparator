<template>
    <v-row class="mt-0">
        <v-autocomplete
            v-model="model"
            :items="items"
            :loading="isLoading"
            :search-input.sync="search"
            item-text="name"
            item-value="symbol"
            placeholder="Hledat produkt"
            class="mt-3"
            dense
        >
            <template v-slot:no-data>
                <v-list-item>
                    <v-list-item-title>
                        Zadej název
                        <strong>hledaného produktu</strong>
                    </v-list-item-title>
                </v-list-item>
            </template>
            <template v-slot:item="{ item }">
                <v-list-item-avatar
                    color="orange darken-1"
                    class="text-h5 font-weight-light white--text"
                >
                    {{ item.name.charAt(0) }}
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title v-text="item.name"></v-list-item-title>
                    <v-list-item-subtitle v-text="item.symbol"></v-list-item-subtitle>
                </v-list-item-content>
            </template>
        </v-autocomplete>

        <v-btn icon @click="filterByName">
            <v-icon>mdi-magnify</v-icon>
        </v-btn>
    </v-row>
</template>

<script>
    export default {
        name: "HeaderSearchBar",
        data: () => ({
            isLoading: false,
            items: [],
            model: null,
            search: null,
        }),
        methods: {
            async searchItems(){
                try {
                    this.items = [];
                    const response = await this.$http.get(`/api/getProductsByName/${this.search}`);
                    response.data.forEach(item => this.items.push({name} = item));
                    // todo pridat kategorie a rozdelit to pomoci header, items nacitat jako objekty:
                    // {
                    //     text: string | number | object,
                    //         value: string | number | object,
                    //     disabled: boolean,
                    //     divider: boolean,
                    //     header: string
                    // }
                    this.isLoading = false;
                } catch (e) {
                    console.log(e);
                }
            },
            routeTo(product_name){
                this.$router.push({name: 'productDetails', params: {productName: product_name}});
                this.$emit('reloadProductDetails', product_name);
                this.items = [];
                this.model = null;
            },
            async filterByName(){
                let category = this.$route.params.category;
                if (category === undefined) category = 'Hudební nástroje'; // todo udelat jako konstantu
                if (this.$route.name !== 'productsList') await this.$router.push({name: 'productsList', params: {category: category, page: 1}});
                this.$emit('filterByName', this.search);
            }
        },
        watch: {
            model (product_name) {
                if (product_name != null) {
                    if ((this.$route.name === 'productDetails' && this.$route.params.productName !== product_name) || this.$router.name !== 'productDetails')
                        this.routeTo(product_name);
                }
            },
            search (val) {
                if (this.items.length > 0) return; // if items have already been loaded
                this.isLoading = true;
                this.searchItems();
            },

        },
    }
</script>

<style scoped>

</style>
