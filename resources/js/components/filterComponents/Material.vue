<template>
    <v-expansion-panels>

        <v-expansion-panel>
            <v-expansion-panel-header>
                Materiál krku
            </v-expansion-panel-header>
            <v-expansion-panel-content>
                <div v-for="option in neckOptions" :key="option">
                    <v-checkbox
                        :label="option"
                        :value="option"
                        v-model="materials.neck"
                        @change="emitChanges"
                        color="orange darken-1"
                        dense
                    ></v-checkbox>
                </div>
            </v-expansion-panel-content>
        </v-expansion-panel>

        <v-expansion-panel>
            <v-expansion-panel-header>
                Materiál těla
            </v-expansion-panel-header>
            <v-expansion-panel-content>
                <div v-for="option in bodyOptions" :key="option">
                    <v-checkbox
                        :label="option"
                        :value="option"
                        v-model="materials.body"
                        @change="emitChanges"
                        color="orange darken-1"
                        dense
                    ></v-checkbox>
                </div>
            </v-expansion-panel-content>
        </v-expansion-panel>

        <v-expansion-panel>
            <v-expansion-panel-header>
                Materiál hmatníku
            </v-expansion-panel-header>
            <v-expansion-panel-content>
                <div v-for="option in fingerboardOptions" :key="option">
                    <v-checkbox
                        :label="option"
                        :value="option"
                        v-model="materials.fingerboard"
                        @change="emitChanges"
                        color="orange darken-1"
                        dense
                    ></v-checkbox>
                </div>
            </v-expansion-panel-content>
        </v-expansion-panel>

    </v-expansion-panels>
</template>

<script>
    export default {
        name: "Material",
        data() {
            return {
                currentCategory: '',
                neckOptions: [],
                bodyOptions: [],
                fingerboardOptions: [],
                materials: {
                    neck: [],
                    body: [],
                    fingerboard: [],
                }
            }
        },
        methods: {
            // async loadOptions(guitarPart){
            //     const response = await this.$http.get(`/api/getMaterials/${guitarPart}/${this.currentCategory}`);
            //     return response.data.map( option => option.value);
            // },
            async loadNeckOptions(){
                const categoryEncoded = encodeURIComponent(encodeURIComponent(this.currentCategory));
                const response = await this.$http.get(`/api/getMaterials/Materiál krku/${categoryEncoded}`);
                this.neckOptions = response.data.map( option => option.value);
            },
            async loadBodyOptions(){
                const categoryEncoded = encodeURIComponent(encodeURIComponent(this.currentCategory));
                const response = await this.$http.get(`/api/getMaterials/Materiál těla/${categoryEncoded}`);
                this.bodyOptions = response.data.map( option => option.value);
            },
            async loadfingerboardOptions(){
                const categoryEncoded = encodeURIComponent(encodeURIComponent(this.currentCategory));
                const response = await this.$http.get(`/api/getMaterials/Materiál hmatníku/${categoryEncoded}`);
                this.fingerboardOptions =  response.data.map( option => option.value);
            },
            emitChanges() {
                this.$emit('materialChange', this.materials);
            }
        },
        mounted() {
            this.currentCategory = this.$route.params.category;
            this.loadNeckOptions();
            this.loadBodyOptions();
            this.loadfingerboardOptions();
            // this.neckOptions = this.loadOptions('Materiál krku');
            // this.bodyOptions = this.loadOptions('Materiál těla');
            // this.fingerboardOptions = this.loadOptions('Materiál hmatníku');
        }
    }
</script>

<style scoped>

</style>
