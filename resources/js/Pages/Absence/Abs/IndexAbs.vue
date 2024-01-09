<script setup>
import IndexGeneral from "../IndexGeneral.vue";
import { ref, defineProps } from "vue";
import { useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import Swal from "sweetalert2";
import Pagination from "@/Components/Pagination.vue";
import AbsForm from "./AbsForm.vue";
import ViewAbs from "./ViewAbs.vue";
import UpdateFile from "./UpdateFile.vue";

const props = defineProps({
    absences: Array,
    workers: Array,
    reasons: Array,
    texto: String,
});

const form = useForm({
    absence: Object,
});

let absenceToEdit = ref(null);
let showModal = ref(false);
let viewModal = ref(false);
let imgModal = ref(false);
let query = ref(props.texto);
let downloading = ref(false);

const addAbsence = () => {
    absenceToEdit.value = null;
    showModal.value = true;
};

const editAbsence = (absence) => {
    absenceToEdit.value = absence;
    showModal.value = true;
};

const viewAbsence = (absence) => {
    absenceToEdit.value = absence;
    viewModal.value = true;
};

const imgAbsence = (absence) => {
    absenceToEdit.value = absence;
    imgModal.value = true;
};

const search = () => {
    form.get(route("abs.search", { texto: query.value }));
};

const searchByDate = (dateRange) => {
    let date;
    switch (dateRange) {
        case "today":
            date = new Date().toISOString().split("T")[0];
            break;
        case "all":
            date = "all";
            break;
    }
    form.get(route("abs.search", { texto: date }));
};

const closeModal = () => {
    imgModal.value = false;
    showModal.value = false;
    absenceToEdit.value = null;
};

const deleteAbsence = (absence) => {
    Swal.fire({
        title: "¿Estás seguro?",
        text:
            "¿Quieres eliminar la inasistencia de " +
            " " +
            absence.worker.name +
            " " +
            absence.worker.lastname +
            " ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cambiar!",
        cancelButtonText: "No, cancelar!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route("abs.destroy", absence.id), {
                preserveScroll: true,
            });
        }
    });
};

const goToAbsenceIndex = () => {
    window.location.href = route("abs.index");
};

const download = () => {
    downloading.value = true;
    window.location.href = route("abs.export", { texto: query.value });
    setTimeout(() => {
        downloading.value = false;
    }, 6000);
};

