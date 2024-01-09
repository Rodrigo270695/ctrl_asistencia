<script setup>
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    absence: Object
})

const form = useForm({
    _method: 'PUT',
    id: props.absence ? props.absence.id : "",
    file: '',
});

const onFileChange = (event) => {
    form.file = event.target.files[0];
};

const submit = () => {
    form.post(route("abs.updateFile", form.id), {
        preserveScroll: true,
        onSuccess: () => emit("close-modal"),
    });
};

const emit = defineEmits(["close-modal"]);

</script>

<template>
    <form @submit.prevent="submit">
        <div class="bg-white shadow-md rounded-md p-4">
            <div class="text-lg font-bold mb-4">Actualizar Documento</div>
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <div class="col-span-6 sm:col-span-6">
                            <InputLabel value="Documento" />
                            <input
                                type="file"
                                class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                                @change="onFileChange"
                                ref="file"
                                accept=".jpg, .jpeg, .png, .pdf, .doc, .docx"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button
                    class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2"
                    :disabled="form.processing"
                >
                    Actualizar
                </button>
            </div>
        </div>
    </form>
</template>
