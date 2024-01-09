<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import CardDashboard from "@/Components/CardDashboard.vue";

const props = defineProps({
    workers: Array,
    pdvs: Array,
    Assists: Array,
    tardinessCount: Number
});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                            <CardDashboard
                                title="Trabajadores"
                                :cantidad="workers.length"
                                icon="ri-user-follow-fill"
                            />
                            <CardDashboard
                                title="PDV's"
                                :cantidad="pdvs.length"
                                icon="la-city-solid"
                            />
                            <CardDashboard
                                title="Asistencias Hoy"
                                :cantidad="Assists.length"
                                icon="bi-check2-square"
                            />
                            <CardDashboard
                                title="Tardanzas Hoy"
                                :cantidad="tardinessCount"
                                icon="bi-calendar2-x"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
