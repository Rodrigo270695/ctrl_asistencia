<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    worker: Array,
    dni: String,
});


const form = useForm({
    id: props.worker ? props.worker.id : "",
    name: props.worker ? props.worker.name + " " + props.worker.lastname : "",
    pdv: props.worker ? props.worker.pdv.spot : "",
    tipoRegistro: "",
});

let fechaActual = ref("");
let horaActual = ref("");
let dniWorker = ref(props.dni);

const getCurrentDateTime = () => {
    const currentDate = new Date();
    const year = currentDate.getFullYear();
    const month = String(currentDate.getMonth() + 1).padStart(2, "0");
    const day = String(currentDate.getDate()).padStart(2, "0");
    const hours = String(currentDate.getHours()).padStart(2, "0");
    const minutes = String(currentDate.getMinutes()).padStart(2, "0");
    const seconds = String(currentDate.getSeconds()).padStart(2, "0");
    fechaActual.value = `${day}-${month}-${year}`;
    horaActual.value = `${hours}:${minutes}:${seconds}`;
};

getCurrentDateTime();

setInterval(() => {
    getCurrentDateTime();
}, 1000);


const getWorker = () => {
    form.get(route("assist.worker", dniWorker.value), {
        preserveScroll: true,
    });
};

const submit = () => {
    form.post(route("assist.store"), {
        preserveScroll: true,
    });
    form.pdv = "";
    form.name = "";
};

const registros = ref([
    { id: 1, name: "Ingreso" },
    { id: 2, name: "Refrigerio/S" },
    { id: 3, name: "Refrigerio/I" },
    { id: 4, name: "Salida" },
]);
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestionar Asistencia
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="">
                        <div
                            class="py-3 flex justify-center align-items-center bg-cyan-950 text-white"
                        >
                            <h1 class="text-lg md:text-2xl uppercase">
                                Registre su asistencia
                            </h1>
                        </div>
                        <div
                            class="m-10 p-2 bg-cyan-950 inline-block rounded-md"
                        >
                            <div class="flex flex-col text-white">
                                <p class="text-xl font-semibold">
                                    Fecha:
                                    <span class="font-normal">{{
                                        fechaActual
                                    }}</span>
                                </p>
                                <p class="text-xl font-semibold mt-2">
                                    Hora:
                                    <span class="font-normal">{{
                                        horaActual
                                    }}</span>
                                </p>
                            </div>
                        </div>
                        <div
                            class="grid grid-cols-1 md:grid-cols-3 gap-4 mx-10 mb-5"
                        >
                            <div>
                                <InputLabel value="DNI" />
                                <TextInput
                                    class="w-full"
                                    v-model="dniWorker"
                                    @keyup.enter="getWorker"
                                />
                                <InputError
                                    class="w-full"
                                    :message="form.errors.id"
                                />
                            </div>
                            <div>
                                <InputLabel value="Nombres" />
                                <TextInput
                                    class="w-full"
                                    v-model="form.name"
                                    readonly
                                />
                            </div>
                            <div>
                                <InputLabel value="Punto de venta" />
                                <TextInput
                                    class="w-full"
                                    v-model="form.pdv"
                                    readonly
                                />
                            </div>
                        </div>

                        <form @submit.prevent="submit">
                            <div
                                class="grid grid-cols-1 md:grid-cols-3 gap-4 mx-10 mb-5"
                            >
                                <div class="">
                                    <InputLabel value="Tipo de registro" />
                                    <select
                                        id="select"
                                        v-model="form.tipoRegistro"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                                    >
                                        <option
                                            v-for="dato in registros"
                                            :key="dato.id"
                                            :value="dato.name"
                                        >
                                            {{ dato.name }}
                                        </option>
                                    </select>
                                    <InputError
                                        class="w-full"
                                        :message="form.errors.tipoRegistro"
                                    />
                                </div>
                                <div class="">
                                    <!-- <p>.</p>
                                    <button
                                        class="bg-green-600 hover:bg-green-500 p-2 text-white rounded-lg flex items-center"
                                    >
                                        <v-icon name="fa-eye" />
                                    </button> -->
                                </div>
                                <div class=""></div>
                                <div class="col-span-3 text-center flex">
                                    <button
                                        class="justify-center w-full bg-sky-500 hover:bg-sky-600 p-2 text-white rounded-lg flex items-center"
                                    >
                                        <v-icon name="io-add-circle-sharp" />
                                        <p class="ml-2">Registrar</p>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
