<script setup>
import CardDashboard from "@/Components/CardDashboard.vue";

const props = defineProps({
    workes: Array
})

</script>

<template>
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <!-- Repite tu componente CardDashboard para cada tarjeta que quieras mostrar -->
                <CardDashboard title="Trabajadores" :cantidad="workes.length" icon="ri-user-follow-fill" />
                <CardDashboard title="Followers" cantidad="183.000" icon="ri-user-follow-fill" />
                <CardDashboard title="Following" cantidad="80.000" icon="ri-user-follow-fill" />
                <CardDashboard title="Saved Post" cantidad="112" icon="ri-user-follow-fill" />
            </div>
        </div>
    </div>
</template>
