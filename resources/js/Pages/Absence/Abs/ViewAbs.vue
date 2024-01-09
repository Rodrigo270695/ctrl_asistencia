<script lang="ts" setup>

const props = defineProps({
    absence: Object
})

</script>

<template>
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-2">Detalles de la Ausencia</h2>
        <p><strong>ID:</strong> {{ absence.id }}</p>
        <p><strong>Fecha de inicio:</strong> {{ new Date(absence.start_date + 'T00:00:00').toLocaleDateString('es-PE') }}</p>
        <p><strong>Fecha de finalización:</strong> {{ new Date(absence.end_date + 'T00:00:00').toLocaleDateString('es-PE') }}</p>
        <p><strong>La razón:</strong> {{ absence.reason.name }}</p>
        <p><strong>Archivo:</strong> {{ absence.file }}</p>
        <p><strong>Trabajador:</strong> {{ absence.worker.name }} {{ absence.worker.lastname }}</p>
        <p><strong>Estado:</strong> {{ absence.status }}</p>
        <p><strong>Detalle del estado:</strong> {{ absence.status_detail }}</p>
    </div>
</template>

