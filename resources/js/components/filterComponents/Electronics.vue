<template>
    <v-expansion-panels>
        <v-expansion-panel>
            <v-expansion-panel-header>
                Elektronika
            </v-expansion-panel-header>
            <v-expansion-panel-content>

                <div v-for="option in options" :key="option">
                    <v-checkbox
                        :label="option"
                        :value="option"
                        v-model="selected"
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
        name: "Electronics",
        data() {
            return {
                options: ['Aktivní', 'Pasivní'],
                selected: []
            }
        },
        methods: {
            emitChanges() {
                this.$emit('electronicsChange', this.selected);
            }
        },
    }
</script>

<style scoped>

</style>
