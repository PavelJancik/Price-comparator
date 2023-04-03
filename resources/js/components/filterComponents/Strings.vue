<template>
    <v-expansion-panels>
        <v-expansion-panel>
            <v-expansion-panel-header>
                Počet strun
            </v-expansion-panel-header>
            <v-expansion-panel-content>

                <div v-for="option in stringOptions" :key="option">
                    <v-checkbox
                        :label="option"
                        :value="option"
                        v-model="stringsSelected"
                        @change="emitStrings"
                        color="orange darken-1"
                        dense
                    ></v-checkbox>
                </div>
                <v-checkbox
                    label="ostatní"
                    value="0"
                    v-model="stringsSelected"
                    @change="emitStrings"
                    color="orange darken-1"
                    dense
                ></v-checkbox>

            </v-expansion-panel-content>
        </v-expansion-panel>
    </v-expansion-panels>
</template>

<script>
    export default {
        name: "Strings",
        data() {
            return {
                stringOptions: ["4", "5", "6", "7", "12"],
                stringsSelected: []
            }
        },
        methods: {
            emitStrings() {
                const strings = this.stringsSelected.map((i) => Number(i)); // converts string array values to int values
                this.$emit('stringsChange', strings);
            }
        }
    }
</script>

<style scoped>

</style>
