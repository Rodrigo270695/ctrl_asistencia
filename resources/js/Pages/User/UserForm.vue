<script setup>
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import { useForm } from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({
    user: Object,
    pdvs: Array
})


const form = useForm({
  id: props.user ? props.user.id : '',
  name: props.user ? props.user.name : '',
  email: props.user ? props.user.email : '',
  pdv: props.user ? props.user.pdv.spot : '',
  rol: props.user ? props.user.roles[0].name : '',
})

const submit = () => {
    if (props.user) {
        form.put(route('user.update', props.user.id), {
            preserveScroll: true,
            onSuccess: () => emit('close-modal')
        });
    } else {
        form.post(route('user.store'), {
            preserveScroll: true,
            onSuccess: () => emit('close-modal')
        });
    }
}
const emit = defineEmits(['close-modal'])

const datos = [
    {id: 1, name: 'admin'},
    {id: 2, name: 'editor'},
    {id: 3, name: 'supervisor'},
    {id: 4, name: 'pdv'},
]

</script>
<template>
    <form @submit.prevent="submit">
        <div class="bg-white shadow-md rounded-md p-4">
            <div class="text-lg font-bold mb-4">{{ form.id == 0 ? 'Registrar Usuario': 'Actualizar Usuario'}}</div>
            <div class="mb-4">

                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-4">
                        <InputLabel value="Nombres" />
                        <TextInput class="w-full" v-model="form.name" />
                        <InputError class="w-full" :message="form.errors.name"/>
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Correo" />
                        <TextInput class="w-full" v-model="form.email" />
                        <InputError class="w-full" :message="form.errors.email"/>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="PDV"  />
                        <select id="select"  v-model="form.pdv" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500">
                            <option disabled selected value="">Elija una opción</option>
                            <option v-for="pdv in pdvs" :key="pdv.id" :value="pdv.spot">{{ pdv.spot }}</option>
                        </select>
                        <InputError class="w-full" :message="form.errors.pdv"/>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Rol"  />
                        <select id="select"  v-model="form.rol" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500">
                            <option disabled selected value="">Elija una opción</option>
                            <option v-for="dato in datos" :key="dato.id" :value="dato.name">{{ dato.name }}</option>
                        </select>
                        <InputError class="w-full" :message="form.errors.rol"/>
                    </div>
                </div>

            </div>
            <div class="flex justify-end">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2" :disabled="form.processing">{{ form.id == 0 ? 'Registrar': 'Actualizar'}}</button>
            </div>
        </div>
    </form>
</template>


