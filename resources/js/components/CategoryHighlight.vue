<template>
    <router-link :to="{ name: 'productsList', params: {category: category, page: 1}}" class="ma-4 link">
        <v-card class="pa-4 tile" width="250">
            <v-container fill-height justify-center  class="loader" v-if="!imgUrl">
                <v-progress-circular
                    indeterminate
                    color="orange darken-1"
                ></v-progress-circular>
            </v-container>

            <v-img
                v-else
                :src="imgUrl"
                height="180px"
                contain
            ></v-img>
            <v-card-text class="text-center">
                <strong>
                    {{ category }}
                </strong>
            </v-card-text>
        </v-card>
    </router-link>
</template>

<script>
    export default {
        name: "CategoryHighlight",
        data() {
            return {
                imgUrl: null,
            }
        },
        props: {
            category: {
                type: String,
                required: true
            },
            categoryImg: {
                type: String,
                required: true
            }
        },
        methods: {
            async loadPic(){ // setImgUrl() alternative ... working, but slow communication with server at local host, maybe useful later
                const categoryEncoded = encodeURIComponent(encodeURIComponent(this.categoryImg));
                const response = await this.$http.get(`/api/getRandomCategoryPic/${categoryEncoded}`);
                this.imgUrl = response.data.img_url;
            },
            setImgUrl (){
                switch(this.category) {
                    case "Kytary":
                        this.imgUrl = 'https://muzikercdn.com/uploads/products/65/6536/main_7fad2959.jpg';
                        break;
                    case "Bicí nástroje":
                        this.imgUrl = 'https://muzikercdn.com/uploads/products/54/5447/main_66c7506c.jpg';
                        break;
                    case "Smyčcové nástroje":
                        this.imgUrl = 'https://muzikercdn.com/uploads/products/52/5266/main_516b4aa4.jpg';
                        break;
                    case "Klávesové nástroje":
                        this.imgUrl = 'https://muzikercdn.com/uploads/products/58/5853/main_ebd86964.jpg';
                        break;
                    case "Nástrojové aparatury":
                        this.imgUrl = 'https://muzikercdn.com/uploads/products/19/1927/main_d1e76337.jpg';
                        break;
                    case "Struny":
                        this.imgUrl = 'https://muzikercdn.com/uploads/products/33/3303/main_ce64eef4.jpg';
                        break;
                    case "Dechové nástroje":
                        this.imgUrl = 'https://muzikercdn.com/uploads/products/28/2822/main_c14dfbec.png';
                        break;
                    case "Zobcové flétny":
                        this.imgUrl = 'https://muzikercdn.com/uploads/products/5443/544306/main_fe1bb7b5.jpg';
                        break;
                    default:
                        this.imgUrl = 'https://muzikercdn.com/uploads/products/65/6536/main_7fad2959.jpg';
                        break;
                }
            }


        },
        mounted() {
            this.setImgUrl();
        }
    }
</script>

<style scoped>
    .tile:hover .v-card__text {
        color: #FB8C00;
    }
    .link {
        text-decoration: none;
    }
    .loader {
        height: 180px !important;
    }
</style>
