<template>
    <v-expansion-panels>
        <v-expansion-panel>
            <v-expansion-panel-header>
                Barva
            </v-expansion-panel-header>
            <v-expansion-panel-content>

                <div v-for="option in colorOptions" :key="option">
                    <v-checkbox
                        :label="option"
                        :value="option"
                        v-model="colorsSelected"
                        @change="emitColors"
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
        name: "Color",
        data() {
            return {
                colorOptions: [],
                colorsSelected: []
            }
        },
        methods: {
            async loadColorOptions(){
                const response = await this.$http.get('/api/getColors');
                this.colorOptions = response.data.map( color => color.value);
            },
            emitColors() {
                this.$emit('colorChange', this.colorsSelected);
            }
        },
        mounted() {
            this.loadColorOptions();
        }
    }
</script>

<style scoped>

</style>
