<template>
    <v-container>

        <loader :loadingProps="loading" text="načítám košík"></loader>

        <div v-for="product in recommendation">
            <cart-product
                :product="product"
                @cartItemRemoved="removeItem"
                @shopOptionChange="sumPriceChange"
                :key="cartProductKey"
            >
            </cart-product>
        </div>



        <v-dialog v-model="deleteAllDialog" max-width="290" v-if="!loading && recommendation">
            <template v-slot:activator="{ on, attrs }">
                <v-card elevation="4">
                    <v-row class="pa-3 align-center ma-3">
                        <v-btn text color="red accent-4" v-bind="attrs" v-on="on" class="ml-4">
                            <v-icon left>mdi-delete-forever-outline</v-icon> Odstranit vše
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn text color="orange darken-1" @click="atSolveClicked">
                            Najít nejlevnější řešení
                        </v-btn>
                        <v-spacer></v-spacer>
                        <div class="mr-4">
                            <b>Celkem: {{ sumPrice }} Kč</b>
                        </div>
                        (+{{ sumDeliveryPrice }} Kč doprava)
                    </v-row>
                </v-card>
            </template>

            <v-card>
                <v-card-title class="text-h5" elevation="4">
                    Smazat seznam?
                </v-card-title>
                <v-card-text>Vážně si přejete smazat všechny položky uložené v seznamu?</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="orange darken-1" text @click="deleteAllDialog = false">
                        Zpět
                    </v-btn>
                    <v-btn color="orange darken-1" text @click="deleteCart">
                        Vymazat
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-container v-if="!recommendation" fill-height justify-center>
            <v-card max-width="400">
                <v-card-text >
                    Nákupní seznam je prázdný
                </v-card-text>
            </v-card>
        </v-container>


    </v-container>
</template>

<script>
    import {tokenManager} from "../app"
    import Loader from "../components/Loader";
    import CartProduct from "../components/CartProduct";

    export default {
        name: "Cart",
        components: {
            Loader,
            CartProduct
        },
        data() {
            return {
                recommendation: [],
                sumPrice: null,
                sumPriceOptimal: null,
                currentShops: [],
                sumDeliveryPrice: null,
                deleteAllDialog: false,
                loading: true,
                cartProductKey: 0,
            }
        },
        props: {
            products: [],
        },
        methods: {
            async removeItem(productName){ // todo delete zpusobi rerender Carty se špatnýma produktama, ale z backendu se propsa 'product' vrací správně
                const productNameEncoded = encodeURIComponent(encodeURIComponent(productName));
                const response = await this.$http.delete(`/api/cart/removeItem/${productNameEncoded}`);
                const {token} = response.data;
                this.$emit('cartItemRemoved', token);
            },
            deleteCart(){
                this.$emit('cartDeleted');
                this.deleteAllDialog = false;
            },
            async solve(){
                if (localStorage.getItem(tokenManager.getTokenKey())) tokenManager.setToken(localStorage.getItem(tokenManager.getTokenKey()));
                const response = await this.$http.get(`/api/solve`);
                this.recommendation = response.data;
                this.sumPrice = 0;
                this.currentShops = [];
                for (const product of response.data){
                    this.sumPrice += product.price;
                    this.increaseShopOccurrence(product.shop_name, product.min_delivery_price);
                }
                this.sumPriceOptimal = this.sumPrice;
                this.countDeliveryPrice();
                this.loading = false;
            },
            sumPriceChange(data){
                this.sumPrice += data.sumPriceChange;
                this.decreaseShopOccurrence(data.oldShop.name);
                this.increaseShopOccurrence(data.newShop.name, data.newShop.price);
                this.countDeliveryPrice();
            },
            atSolveClicked() {
                this.sumPrice = this.sumPriceOptimal;
                this.cartProductKey++;
                // todo vyresit to bez toho aby se znovu nacitaly shopOptions v CartProduct
            },
            increaseShopOccurrence(shopName, shopPrice){
                if (this.currentShops.filter( shop => shop.name.includes(shopName)).length === 0) // if the shop is not in array yet
                    this.currentShops.push({
                        name: shopName,
                        price: shopPrice,
                        occurrence: 1
                    });
                else {
                    const i = this.currentShops.findIndex( shop => shop.name === shopName);
                    this.currentShops[i].occurrence += 1;
                    if (this.currentShops[i].price > shopPrice) // if it is there, but with higher delivery price)
                        this.currentShops[i].price = shopPrice;
                }
            },
            decreaseShopOccurrence (shopName) { // todo problem kdyz decreasnu ten produkt kterej mel nejmensi cenu
                const i = this.currentShops.findIndex( shop => shop.name === shopName);
                this.currentShops[i].occurrence -= 1;
                if (this.currentShops[i].occurrence === 0) this.currentShops.splice(i, 1);
            },
            // todo jeste to nefunguje stoprocentne
            countDeliveryPrice() {
                let newPrice = 0;
                this.currentShops.forEach( shop => newPrice += shop.price);
                this.currentShops.forEach( shop => console.log(shop.name + ' se vyskytuje ' + shop.occurrence + 'x, min cena: ' + shop.price));
                this.sumDeliveryPrice = newPrice;
            },
        },
        mounted() {
            this.solve();
        },
        watch: {
            products: function () {
                this.solve();
            },
        }
    }
</script>

<style scoped>

</style>
