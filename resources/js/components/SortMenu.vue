<template>
    <v-container class="pa-0">
        <v-divider class="ma-0 mb-2"></v-divider>
        <v-card flat color="transparent">
            <v-card-text class="pa-0">
                <span class="mr-4">Seřadit:</span>
                <span v-for="option in sortOptions" :key="option.value">
                    <span @click="selectSort(option)" class="sortOption ma-2" :class="{'selected': option.selected}">
                        {{ option.text }}
                    </span>
                </span>
            </v-card-text>
        </v-card>
        <v-divider class="mt-2"></v-divider>
    </v-container>
</template>

<script>
    export default {
        name: "SortMenu",
        data() {
            return {
                sortOptions: [
                    {
                        text: "Podle názvu",
                        value: "name",
                        selected: false
                    },
                    {
                        text: "Od nejlevnějších",
                        value: "price_a",
                        selected: false
                    },
                    {
                        text: "Od nejdražších",
                        value: "price_d",
                        selected: false
                    },
                ],
                sortSelected: null,
            }
        },
        methods: {
            selectSort(option){
                if (this.sortSelected === option.value) return;
                this.sortSelected = option.value;
                this.sortOptions.forEach( option => {option.selected = false});
                option.selected = true;
                this.$emit('sortSelected', option.value)
            }
        }
    }
</script>

<style scoped>
    .sortOption {
        cursor: pointer;
    }
    .sortOption:hover {
        color: #FB8C00;
    }
    .selected {
        color: #FB8C00;
        font-weight: bold;
    }
</style>
