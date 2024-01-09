<script setup>
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import TextArea from '@/Components/TextArea.vue'
import InputError from '@/Components/InputError.vue'
import { useForm } from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({
    reason: Object
});

const form = useForm({
  id: props.reason ? props.reason.id : '',
  name: props.reason ? props.reason.name : '',
  description: props.reason ? props.reason.description : '',
})

const submit = () => {
    if (props.reason) {
        form.put(route('reason.update', props.reason.id), {
            preserveScroll: true,
            onSuccess: () => emit('close-modal')
        });
    } else {
        form.post(route('reason.store'), {
            preserveScroll: true,
            onSuccess: () => emit('close-modal')
        });
    }
}

const emit = defineEmits(['close-modal'])


</script>

<template>
    <form @submit.prevent="submit">
        <div class="bg-white shadow-md rounded-md p-4">
            <div class="text-lg font-bold mb-4">{{ form.id == 0 ? 'Registrar Motivo': 'Actualizar Motivo'}}</div>
            <div class="mb-4">

                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-4">
                        <InputLabel value="Nombres" />
                        <TextInput class="w-full" v-model="form.name" @input="form.name = form.name.toUpperCase()" />
                        <InputError class="w-full" :message="form.errors.name"/>
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Descripción" />
                        <TextArea class="w-full" v-model="form.description"  />
                        <InputError class="w-full" :message="form.errors.description"/>
                    </div>
                </div>

            </div>
            <div class="flex justify-end">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2" :disabled="form.processing">{{ form.id == 0 ? 'Registrar': 'Actualizar'}}</button>
            </div>
        </div>
    </form>
</template>