</script>
<template>
    <IndexGeneral>
        <template #contentAbs>
            <div>
                <button
                    class="ml-4 px-2 py-1 rounded-md text-white bg-sky-950"
                    @click="searchByDate('all')"
                >
                    Todos
                </button>
            </div>
            <div class="flex justify-between py-2 px-4 mr-4 mt-4">
                <div class="relative">
                    <input
                        type="text"
                        v-model="query"
                        class="w-auto lg:w-96 hover:border-sky-300 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none"
                        placeholder="Buscar Inasistencia"
                        @keyup.enter="search"
                        @input="query = query.toUpperCase()"
                    />
                    <button
                        class="absolute inset-y-0 right-0 px-3 flex items-center text-white bg-sky-500 rounded-e-md hover:bg-sky-600"
                        @click.prevent="goToAbsenceIndex"
                    >
                        <v-icon name="io-reload-circle-sharp" scale="1.5" />
                    </button>
                </div>
                <div>
                    <button
                        v-if="!$page.props.user.roles.includes('editor')"
                        class="bg-sky-500 hover:bg-sky-600 p-2 text-white rounded-lg flex items-center"
                        @click="addAbsence"
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
                                    Fecha de registro
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Trabajador
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Motivo
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Documento
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
                                v-for="(absence, index) in absences.data"
                                :key="absence.id"
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
                                        new Date(absence.created_at + 'T00:00:00').toLocaleDateString('es-PE')
                                    }}

                                </td>
                                <td
                                    class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                >
                                    {{ absence.worker.name }}
                                    {{ absence.worker.lastname }} -
                                    {{ absence.worker.num_document }}
                                </td>
                                <td
                                    class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                >
                                    {{ absence.reason.name }}
                                </td>
                                <td
                                    class="text-xs md:text-sm px-6 py-4 whitespace-nowrap text-center"
                                >
                                    <a
                                        :href="`/absences/download/${absence.file}`"
                                        :download="absence.file"
                                    >
                                        <v-icon
                                            v-if="
                                                absence.file &&
                                                absence.file.endsWith('.pdf')
                                            "
                                            name="vi-file-type-pdf"
                                            scale="2"
                                        />
                                        <v-icon
                                            v-else-if="
                                                (absence.file &&
                                                    absence.file.endsWith(
                                                        '.docx'
                                                    )) ||
                                                (absence.file &&
                                                    absence.file.endsWith(
                                                        '.doc'
                                                    ))
                                            "
                                            name="vi-file-type-word"
                                            scale="2"
                                        />
                                        <v-icon
                                            v-else-if="
                                                absence.file &&
                                                (absence.file.endsWith(
                                                    '.png'
                                                ) ||
                                                    absence.file.endsWith(
                                                        '.jpg'
                                                    ) ||
                                                    absence.file.endsWith(
                                                        '.jpeg'
                                                    ))
                                            "
                                            name="vi-file-type-image"
                                            scale="2"
                                        />
                                    </a>
                                    <p v-if="absence.file === null">
                                        <v-icon
                                            name="vi-default-file"
                                            scale="2"
                                        />
                                    </p>
                                </td>
                                <td
                                    class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                >
                                    <p
                                        class="inline-block px-2 rounded-full h-auto justify-center items-center text-sm"
                                        :class="{
                                            ' bg-gray-500 text-white':
                                                absence.status == 'POR APROBAR',
                                            'bg-green-500 rounded text-white':
                                                absence.status == 'APROBADO',
                                            'bg-red-500 rounded text-white':
                                                absence.status == 'RECHAZADO',
                                            'bg-yellow-500 rounded text-white':
                                                absence.status == 'OBSERVADO',
                                        }"
                                    >
                                        {{ absence.status }}
                                    </p>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                >
                                    <button
                                        class="bg-blue-500 text-white p-1 rounded-md hover:bg-blue-600 cursor-pointer mr-1"
                                        @click="viewAbsence(absence)"
                                    >
                                        <v-icon name="fa-eye" />
                                    </button>
                                    <button
                                        v-if="
                                            absence.status !== 'APROBADO' &&
                                            absence.status !== 'RECHAZADO' &&
                                            $page.props.user.roles.includes(
                                                'supervisor'
                                            )
                                        "
                                        class="bg-green-500 text-white p-1 rounded-md hover:bg-green-600 cursor-pointer mr-1"
                                        @click="imgAbsence(absence)"
                                    >
                                        <v-icon
                                            name="bi-file-earmark-arrow-up-fill"
                                        />
                                    </button>
                                    <button
                                        v-if="
                                            absence.status !== 'APROBADO' &&
                                            absence.status !== 'RECHAZADO' &&
                                            (absence.status === 'POR APROBAR' ||
                                                absence.status ===
                                                    'OBSERVADO' ||
                                                $page.props.user.roles.includes(
                                                    'admin'
                                                ) ||
                                                $page.props.user.roles.includes(
                                                    'editor'
                                                ))
                                        "
                                        class="bg-yellow-500 text-white p-1 rounded-md hover:bg-yellow-600 cursor-pointer mr-1"
                                        @click="editAbsence(absence)"
                                    >
                                        <v-icon name="md-modeedit-round" />
                                    </button>
                                    <button
                                        v-if="
                                            (absence.status === 'POR APROBAR') |
                                                $page.props.user.roles.includes(
                                                    'admin'
                                                )
                                        "
                                        class="bg-red-400 text-white p-1 rounded-md hover:bg-red-500 cursor-pointer mr-1"
                                        @click="deleteAbsence(absence)"
                                    >
                                        <v-icon name="bi-trash" />
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="absences.data.length <= 0">
                                <td class="text-center" colspan="8">
                                    No hay registros
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <Pagination :pagination="absences" />
                </div>
            </div>
            <Modal :show="showModal" @close="showModal = false">
                <AbsForm
                    :absence="absenceToEdit"
                    :reasons="reasons"
                    :workers="workers"
                    @close-modal="closeModal"
                />
            </Modal>
            <Modal :show="viewModal" @close="viewModal = false">
                <ViewAbs :absence="absenceToEdit" />
            </Modal>
            <Modal :show="imgModal" @close="imgModal = false">
                <UpdateFile
                    :absence="absenceToEdit"
                    @close-modal="closeModal"
                />
            </Modal>
        </template>
    </IndexGeneral>
</template>
