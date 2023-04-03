<template>
    <v-expansion-panels>
        <v-expansion-panel>
            <v-expansion-panel-header>
                Typ
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
        name: "Type",
        data() {
            return {
                currentCategory: '',
                options: [],
                selected: []
            }
        },
        methods: {
            async loadOptions(){
                const categoryEncoded = encodeURIComponent(encodeURIComponent(this.currentCategory));
                const response = await this.$http.get(`/api/getTypes/${categoryEncoded}`);
                this.options = response.data.map( option => option.value);
            },
            emitChanges() {
                this.$emit('typeChange', this.selected);
            }
        },
        mounted() {
            this.currentCategory = this.$route.params.category;
            this.loadOptions();
        }
    }
</script>

<style scoped>

</style>
