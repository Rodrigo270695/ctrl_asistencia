<script setup>

const props = defineProps({
    title: String,
    cantidad: String,
    icon: String
})

</script>

<template>
    <div class="flex flex-col">
        <!-- Barra de color en la parte superior -->
        <div class="h-1 bg-sky-700 rounded-t-lg "></div>

        <!-- Contenido de la tarjeta -->
        <div class="flex items-center p-4 bg-white rounded-b-lg shadow-xl">
            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-md">
                <v-icon :name="icon" class="w-6 h-6"/>
            </div>
            <div>
                <p class="mb-2 text-sm md:text-lg font-semibold text-gray-600">{{ title }}</p>
                <p class="text-md md:text-lg font-semibold text-gray-700">{{ cantidad }}</p>
            </div>
        </div>
    </div>
</template>
