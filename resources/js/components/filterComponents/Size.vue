<template>
    <v-expansion-panels>
        <v-expansion-panel>
            <v-expansion-panel-header>
                Velikost
            </v-expansion-panel-header>
            <v-expansion-panel-content>


                <v-checkbox
                    label="3/4"
                    value="3/4"
                    v-model="selected"
                    @change="emitChanges"
                    color="orange darken-1"
                    dense
                ></v-checkbox>

                <v-checkbox
                    label="4/4"
                    value="4/4"
                    v-model="selected"
                    @change="emitChanges"
                    color="orange darken-1"
                    dense
                ></v-checkbox>

            </v-expansion-panel-content>
        </v-expansion-panel>
    </v-expansion-panels>
</template>

<script>
    export default {
        name: "Size",
        data() {
            return{
                selected: [],
            }
        },
        methods: {
            emitChanges() {
                this.$emit('sizeChange', this.selected);
            }
        }
    }
</script>

<style scoped>

</style>
