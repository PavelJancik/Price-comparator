<template>
    <v-expand-transition>
        <v-card flat>
            <v-card-text class="pa-0">

                <div @click="swapDisplay" class="tile" :class="{ 'selectedTile': display && nodes.length>0, 'expandableTile': nodes.length>0}">
                    <v-row class="ma-0 pa-0">
                        <div @click="pushRoute(label)" class="pa-2 label link" :style="indent">
                            <span v-if="depth===0"> Kategorie </span>
                            <span v-else> {{ label | truncate(20) }} </span>
                        </div>
                        <v-spacer></v-spacer>
                        <v-icon v-if="nodes.length>0" :class="{ 'iconAnimation': display }" class="mr-3">
                            mdi-menu-down
                        </v-icon>
                    </v-row>
                </div>

                <category-tree
                    v-show="display"
                    v-for="node in nodes"
                    :nodes="node.sub_categories"
                    :label="node.category.name"
                    :depth="depth + 1"
                    :key="node.category.category_id"
                    class="pa-0"
                    @categoryChange="$emit('categoryChange')"
                >
                </category-tree>

            </v-card-text>
        </v-card>
    </v-expand-transition>
</template>

<script>
    export default {
        name: "CategoryTree",
        data(){
            return {
                display: false,
            }
        },
        props: {
            label: {
                type: String,
                required: true
            },
            nodes: {
                type: Array,
                required: true
            },
            depth: {
                type: Number,
                required: true
            },
        },
        methods: {
            pushRoute(category){
                if (this.$route.params.category !== category || this.$route.name !== 'productsList'){
                    this.$router.replace({name: 'productsList', params: {category: category, page: 1}});
                    this.$emit('categoryChange');
                }
            },
            swapDisplay(){
                this.display = !this.display;
            },
        },
        computed: {
            indent() {
                return 'text-indent: ' + (this.depth * 20) + 'px;';
            },
            tileColor(){
                if (this.display) return 'background-color: rgb(240,240,240);';
                else return 'background-color: rgba(240,240,240,0);'
            }
        },
        mounted() {
            if (this.$route.params.category === this.label) this.currentCategory = true;
        }
    }
</script>


<style scoped>
    .tile:hover {
        background-color: rgb(248,248,248);
    }
    .selectedTile {
        background-color: rgb(240,240,240);
    }
    .expandableTile {
        cursor: pointer;
    }
    .label {
        cursor: pointer;
    }
    .label:hover {
        color: #FB8C00;
    }
    .link {
        width: fit-content;
    }
    .iconAnimation {
        transform: rotate(180deg);
    }
</style>
