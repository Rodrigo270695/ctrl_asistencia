<script setup>
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    worker: Object,
    pdvs: Array
});


const form = useForm({
    id: props.worker ? props.worker.id : '',
    name: props.worker ? props.worker.name : '',
    lastname: props.worker ? props.worker.lastname : '',
    document_type: props.worker ? props.worker.document_type : '',
    num_document: props.worker ? props.worker.num_document : '',
    entry_date: props.worker ? props.worker.entry_date : '',
    charge: props.worker ? props.worker.charge : '',
    phone: props.worker ? props.worker.phone : '',
    email: props.worker ? props.worker.email : '',
    address: props.worker ? props.worker.address : '',
    pdv_id: props.worker ? props.worker.pdv_id : '',
})

const submit = () => {
    if (props.worker) {
        form.put(route('worker.update', props.worker.id), {
            preserveScroll: true,
            onSuccess: () => emit('close-modal')
        });
    } else {
        form.post(route('worker.store'), {
            preserveScroll: true,
            onSuccess: () => emit('close-modal')
        });
    }
}

const emit = defineEmits(['close-modal'])

const datos = [
    { id: 1, name: 'DNI' },
    { id: 2, name: 'CARNET DE EXTRANJERIA' },
    { id: 3, name: 'OTROS' },
]

const cargos = [
    { id: 1, name: 'ASESOR DE VENTAS' },
    { id: 2, name: 'SUPERVISOR MASIVO AGENCIA' },
    { id: 3, name: 'ASESOR DE VENTAS VOLANTE' },
    { id: 4, name: 'ANALISTA COMERCIAL' },
    { id: 5, name: 'ALMACENERO(A)' },
    { id: 6, name: 'SUPERVISOR DE VENTAS I' },
    { id: 7, name: 'CAJERO (A) PART TIME' },
    { id: 8, name: 'ASESOR MULTISKILL' },
    { id: 9, name: 'ALMACENERO(A) PART TIME' },
    { id: 10, name: 'BACK OFFICE' },
    { id: 11, name: 'CAJERO(A)' },
    { id: 12, name: 'COORDINADOR DE BIENVENIDA' },
    { id: 13, name: 'OP. LIMPIEZA' },
    { id: 14, name: 'ASESOR COMERCIAL' },
    { id: 15, name: 'ANFITRION(A)' },
    { id: 16, name: 'JEFE DE TIENDA I' },
    { id: 17, name: 'ANFITRION (A) PART TIME' },
    { id: 18, name: 'ORIENTADOR' },
    { id: 19, name: 'JEFE DE TIENDA II' },
    { id: 20, name: 'ANALISTA DE SISTEMAS' },
    { id: 21, name: 'ASISTENTE DE VENTAS MASIVO' },
    { id: 22, name: 'ASISTENTE DE COBRANZA' },
    { id: 23, name: 'JEFE DE VENTAS' },
    { id: 24, name: 'GERENTE TERRITORIAL' },
    { id: 25, name: 'ANALISTA DE CONTROL DE ALMACENES' },
    { id: 26, name: 'JEFE TERRITORIAL' },
    { id: 27, name: 'CAPACITADOR (A)' },
    { id: 28, name: 'AUXILIAR DE TIENDA' },
    { id: 29, name: 'SEGURIDAD' },
    { id: 30, name: 'GESTOR VIRTUAL' },
    { id: 31, name: 'COORDINADOR (A) DE RECUPEROS' },
    { id: 32, name: 'RECLUTADOR' },
    { id: 33, name: 'BACK FIJA' },
    { id: 34, name: 'SUPERVISOR FIJA' },
    { id: 35, name: 'SUPERVISOR DE VENTAS II' },
    { id: 36, name: 'JEFE DE CANAL POSTPAGO DE LA AD' },
    { id: 37, name: 'ASISTENTE DE OPERACIONES' },
    { id: 38, name: 'JEFE DE OPERACIONES' },
    { id: 39, name: 'ANALISTA DE VENTAS' },
    { id: 40, name: 'JEFE DE CONTROL DE ALMACENES' },
    { id: 41, name: 'GERENTE REGIONAL' },
    { id: 42, name: 'ANALISTA DE GESTION DE RECLAMOS' },
]


const toTitleCase = (str) => {
    return str.replace(/\w\S*/g, (txt) => {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
};

</script>

<template>
    <form @submit.prevent="submit">
        <div class="bg-white shadow-md rounded-md p-4">
            <div class="text-lg font-bold mb-4">{{ form.id == 0 ? 'Registrar Trabajador' : 'Actualizar Trabajador' }}</div>
            <div class="mb-4">

                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Nombres" />
                        <TextInput class="w-full" v-model="form.name" @input="form.name = toTitleCase(form.name)" />
                        <InputError class="w-full" :message="form.errors.name" />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Apellidos" />
                        <TextInput class="w-full" v-model="form.lastname"
                            @input="form.lastname = toTitleCase(form.lastname)" />
                        <InputError class="w-full" :message="form.errors.lastname" />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Tipo Documento" />
                        <select id="select" v-model="form.document_type"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500">
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option v-for="dato in datos" :key="dato.id" :value="dato.name">{{ dato.name }}</option>
                        </select>
                        <InputError class="w-full" :message="form.errors.document_type" />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Número doc" />
                        <TextInput class="w-full" v-model="form.num_document"
                            @input="form.num_document = form.num_document.toUpperCase()" />
                        <InputError class="w-full" :message="form.errors.num_document" />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Fecha de Ingreso" />
                        <TextInput class="w-full" v-model="form.entry_date"
                            @input="form.entry_date = form.entry_date.toUpperCase()" type="date"/>
                        <InputError class="w-full" :message="form.errors.entry_date" />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Cargo" />
                        <select id="select" v-model="form.charge"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500">
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option v-for="cargo in cargos" :key="cargo.id" :value="cargo.name">{{ cargo.name }}</option>
                        </select>
                        <InputError class="w-full" :message="form.errors.charge" />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Pdv" />
                        <select id="select" v-model="form.pdv_id"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500">
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option v-for="pdv in pdvs" :key="pdv.id" :value="pdv.id">{{ pdv.spot }}</option>
                        </select>
                        <InputError class="w-full" :message="form.errors.pdv_id" />
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Celular" />
                        <TextInput type="number" class="w-full" v-model="form.phone"
                            @input="form.phone = form.phone.toUpperCase()" />
                        <InputError class="w-full" :message="form.errors.lastnphoneame" />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Correo" />
                        <TextInput type="email" class="w-full" v-model="form.email"
                            @input="form.email = form.email.toUpperCase()" />
                        <InputError class="w-full" :message="form.errors.email" />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Dirección" />
                        <TextInput class="w-full" v-model="form.address"
                            @input="form.address = form.address.toUpperCase()" />
                        <InputError class="w-full" :message="form.errors.address" />
                    </div>
                </div>

            </div>
            <div class="flex justify-end">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2" :disabled="form.processing">{{ form.id == 0
                    ? 'Registrar' : 'Actualizar' }}</button>
            </div>
        </div>
    </form>
</template>

<style scoped>
.dropdown-menu {
    max-height: 200px;
    overflow-y: auto;
}</style>
