<template>
    <v-expansion-panels>
        <v-expansion-panel>
            <v-expansion-panel-header>
                Povrchová úprava
            </v-expansion-panel-header>
            <v-expansion-panel-content>

                <div v-for="option in surfaceOptions" :key="option">
                    <v-checkbox
                        :label="option"
                        :value="option"
                        v-model="surfaceSelected"
                        @change="emitSurface"
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
        name: "SurfaceFinish",
        data() {
            return {
                currentCategory: '',
                surfaceOptions: [],
                surfaceSelected: []
            }
        },
        methods: {
            async loadSurfaceOptions(){
                const categoryEncoded = encodeURIComponent(encodeURIComponent(this.currentCategory));
                const response = await this.$http.get(`/api/getSurface/${categoryEncoded}`);
                this.surfaceOptions = response.data.map( surface => surface.value);
            },
            emitSurface() {
                this.$emit('surfaceChange', this.surfaceSelected);
            }
        },
        mounted() {
            this.currentCategory = this.$route.params.category;
            this.loadSurfaceOptions();
        }
    }
</script>

<style scoped>

</style>
