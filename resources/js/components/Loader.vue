<template>
    <v-dialog v-model="loadingData" hide-overlay persistent width="300">
        <v-card color="orange darken-1" dark>
            <v-card-text>
                Čekejte prosím<span v-if="text">, {{ text }}</span>
                <v-progress-linear
                    indeterminate
                    color="white"
                    class="mb-0"
                ></v-progress-linear>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "Loader",
        props: {
            loadingProps: {
                type: Boolean,
                required: false
            },
            text: {
                type: String,
                required: false
            }
        },
        data() {
            return {
                loadingData: true
            }
        },
        watch: {
            loadingProps: function (val) {
                this.loadingData = val;
            }
        }
    }
</script>

<style scoped>

</style>
