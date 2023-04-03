<template>
    <v-expansion-panels>
        <v-expansion-panel>
            <v-expansion-panel-header>
                Preference ruky
            </v-expansion-panel-header>
            <v-expansion-panel-content>

                <v-checkbox
                    label="Pravá ruka"
                    value="Pravá ruka"
                    v-model="handSelected"
                    @change="emitSurface"
                    color="orange darken-1"
                    dense
                ></v-checkbox>
                <v-checkbox
                    label="Levá ruka"
                    value="Levá ruka"
                    v-model="handSelected"
                    @change="emitSurface"
                    color="orange darken-1"
                    dense
                ></v-checkbox>

            </v-expansion-panel-content>
        </v-expansion-panel>
    </v-expansion-panels>
</template>

<script>
    export default {
        name: "Hand",
        data() {
            return {
                handSelected: []
            }
        },
        methods: {
            emitSurface() {
                this.$emit('handChange', this.handSelected);
            }
        },
    }
</script>

<style scoped>

</style>
