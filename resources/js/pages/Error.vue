<template>
    <v-container>

        <v-divider class="mt-0 mb-8"></v-divider>

        <v-card max-width="400" class="mx-auto" width="fit-content">

            <v-card-title>Něco se pokazilo! :(</v-card-title>

            <v-card-subtitle v-if="$route.params.code == 404">Stránka nebyla nalezena</v-card-subtitle>
            <v-card-subtitle v-else-if="$route.params.code == 429">Server je příliž zaneprázdněn</v-card-subtitle>
            <v-card-subtitle v-else>Vyskytla se neočekávaná chyba</v-card-subtitle>

            <v-card-text v-if="$route.params.code != 404">Prosím, zopakujte tuto akci později. Pokud problém přetrvává, kontaktujte mne na xjancik1@mendelu.cz</v-card-text>

            <v-card-actions>
                <v-btn text color="orange darken-1" @click="sendMeBack">
                    Zpět
                </v-btn>
                <v-btn text color="orange darken-1" @click="sendMeHome">
                    Domů
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-container>

</template>

<script>
    export default {
        name: "Error",
        methods: {
            sendMeBack() {
                this.$router.back();
            },
            sendMeHome() {
                this.$router.push({name: 'home'});
            }
        }
    }
</script>

<style scoped>

</style>
