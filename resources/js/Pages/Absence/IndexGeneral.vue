<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import NavLink from '@/Components/NavLink.vue';



</script>

<template>
    <AppLayout title="Inasistencias">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestionar Inasistencias
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex divide-x p-3 divide-gray-300 shadow-md">
                        <NavLink  :href="route('abs.index')" :active="route().current('abs.*')">
                            Inasistencias
                        </NavLink>
                        <NavLink v-if="$page.props.user.roles.includes('admin') | $page.props.user.roles.includes('editor')" :href="route('reason.index')" :active="route().current('reason.*')">
                            Motivos
                        </NavLink>
                    </div>
                    <div class="tab-content p-4">
                        <slot name="contentAbs"/>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
