<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps } from "vue";
import { useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    assists: Array,
    texto: String,
    fromDate: String,
    toDate: String,
});

const form = useForm({
    /*     fromDate: '',
    toDate: '' */
});

let query = ref(props.texto);
let fromDate = ref(props.fromDate);
let toDate = ref(props.toDate);
let downloading = ref(false);

const search = () => {
    form.get(
        route("report.search", {
            texto: query.value,
            fromDate: fromDate.value,
            toDate: toDate.value,
        })
    );
};

const goToReportIndex = () => {
    window.location.href = route("report.index");
};

const download = () => {
    downloading.value = true;
    window.location.href = route("report.export", { texto: query.value });
    setTimeout(() => {
        downloading.value = false;
    }, 7000);
};
</script>

<template>
    <AppLayout title="Reports">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reportes
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="mt-4 mx-4 p-5 bg-white rounded-lg shadow-lg">
                        <h3 class="text-lg font-semibold mb-2">Leyenda</h3>
                        <div
                            class="grid sm:flex grid-cols-1 gap-2 mt-2 text-sm"
                        >
                            <div class="flex items-center space-x-1">
                                <div
                                    class="w-4 h-4 rounded-full bg-green-500"
                                ></div>
                                <span>Salida / Ingreso</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <div
                                    class="w-4 h-4 rounded-full bg-yellow-500"
                                ></div>
                                <span>Tolerancia</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <div
                                    class="w-4 h-4 rounded-full bg-red-500"
                                ></div>
                                <span>Tarde / Antes de tiempo</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 mx-4 p-5 bg-white rounded-lg shadow-lg">
                        <h3 class="text-lg font-semibold mb-2">Busqueda</h3>
                        <div class="flex flex-col md:flex-row justify-between">
                            <div class="relative mb-4 md:mb-0">
                                <input
                                    type="text"
                                    v-model="query"
                                    class="w-full lg:w-96 hover:border-sky-300 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none"
                                    placeholder="Buscar Asistencias"
                                    @keyup.enter="search"
                                    @input="query = query.toUpperCase()"
                                />
                                <button
                                    @click.prevent="goToReportIndex"
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-white bg-sky-500 rounded-e-md hover:bg-sky-600"
                                >
                                    <v-icon name="io-reload-circle-sharp" scale="1.5" />
                                </button>
                            </div>
                            <div class="flex flex-col md:flex-row">
                                <div
                                    class="flex justify-center items-center mb-4 md:mb-0 md:mr-3"
                                >
                                    <InputLabel class="mx-2" value="De:" />
                                    <TextInput
                                        type="date"
                                        class="w-full"
                                        v-model="fromDate"
                                    />
                                    <InputError
                                        class="w-full"
                                        :message="form.errors.fromDate"
                                    />
                                </div>
                                <div
                                    class="flex justify-center items-center mb-4 md:mb-0 md:mr-3"
                                >
                                    <InputLabel class="mx-2" value="Hasta:" />
                                    <TextInput
                                        type="date"
                                        class="w-full"
                                        v-model="toDate"
                                    />
                                    <InputError
                                        class="w-full"
                                        :message="form.errors.toDate"
                                    />
                                </div>
                                <div class="flex justify-center items-center">
                                    <button
                                        @click.prevent="search"
                                        class="text-white bg-sky-950 p-2 rounded-md w-full"
                                    >
                                        <v-icon name="fa-search" scale="1.2" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex mt-4 mx-4">
                        <a
                            @click="download"
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded cursor-pointer"
                        >
                            Exportar
                            <v-icon name="bi-file-earmark-excel-fill" />
                        </a>
                        <div
                            v-if="downloading"
                            class="flex justify-center items-center space-x-2 mx-3"
                        >
                            <svg
                                class="animate-spin h-5 w-5 text-blue-500"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            <div>Descargando...</div>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="overflow-x-auto rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            #
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Fecha
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Trabajador
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Ingreso laboral
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Salida de refrigerio
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Ingreso de refrigerio
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Salida laboral
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="(assist, index) in assists.data"
                                        :key="assist.id"
                                    >
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            {{ index + 1 }}
                                        </td>
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            {{
                                                new Date(
                                                    assist.current_date +
                                                        "T00:00:00"
                                                ).toLocaleDateString("es-PE", {
                                                    day: "2-digit",
                                                    month: "2-digit",
                                                    year: "2-digit",
                                                }) +
                                                " - " +
                                                new Date(
                                                    assist.current_date +
                                                        "T00:00:00"
                                                ).toLocaleDateString("es-PE", {
                                                    weekday: "long",
                                                })
                                            }}
                                        </td>
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            {{ assist.worker.name }}
                                            {{ assist.worker.lastname }} -
                                            {{ assist.worker.num_document }}
                                        </td>
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            <p
                                                class="inline-block px-2 rounded-full h-auto justify-center items-center text-sm"
                                                :class="{
                                                    ' bg-green-500 text-white':
                                                        assist.status_entry ===
                                                        'ingreso',
                                                    'bg-red-500 rounded text-white':
                                                        assist.status_entry ===
                                                        'tarde',
                                                    'bg-yellow-500 rounded text-white':
                                                        assist.status_entry ===
                                                        'tolerancia',
                                                }"
                                            >
                                                {{ assist.hi }}
                                                <v-icon
                                                    v-if="
                                                        assist.status_entry ===
                                                        'tarde'
                                                    "
                                                    name="io-close-circle-sharp"
                                                    class="text-white"
                                                />
                                                <v-icon
                                                    v-if="
                                                        assist.status_entry ===
                                                        'ingreso'
                                                    "
                                                    name="bi-check-circle-fill"
                                                    class="text-white"
                                                />
                                                <v-icon
                                                    v-if="
                                                        assist.status_entry ===
                                                        'tolerancia'
                                                    "
                                                    name="bi-dash-circle-fill"
                                                    class="text-white"
                                                />
                                            </p>
                                        </td>
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            <p
                                                class="inline-block px-2 rounded-full h-auto justify-center items-center text-sm"
                                                :class="{
                                                    ' bg-green-500 text-white':
                                                        assist.status_foodout ===
                                                        'salida',
                                                    'bg-red-500 rounded text-white':
                                                        assist.status_foodout ===
                                                        'antes de tiempo',
                                                }"
                                            >
                                                {{ assist.rs }}
                                                <v-icon
                                                    v-if="
                                                        assist.status_foodout ===
                                                        'antes de tiempo'
                                                    "
                                                    name="io-close-circle-sharp"
                                                    class="text-white"
                                                />
                                                <v-icon
                                                    v-if="
                                                        assist.status_foodout ===
                                                        'salida'
                                                    "
                                                    name="bi-check-circle-fill"
                                                    class="text-white"
                                                />
                                            </p>
                                        </td>
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            <p
                                                class="inline-block px-2 rounded-full h-auto justify-center items-center text-sm"
                                                :class="{
                                                    ' bg-green-500 text-white':
                                                        assist.status_foodentry ===
                                                        'ingreso',
                                                    'bg-red-500 rounded text-white':
                                                        assist.status_foodentry ===
                                                        'tarde',
                                                    'bg-yellow-500 rounded text-white':
                                                        assist.status_foodentry ===
                                                        'tolerancia',
                                                }"
                                            >
                                                {{ assist.ri }}
                                                <v-icon
                                                    v-if="
                                                        assist.status_foodentry ===
                                                        'tarde'
                                                    "
                                                    name="io-close-circle-sharp"
                                                    class="text-white"
                                                />
                                                <v-icon
                                                    v-if="
                                                        assist.status_foodentry ===
                                                        'ingreso'
                                                    "
                                                    name="bi-check-circle-fill"
                                                    class="text-white"
                                                />
                                                <v-icon
                                                    v-if="
                                                        assist.status_foodentry ===
                                                        'tolerancia'
                                                    "
                                                    name="bi-dash-circle-fill"
                                                    class="text-white"
                                                />
                                            </p>
                                        </td>
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            <p
                                                class="inline-block px-2 rounded-full h-auto justify-center items-center text-sm"
                                                :class="{
                                                    ' bg-green-500 text-white':
                                                        assist.status_out ===
                                                        'salida',
                                                    'bg-red-500 rounded text-white':
                                                        assist.status_out ===
                                                        'antes de tiempo',
                                                }"
                                            >
                                                {{ assist.hs }}
                                                <v-icon
                                                    v-if="
                                                        assist.status_out ===
                                                        'antes de tiempo'
                                                    "
                                                    name="io-close-circle-sharp"
                                                    class="text-white"
                                                />
                                                <v-icon
                                                    v-if="
                                                        assist.status_out ===
                                                        'salida'
                                                    "
                                                    name="bi-check-circle-fill"
                                                    class="text-white"
                                                />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr v-if="assists.data.length <= 0">
                                        <td class="text-center" colspan="5">
                                            No hay registros
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <Pagination :pagination="assists" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
