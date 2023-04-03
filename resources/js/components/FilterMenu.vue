<template>
    <v-container>
        <v-card>

            <v-card-title>Cena</v-card-title>
            <v-card-text>
                <span v-if="vModelPrice[0] > 0 || vModelPrice[1] < maxPrice">
                    <span v-if="vModelPrice[0] != 0 ">od {{ vModelPrice[0] }}</span>
                    <span v-if="vModelPrice[1] < maxPrice">do {{ vModelPrice[1] }}</span>
                    Kč
                </span>
                <span v-else>Cenová hranice není omezena</span>
                <v-range-slider
                    v-model.lazy="vModelPrice"
                    @change="setPrice"
                    class="align-center mt-3"
                    color="orange darken-1"
                    track-color="grey"
                    min="0"
                    :max="maxPrice"
                    step="100"
                    hide-details
                >
                </v-range-slider>
            </v-card-text>
            <v-divider></v-divider>

            <v-card-title>Výrobce</v-card-title>
            <v-card-text>
                <v-autocomplete
                v-model="filters.manufacturers"
                :items="manufacturersOptions"
                :loading="manufacturersLoading"
                @input="reloadProducts"
                dense
                label="Hledat"
                multiple
                solo
                deletable-chips
                item-color="orange darken-1"
                color="orange darken-1"
                ></v-autocomplete>
            </v-card-text>
            <v-divider></v-divider>


            <v-card-title>Ostatní</v-card-title>
            <v-card-text>
                <guitars v-if="currentCategoryIn(categoryArrays.guitars)"
                         @stringsChange="setStrings"
                         @colorChange="setColors"
                         @surfaceChange="setSurface"
                         @electronicsChange="setElectronics"
                         @handChange="setHands"
                         @typeChange="setTypes"
                         @materialChange="setMaterials"
                ></guitars>
                <strings v-if="currentCategoryIn(categoryArrays.strings)"
                         @stringsChange="setStrings"
                ></strings>
                <electronics
                    v-if="currentCategory === 'Baskytary'"
                    @electronicsChange="setElectronics"
                ></electronics>
                <size v-if="currentCategoryIn(categoryArrays.size)"
                      @sizeChange="setSize"
                ></size>
            </v-card-text>




        </v-card>
    </v-container>
</template>

<script>
    import {MAX_PRICE} from "../../code/constants";
    import Guitars from "./filterComponents/Guitars";
    import Strings from "./filterComponents/Strings";
    import Electronics from "./filterComponents/Electronics";
    import Size from "./filterComponents/Size";

    export default {
        name: "FilterMenu",
        components: {
            Guitars,
            Strings,
            Electronics,
            Size
        },
        data () {
            return {
                maxPrice: MAX_PRICE,
                manufacturersOptions: [],
                manufacturersLoading: true,
                vModelPrice: [0, MAX_PRICE],
                filters: {
                    price: [0, MAX_PRICE],
                    productName: "",
                    manufacturers: [],
                    strings: [],
                    colors: [],
                    surface: [],
                    electronics: [],
                    hands: [],
                    types: [],
                    materials: {},
                    size: [],
                },
                categoryArrays: {
                    guitars: ['Kytary', 'Elektrické kytary', 'Akustické kytary', 'Klasické kytary', 'Ukulele', 'Baskytary', 'Elektroakustické kytary'],
                    strings: ['Struny'],
                    size: ['Klasické kytary'],
                },
                showAdditionalFilters: false,
            }
        },
        props: {
            currentCategory: {
                type: String,
                required: true
            },
            productNameFilter: {
                type: String,
                required: false
            },
        },
        methods: {
            async getManufacturers(){
                try {
                    const categoryEncoded = encodeURIComponent(encodeURIComponent(this.currentCategory));
                    const response = await this.$http.get(`/api/getManufacturers/${categoryEncoded}`);
                    for (let i=0; i<response.data.length; i++){
                        this.manufacturersOptions.push(response.data[i].name);
                    }
                    this.manufacturersLoading = false;
                } catch(e) {
                    console.log(e);
                }
            },
            mouseleave(){
                console.log('mouseleave');
            },
            mouseup(){
                console.log('mouseup');
            },
            change(){
                console.log('change');
            },
            reloadProducts(){
                let emitedFilters = this.filters;
                if (this.filters.price[1]===MAX_PRICE) emitedFilters.price[1]=999000;
                this.$emit('filtersChanged', emitedFilters);
            },
            showAdditionalFiltersFunction() { // todo doladit zobrazeni filtru "Ostatni"
                for (let i=0; i<this.categoryArrays.length; i++)
                    if ((this.categoryArrays[i].filter( category => category.includes(this.currentCategory))).length > 0)
                        this.showAdditionalFilters = true;
            },
            currentCategoryIn(array){
                return (array.filter( category => category.includes(this.currentCategory))).length > 0
            },
            setPrice(){
                this.filters.price = this.vModelPrice;
                this.reloadProducts();
            },
            setName(productName){
                this.filters.name = productName;
                this.reloadProducts();
            },
            setStrings(arrayValues){
                this.filters.strings = arrayValues;
                this.reloadProducts();
            },
            setColors(arrayValues){
                this.filters.colors = arrayValues;
                this.reloadProducts();
            },
            setSurface(arrayValues){
                this.filters.surface = arrayValues;
                this.reloadProducts();
            },
            setElectronics(arrayValues){
                this.filters.electronics = arrayValues;
                this.reloadProducts();
            },
            setHands(arrayValues){
                this.filters.hands = arrayValues;
                this.reloadProducts();
            },
            setTypes(arrayValues){
                this.filters.types = arrayValues;
                this.reloadProducts();
            },
            setMaterials(materials){
                this.filters.materials = materials;
                this.reloadProducts();
            },
            setSize(size){
                this.filters.size = size;
                this.reloadProducts();
            }

        },
        mounted() {
            this.getManufacturers();
        },
        updated(){
            // console.log('filterMenu updated');
        },
        watch: {
            productNameFilter: function(productName){
                this.filters.productName = productName;
            }
        }
        // watch: {
        //         nahrada za opakovani v set funkcich // todo provola se milionkrat pri zmene ceny
        //             filters: {
        //                 handler(val){
        //                     this.reloadProducts();
        //                 },
        //                 deep: true
        //             }
        // }
    }
</script>

<style scoped>

</style>
