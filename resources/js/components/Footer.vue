<template>
    <v-container>
        <v-divider></v-divider>
        <v-footer
            class="mt-4"
            padless
            color="transparent"
        >

            <v-col cols="12" class="pa-4 text-center">
                <strong>HrajLevně.cz</strong>
                <br>
                <span>{{ new Date().getFullYear() }}</span>
            </v-col>

        </v-footer>
    </v-container>
</template>

<script>
    export default {
        name: "Footer"
    }
</script>

<style scoped>

</style>
