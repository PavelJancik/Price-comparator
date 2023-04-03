<template>
    <div>

        <!-- 1st immersion -->

        <v-card-title>Jaké máte zkušenosti s hraním na kytaru?</v-card-title>
        <v-card-text>
            <v-radio-group v-model="experiences">
                <v-radio
                    label="Začátečník"
                    value="beginner"
                    color="orange darken-1"
                ></v-radio>
                <v-radio
                    label="Pokročilý"
                    value="advanced"
                    color="orange darken-1"
                ></v-radio>
            </v-radio-group>
        </v-card-text>


        <!-- 2nd immersion -->

        <v-expand-transition>
            <div v-if="experiences === 'beginner'">
                <v-card-title>Kolik Vám je let?</v-card-title>
                <v-card-text>
                    <v-text-field
                        v-model="age"
                        label="Věk"
                        min="0"
                        max="150"
                        type="number"
                        style="width: 70px"
                        color="orange darken-1"
                    ></v-text-field>
                </v-card-text>
            </div>
        </v-expand-transition>

        <v-expand-transition>
            <div v-if="experiences === 'advanced'" >
                <v-card-title>Jakému stylu se chcete věnovat?</v-card-title>
                <v-card-text>
                    <v-radio-group v-model="genre">
                        <v-radio
                            label="Rock / Metal / Hardcore / Punk"
                            value="rock"
                            color="orange darken-1"
                        ></v-radio>
                        <v-radio
                            label="Folk"
                            value="folk"
                            color="orange darken-1"
                        ></v-radio>
                        <v-radio
                            label="Klasická hudba"
                            value="classic"
                            color="orange darken-1"
                        ></v-radio>
                    </v-radio-group>
                </v-card-text>
            </div>
        </v-expand-transition>

        <!-- 3rd immersion -->

        <v-expand-transition>
            <div v-if="experiences === 'advanced' && genre === 'folk'">
                <v-card-title>Kde si představujete, že budete hrát?</v-card-title>
                <v-card-text>
                    <v-radio-group v-model="place">
                        <v-radio
                            label="Pouze doma, sám pro sebe"
                            value="home"
                            color="orange darken-1"
                        ></v-radio>
                        <v-radio
                            label="V menších prostorech (kavárny, v ulicích)"
                            value="street"
                            color="orange darken-1"
                        ></v-radio>
                        <v-radio
                            label="Na velkých pódiích (festivaly, koncerty)"
                            value="stage"
                            color="orange darken-1"
                        ></v-radio>
                    </v-radio-group>
                </v-card-text>
            </div>
        </v-expand-transition>

    </div>
</template>

<script>
    import {ROOT_CATEGORY} from "../../../code/constants";

    export default {
        name: "GuitarHelper",
        data() {
            return {
                experiences: null,
                age: null,
                genre: null,
                place: null,
                category: ROOT_CATEGORY,
                filters: {
                    price: [0, 999999],
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
            }
        },
        methods: {
            emit(){
                const data = {
                    category: this.category,
                    filters: this.filters
                };
                this.$emit('dataChanged', data);
            },
        },
        updated() {
            this.emit();
        },
        watch: {
            experiences: function (exp) {
                this.age = null;
                this.genre = null;
                this.place = null;
                if (exp === 'beginner') this.category = 'Klasické kytary';
                if (exp === 'advanced') this.category = 'Kytary';
            },
            age: function (age) {
                if (this.experiences === 'beginner'){
                    if (age <= 12) this.filters.size = ['3/4'];
                    else this.filters.size = ['4/4'];
                } else this.filters.size = [];
            },
            genre: function(genre) {
                this.place = null;
                if (this.experiences === 'advanced') {
                    if (genre === 'rock') this.category = 'Elektrické kytary';
                    if (genre === 'classic') this.category = "Klasické kytary";
                    if (genre === 'folk') this.category = "Kytary";
                }
                else this.category = ROOT_CATEGORY;
            },
            place: function (place) {
                if (this.experiences === 'advanced' && this.genre === 'folk') {
                    if (place === 'home') this.category = "Akustické kytary";
                    if (place === 'street') this.category = "Akustické kytary";
                    if (place === 'stage') this.category = "Elektroakustické kytary";
                }
            }
        }
    }
</script>

<style scoped>

</style>
