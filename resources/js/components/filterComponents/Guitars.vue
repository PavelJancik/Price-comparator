<template>
    <div>
        <type @typeChange="setTypes"></type>
        <strings @stringsChange="setStrings"></strings>
        <color @colorChange="setColors"></color>
        <surface-finish @surfaceChange="setSurface"></surface-finish>
        <hand @handChange="setHand"></hand>
        <material @materialChange="setMaterials"></material>
    </div>
</template>

<script>
    import Strings from "./Strings";
    import Color from "./Color";
    import SurfaceFinish from "./SurfaceFinish";
    import Hand from "./Hand";
    import Type from "./Type";
    import Material from "./Material";


    export default {
        name: "Guitars",
        components: {
            Strings,
            Color,
            SurfaceFinish,
            Hand,
            Type,
            Material,
        },
        methods: {
            setStrings(strings){
                this.$emit('stringsChange', strings);
            },
            setColors(colors){
                this.$emit('colorChange', colors);
            },
            setSurface(surface){
                this.$emit('surfaceChange', surface);
            },
            setElectronics(electronics){
                this.$emit('electronicsChange', electronics);
            },
            setHand(hands){
                this.$emit('handChange', hands);
            },
            setTypes(types){
                this.$emit('typeChange', types);
            },
            setMaterials(materials){
                this.$emit('materialChange', materials);
            }
        }
    }
</script>

<style scoped>

</style>
