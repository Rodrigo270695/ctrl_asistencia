<script setup>
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import { useForm } from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({
    pdv: Object
});

const form = useForm({
  id: props.pdv ? props.pdv.id : '',
  unit: props.pdv ? props.pdv.unit : '',
  spot: props.pdv ? props.pdv.spot : '',
  address: props.pdv ? props.pdv.address : '',
})

const submit = () => {
    if (props.pdv) {
        form.put(route('pdv.update', props.pdv.id), {
            preserveScroll: true,
            onSuccess: () => emit('close-modal')
        });
    } else {
        form.post(route('pdv.store'), {
            preserveScroll: true,
            onSuccess: () => emit('close-modal')
        });
    }
}

const emit = defineEmits(['close-modal'])

const datos = [
    {id: 1, name: 'DISTRIBUIDORA'},
    {id: 2, name: 'FRANQUICIA'},
    {id: 3, name: 'DAM'},
]

</script>

<template>
    <form @submit.prevent="submit">
        <div class="bg-white shadow-md rounded-md p-4">
            <div class="text-lg font-bold mb-4">{{ form.id == 0 ? 'Registrar Pdv': 'Actualizar Pdv'}}</div>
            <div class="mb-4">

                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Unidad de negocio"  />
                        <select id="select"  v-model="form.unit" class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500">
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option v-for="dato in datos" :key="dato.id" :value="dato.name">{{ dato.name }}</option>
                        </select>
                        <InputError class="w-full" :message="form.errors.unit"/>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Pdv" />
                        <TextInput class="w-full" v-model="form.spot" @input="form.spot = form.spot.toUpperCase()" />
                        <InputError class="w-full" :message="form.errors.spot"/>
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Dirección" />
                        <TextInput class="w-full" v-model="form.address" @input="form.address = form.address.toUpperCase()" />
                        <InputError class="w-full" :message="form.errors.address"/>
                    </div>
                </div>

            </div>
            <div class="flex justify-end">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2" :disabled="form.processing">{{ form.id == 0 ? 'Registrar': 'Actualizar'}}</button>
            </div>
        </div>
    </form>
</template>
