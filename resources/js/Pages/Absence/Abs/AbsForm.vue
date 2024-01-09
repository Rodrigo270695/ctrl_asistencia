<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps } from "vue";

const props = defineProps({
    absence: Object,
    workers: Array,
    reasons: Array,
});

const onFileChange = (event) => {
    form.file = event.target.files[0];
};

const form = useForm({
    id: props.absence ? props.absence.id : "",
    start_date: props.absence ? props.absence.start_date : "",
    status_detail: props.absence ? props.absence.status_detail : "",
    status: props.absence ? props.absence.status : "",
    end_date: props.absence ? props.absence.end_date : "",
    reason_id: props.absence ? props.absence.reason_id : "",
    file: "",
    file_old: props.absence ? props.absence.file : "",
    worker_id: props.absence ? props.absence.worker_id : "",
});

const submit = () => {
    if (props.absence) {
        form.put(route("abs.update", props.absence.id), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("abs.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const emit = defineEmits(["close-modal"]);

const datos = [
    { id: 1, name: "POR APROBAR" },
    { id: 2, name: "OBSERVADO" },
    { id: 3, name: "APROBADO" },
    { id: 4, name: "RECHAZADO" }
];

</script>

<template>
    <form @submit.prevent="submit">
        <div class="bg-white shadow-md rounded-md p-4">
            <div class="text-lg font-bold mb-4">
                {{
                    form.id == 0
                    ? "Registrar Inasistencia"
                    : "Actualizar Inasistencia"
                }}
            </div>
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Fecha inicial" />
                        <TextInput type="date" class="w-full" v-model="form.start_date"
                            :readonly="$page.props.user.roles.includes('editor')" />
                        <InputError class="w-full" :message="form.errors.start_date" />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Fecha final" />
                        <TextInput type="date" class="w-full" v-model="form.end_date"
                            :readonly="$page.props.user.roles.includes('editor')" />
                        <InputError class="w-full" :message="form.errors.end_date" />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Motivo" />  
                        <select id="select" v-model="form.reason_id"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                            :disabled="$page.props.user.roles.includes('editor')">
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option v-for="reason in props.reasons" :key="reason.id" :value="reason.id">
                                {{ reason.name }}
                            </option>
                        </select>
                        <InputError class="w-full" :message="form.errors.reason_id" />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Trabajador" />
                        <select id="select" v-model="form.worker_id"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                            :disabled="$page.props.user.roles.includes('editor')">
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option v-for="worker in props.workers" :key="worker.id" :value="worker.id">
                                {{ worker.name }} {{ worker.lastname }}
                            </option>
                        </select>
                        <InputError class="w-full" :message="form.errors.worker_id" />
                    </div>
                    <div v-if="form.id < 1" class="col-span-6 sm:col-span-6">
                        <InputLabel value="Documento" />
                        <input type="file"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                            @change="onFileChange" ref="image" accept=".jpg, .jpeg, .png, .pdf, .doc, .docx" />
                        <p class="text-blue-900 text-sm font-bold">
                            {{ form.file_old }}
                        </p>
                        <InputError class="w-full" :message="form.errors.file" />
                    </div>
                    <div v-if="$page.props.user.roles.includes('admin') |
                        $page.props.user.roles.includes('editor')
                        " class="col-span-6 sm:col-span-3">
                        <InputLabel value="Estado" />
                        <select id="select" v-model="form.status"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500">
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option v-for="dato in datos" :key="dato.id" :value="dato.name">
                                {{ dato.name }}
                            </option>
                        </select>
                        <InputError class="w-full" :message="form.errors.status" />
                    </div>
                    <div v-if="$page.props.user.roles.includes('admin') |
                        $page.props.user.roles.includes('editor')
                        " class="col-span-6 sm:col-span-6">
                        <InputLabel value="Descripción" />
                        <TextArea class="w-full" v-model="form.status_detail" />
                        <InputError class="w-full" :message="form.errors.status_detail" />
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
