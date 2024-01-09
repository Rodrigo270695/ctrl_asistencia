<script setup>
import { ref, defineProps } from "vue";
import { useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import Swal from "sweetalert2";
import Pagination from "@/Components/Pagination.vue";
import IndexGeneral from "../IndexGeneral.vue";
import ReasonForm from "./ReasonForm.vue";
import NotPermission from '@/Components/NotPermission.vue';

const props = defineProps({
    reasons: Array,
    texto: String,
});

const form = useForm({
    reason: Object,
});

let reasonToEdit = ref(null);
let showModal = ref(false);
let query = ref(props.texto);
let downloading = ref(false);

const addReason = () => {
    reasonToEdit.value = null;
    showModal.value = true;
};

const editReason = (reason) => {
    reasonToEdit.value = reason;
    showModal.value = true;
};

const search = () => {
    form.get(route("reason.search", { texto: query.value }));
};

const closeModal = () => {
    showModal.value = false;
    reasonToEdit.value = null;
};

const changeStatus = (reason) => {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¿Quieres cambiar el estado de del Motivo?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cambiar!",
        cancelButtonText: "No, cancelar!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.put(route("reason.change", reason), {
                preserveScroll: true,
            });
        }
    });
};

const goToReasonIndex = () => {
    window.location.href = route("reason.index");
};

const download = () => {
    downloading.value = true;
    window.location.href = route("reason.export", { texto: query.value });
    setTimeout(() => {
        downloading.value = false;
    }, 4000);
};
</script>
<template>
    <div class="" v-if="$page.props.user.roles.includes('admin') || $page.props.user.roles.includes('editor')">
        <IndexGeneral>
            <template #contentAbs>
                <div class="flex justify-between py-2 px-4 mr-4 mt-4">
                    <div class="relative">
                        <input
                            type="text"
                            v-model="query"
                            class="w-auto lg:w-96 hover:border-sky-300 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none"
                            placeholder="Buscar Motivo"
                            @keyup.enter="search"
                            @input="query = query.toUpperCase()"
                        />
                        <button
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-white bg-sky-500 rounded-e-md hover:bg-sky-600"
                            @click.prevent="goToReasonIndex"
                        >
                            <v-icon name="io-reload-circle-sharp" scale="1.5" />
                        </button>
                    </div>
                    <div>
                        <button
                            class="bg-sky-500 hover:bg-sky-600 p-2 text-white rounded-lg flex items-center"
                            @click="addReason"
                        >
                            <v-icon name="io-add-circle-sharp" />
                            <p class="sm:block hidden ml-2">agregar</p>
                        </button>
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
                                        class="px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        #
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Nombre
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Descripción
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        estado
                                    </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="(reason, index) in reasons.data"
                                    :key="reason.id"
                                >
                                    <td
                                        class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                    >
                                        {{ index + 1 }}
                                    </td>
                                    <td
                                        class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                    >
                                        {{ reason.name }}
                                    </td>
                                    <td
                                        class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                    >
                                        {{ reason.description }}
                                    </td>
                                    <td
                                        class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                    >
                                        <p
                                            class="inline-block px-2 rounded-full h-auto justify-center items-center text-sm"
                                            :class="{
                                                ' bg-green-500 text-white':
                                                    reason.status == 1,
                                                'bg-red-500 rounded text-white':
                                                    reason.status == 0,
                                            }"
                                        >
                                            {{
                                                reason.status == 1
                                                    ? "ACTIVO"
                                                    : "INACTIVO"
                                            }}
                                        </p>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                    >
                                        <button
                                            class="bg-yellow-500 text-white p-1 rounded-md hover:bg-yellow-600 cursor-pointer mr-1"
                                            @click="editReason(reason)"
                                        >
                                            <v-icon name="md-modeedit-round" />
                                        </button>
                                        <button
                                            v-if="
                                                $page.props.user.permissions.includes(
                                                    'delete workers'
                                                )
                                            "
                                            class="text-white p-1 rounded-md"
                                            :class="{
                                                'bg-red-400 hover:bg-red-500':
                                                    reason.status == 1,
                                                'bg-green-500 hover:bg-green-600':
                                                    reason.status == 0,
                                            }"
                                            @click="changeStatus(reason)"
                                        >
                                            <v-icon
                                                v-if="reason.status == 1"
                                                name="gi-cancel"
                                            />
                                            <v-icon v-else name="fa-check" />
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="reasons.data.length <= 0">
                                    <td class="text-center" colspan="8">
                                        No hay registros
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <Pagination :pagination="reasons" />
                    </div>
                </div>
                <Modal :show="showModal" @close="showModal = false">
                    <ReasonForm :reason="reasonToEdit" @close-modal="closeModal" />
                </Modal>
            </template>
        </IndexGeneral>
    </div>
    <div v-else class="">
        <NotPermission />
    </div>
</template>
