<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps } from "vue";

const props = defineProps({
    horary: Object,
});

const form = useForm({
    id: props.horary ? props.horary.id : "",
    week: props.horary ? props.horary.week : "",
    hi: props.horary ? props.horary.hi : "",
    rs: props.horary ? props.horary.rs : "",
    ri: props.horary ? props.horary.ri : "",
    hs: props.horary ? props.horary.hs : "",
});

const submit = () => {
    if (props.horary) {
        form.put(route("horary.update", props.horary.id), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("horary.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const emit = defineEmits(["close-modal"]);

const datos = [
    { id: 1, name: "LUNES" },
    { id: 2, name: "MARTES" },
    { id: 3, name: "MIERCOLES" },
    { id: 4, name: "JUEVES" },
    { id: 5, name: "VIERNES" },
    { id: 6, name: "SABADO" },
    { id: 7, name: "DOMINGO" },
];
</script>

<template>
    <form @submit.prevent="submit">
        <div class="bg-white shadow-md rounded-md p-4">
            <div class="text-lg font-bold mb-4">
                {{ form.id == 0 ? "Registrar Horario" : "Actualizar Horario" }}
            </div>
            <div class="mb-4">
                <div class="grid grid-cols-4 gap-3">
                    <div class="col-span-4 sm:col-span-4">
                        <InputLabel value="Día de la semana" />
                        <select id="select" v-model="form.week"
                        class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500">
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option v-for="dato in datos" :key="dato.id" :value="dato.name">
                                {{ dato.name }}
                            </option>
                        </select>
                        <InputError class="w-full" :message="form.errors.week" />
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <InputLabel value="Horario Ingeso" />
                        <TextInput type="time" class="w-full" v-model="form.hi" />
                        <InputError class="w-full" :message="form.errors.hi" />
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <InputLabel value="Refrigerio/S " />
                        <TextInput type="time" class="w-full" v-model="form.rs" />
                        <InputError class="w-full" :message="form.errors.rs" />
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <InputLabel value="Refrigerio/I" />
                        <TextInput type="time" class="w-full" v-model="form.ri" />
                        <InputError class="w-full" :message="form.errors.ri" />
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <InputLabel value="Horario Salida" />
                        <TextInput type="time" class="w-full" v-model="form.hs" />
                        <InputError class="w-full" :message="form.errors.hs" />
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2" :disabled="form.processing">
                    {{ form.id == 0 ? "Registrar" : "Actualizar" }}
                </button>
            </div>
        </div>
    </form>
</template>
