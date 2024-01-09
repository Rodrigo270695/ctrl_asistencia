<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, defineProps } from "vue";
import Modal from "@/Components/Modal.vue";
import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import Pagination from "@/Components/Pagination.vue";
import UserForm from "./UserForm.vue";
import { Inertia } from '@inertiajs/inertia';
import NotPermission from '@/Components/NotPermission.vue';

const props = defineProps({
    users: Array,
    texto: String,
    pdvs: Array
});

let query = ref(props.texto);
let userToEdit = ref(null);
let showModal = ref(false);
let downloading = ref(false);

let form = useForm({
    id: userToEdit.id,
});

const addUser = () => {
    userToEdit.value = null;
    showModal.value = true;
};

const editUser = (user) => {
    userToEdit.value = user;
    showModal.value = true;
};


const search = () => {
    form.get(route("user.search", { texto: query.value }));
};

const goToUserIndex = () => {
    window.location.href = route("user.index");
};

const closeModal = () => {
    showModal.value = false;
    userToEdit.value = null;
};

const deleteUser = (user) => {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¿Quieres eliminar al usuario "+user.name+" ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cambiar!",
        cancelButtonText: "No, cancelar!",
    }).then((result) => {
        console.log(user)
        if (result.isConfirmed) {
            form.delete(route("user.destroy", user), {
                preserveScroll: true,
            });
        }
    });
};

const download = () => {
    downloading.value = true;
    window.location.href = route("user.export", { texto: query.value });
    setTimeout(() => {
        downloading.value = false;
    }, 6000);
};

</script>

<template>
    <div class="" v-if="$page.props.user.roles.includes('admin')">
        <AppLayout title="Dashboard">
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Gestionar Usuarios
                </h2>
            </template>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                        <div class="flex justify-between py-2 px-4 mr-4 mt-4">
                            <div class="relative">
                                <input
                                    type="text"
                                    v-model="query"
                                    class="w-auto lg:w-96 hover:border-sky-300 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none"
                                    placeholder="Buscar Usuario"
                                    @input="query = query.toUpperCase()"
                                    @keyup.enter="search"
                                />
                                <button
                                    @click.prevent="goToUserIndex"
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-white bg-sky-500 rounded-e-md hover:bg-sky-600"
                                >
                                    <v-icon name="io-reload-circle-sharp" scale="1.5" />
                                </button>
                            </div>
                            <div>
                                <button
                                    class="bg-sky-500 hover:bg-sky-600 p-2 text-white rounded-lg flex items-center"
                                    @click="addUser"
                                >
                                    <v-icon name="io-add-circle-sharp" />
                                    <p class="sm:block hidden ml-2">agregar</p>
                                </button>
                            </div>
                        </div>

                        <div class="flex mt-4 mx-4">
                            <a @click="download"
                                class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                                Exportar
                                <v-icon name="bi-file-earmark-excel-fill" />
                            </a>
                            <div v-if="downloading" class="flex justify-center items-center space-x-2 mx-3">
                                <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
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
                                                Nombres
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Correo
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Rol
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                PDV
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-gray-200"
                                    >
                                        <tr
                                            v-for="(user, index) in users.data"
                                            :key="user.id"
                                        >
                                            <td
                                                class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                            >
                                                {{ index + 1 }}
                                            </td>
                                            <td
                                                class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                            >
                                                {{ user.name }}
                                            </td>
                                            <td
                                                class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                            >
                                                {{ user.email }}
                                            </td>
                                            <td
                                                class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                            >
                                                {{ user.roles[0].name }}
                                            </td>
                                            <td
                                                class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                            >
                                                {{ user.pdv.spot }}
                                            </td>
                                             <td
                                                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                            >
                                                <button
                                                    class="bg-yellow-500 text-white p-1 rounded-md hover:bg-yellow-600 cursor-pointer mr-1"
                                                    @click="editUser(user)"
                                                >
                                                    <v-icon
                                                        name="md-modeedit-round"
                                                    />
                                                </button>
                                                 <button
                                                    class="text-white p-1 rounded-md bg-red-400 hover:bg-red-500"
                                                    @click.prevent="deleteUser(user.id)"
                                                >
                                                    <v-icon
                                                        name="bi-trash"
                                                    />
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="users.data.length <= 0">
                                            <td class="text-center" colspan="5">No hay registros</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <Pagination :pagination="users" />
                            </div>
                        </div>

                    </div>
                </div>
                <Modal :show="showModal" @close="showModal = false">
                    <UserForm :user="userToEdit" :pdvs="pdvs" @close-modal="closeModal"/>
                </Modal>
            </div>
        </AppLayout>
    </div>
    <div v-else class="">
        <NotPermission />
    </div>
</template>
