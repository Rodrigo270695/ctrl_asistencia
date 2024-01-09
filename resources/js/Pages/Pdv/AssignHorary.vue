<script setup>
import { ref, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props =  defineProps({
    data: Array,
    pdv: Array
})

let selectedHorary = ref({
    LUNES: null,
    MARTES: null,
    MIERCOLES: null,
    JUEVES: null,
    VIERNES: null,
    SABADO: null,
    DOMINGO: null
});

onMounted(() => {
    if (props.pdv.horaries) {
        props.pdv.horaries.forEach(horary => {
            selectedHorary.value[horary.week] = horary.id;
        });
    }
});

const updateHoraries = () => {
    Inertia.put(route('pdv.updateHoraries', props.pdv.id), {
        horaries: selectedHorary.value,
    });
};

</script>

<template>
    <div class="py-3">

        <h1 class="ml-2 mb-3 text-2xl">Seleccione el horario</h1>

        <div class="px-2 grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="whitespace-nowrap">
                Lunes
                <div class="" v-for="horary in data" :key="horary.id">
                    <input class="ml-4" type="radio" :value="horary.id" v-model="selectedHorary.LUNES" v-if="horary.week == 'LUNES'" :id="'LUNES-' + horary.id">
                    <label class="text-sm ml-2" v-if="horary.week == 'LUNES'" :for="'LUNES-' + horary.id">{{horary.hi}} - {{ horary.rs }} - {{ horary.ri }} - {{ horary.hs }}</label>
                </div>
            </div>
            <div class="whitespace-nowrap">
                Martes
                <div class="" v-for="horary in data" :key="horary.id">
                    <input class="ml-4" type="radio" :value="horary.id" v-model="selectedHorary.MARTES" v-if="horary.week == 'MARTES'" :id="'MARTES-' + horary.id">
                    <label class="text-sm ml-2" v-if="horary.week == 'MARTES'" :for="'MARTES-' + horary.id">{{horary.hi}} - {{ horary.rs }} - {{ horary.ri }} - {{ horary.hs }}</label>
                </div>
            </div>
            <div class="whitespace-nowrap">
                Miercoles
                <div class="" v-for="horary in data" :key="horary.id">
                    <input class="ml-4" type="radio" :value="horary.id" v-model="selectedHorary.MIERCOLES" v-if="horary.week == 'MIERCOLES'" :id="'MIERCOLES-' + horary.id">
                    <label class="text-sm ml-2" v-if="horary.week == 'MIERCOLES'" :for="'MIERCOLES-' + horary.id">{{horary.hi}} - {{ horary.rs }} - {{ horary.ri }} - {{ horary.hs }}</label>
                </div>
            </div>
            <div class="whitespace-nowrap">
                Jueves
                <div class="" v-for="horary in data" :key="horary.id">
                    <input class="ml-4" type="radio" :value="horary.id" v-model="selectedHorary.JUEVES" v-if="horary.week == 'JUEVES'" :id="'JUEVES-' + horary.id">
                    <label class="text-sm ml-2" v-if="horary.week == 'JUEVES'" :for="'JUEVES-' + horary.id">{{horary.hi}} - {{ horary.rs }} - {{ horary.ri }} - {{ horary.hs }}</label>
                </div>
            </div>
            <div class="whitespace-nowrap">
                Viernes
                <div class="" v-for="horary in data" :key="horary.id">
                    <input class="ml-4" type="radio" :value="horary.id" v-model="selectedHorary.VIERNES" v-if="horary.week == 'VIERNES'" :id="'VIERNES-' + horary.id">
                    <label class="text-sm ml-2" v-if="horary.week == 'VIERNES'" :for="'VIERNES-' + horary.id">{{horary.hi}} - {{ horary.rs }} - {{ horary.ri }} - {{ horary.hs }}</label>
                </div>
            </div>
            <div class="whitespace-nowrap">
                Sábado
                <div class="" v-for="horary in data" :key="horary.id">
                    <input class="ml-4" type="radio" :value="horary.id" v-model="selectedHorary.SABADO" v-if="horary.week == 'SABADO'" :id="'SABADO-' + horary.id">
                    <label class="text-sm ml-2" v-if="horary.week == 'SABADO'" :for="'SABADO-' + horary.id">{{horary.hi}} - {{ horary.rs }} - {{ horary.ri }} - {{ horary.hs }}</label>
                </div>
            </div>
            <div class="whitespace-nowrap">
                Domingo
                <div class="" v-for="horary in data" :key="horary.id">
                    <input class="ml-4" type="radio" :value="horary.id" v-model="selectedHorary.DOMINGO" v-if="horary.week == 'DOMINGO'" :id="'DOMINGO-' + horary.id">
                    <label class="text-sm ml-2" v-if="horary.week == 'DOMINGO'" :for="'DOMINGO-' + horary.id">{{horary.hi}} - {{ horary.rs }} - {{ horary.ri }} - {{ horary.hs }}</label>
                </div>
            </div>
        </div>
        <div class="flex justify-end">
            <button class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2" @click.prevent="updateHoraries" >Registrar</button>
        </div>

    </div>
</template>
