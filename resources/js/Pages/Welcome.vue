<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Bienvenido" />

    <nav class="bg-cyan-950 shadow-md">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <!-- Logo en el lado izquierdo -->
            <a href="/">
                <div class="flex items-center">
                    <img src="/logo_macga.png" alt="Macga Telecomunicaciones" width="50" />
                    <p class="ml-3 text-white text-sm md:text-lg font-semibold">
                        Grupo Maclabi
                    </p>
                </div>
            </a>

            <!-- Enlaces de navegación en el lado derecho -->
            <div class="flex items-center">
                <template v-if="canLogin">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                        class="font-semibold text-white hover:text-gray-300 focus:outline focus:outline-2 focus:rounded-sm">
                    Dashboard</Link>
                    <template v-else>
                        <Link :href="route('login')"
                            class="font-semibold text-white hover:text-gray-300 focus:outline focus:outline-2 focus:rounded-sm">
                        Iniciar Sesión</Link>
                    </template>
                </template>
            </div>
        </div>
    </nav>

    <div
        class="relative sm:flex sm:justify-center sm:items-center h-full w-full bg-blue-100 selection:bg-blue-950 selection:text-white">
        <div class="min-h-screen bg-white flex flex-col justify-center items-center p-4">
            <!-- Sección de Misión y Visión -->
            <div
                class="flex flex-col md:flex-row justify-between items-center text-center md:text-left bg-sky-800 text-white">
                <!-- Visión -->
                <div class="flex-1 flex flex-col md:flex-row items-center md:pl-10 py-4 px-3">
                    <v-icon name="md-visibility-round" scale="3" class="h-16 w-16 mt-4 md:mt-0 md:ml-0 mr-4" />
                    <div>
                        <h2 class="text-2xl font-bold mb-2">Visión</h2>
                        <p>
                            Ser reconocidos como líderes en el mercado de
                            telecomunicaciones, expandiendo nuestra presencia a
                            nivel nacional e internacional, y siendo sinónimo de
                            calidad, innovación y confianza.
                        </p>
                    </div>
                </div>
                <!-- Misión -->
                <div class="flex-1 flex flex-col md:flex-row items-center md:pr-10 py-4 bg-gray-500 px-3">
                    <div class="md:flex-1">
                        <h2 class="text-2xl font-bold mb-2">Misión</h2>
                        <p>
                            Proveer soluciones de telecomunicaciones innovadoras
                            que conecten y enriquezcan la vida de nuestros
                            clientes, manteniendo un compromiso firme con la
                            excelencia en el servicio y la satisfacción del
                            cliente.
                        </p>
                    </div>
                    <v-icon name="si-target" scale="3" class="h-16 w-16 mt-4 md:mt-0 md:ml-4" />
                </div>
            </div>

            <!-- Sección de Valores -->
            <div class="flex flex-col items-center justify-center w-full my-10">
                <h3 class="text-2xl font-bold mb-6 text-center">
                    VALORES QUE NOS DEFINEN
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Conectividad -->
                    <div class="flex flex-col items-center p-4 bg-gray-100 rounded-lg shadow">
                        <v-icon name="fc-mind-map" scale="3" class="h-12 w-12 mb-3" />
                        <h4 class="font-semibold mb-2">CONECTIVIDAD</h4>
                        <p class="text-sm text-gray-600 text-center">
                            Unimos personas y empresas con soluciones de
                            comunicación de vanguardia, garantizando una
                            conectividad sin interrupciones.
                        </p>
                    </div>
                    <!-- Servicio al Cliente -->
                    <div class="flex flex-col items-center p-4 bg-gray-100 rounded-lg shadow">
                        <v-icon name="fc-services" scale="3" class="h-12 w-12 mb-3" />
                        <h4 class="font-semibold mb-2">SERVICIO AL CLIENTE</h4>
                        <p class="text-sm text-gray-600 text-center">
                            Nos dedicamos a ofrecer una experiencia excepcional
                            al cliente, con un enfoque personalizado y
                            soluciones rápidas a sus necesidades.
                        </p>
                    </div>
                    <!-- Innovación -->
                    <div class="flex flex-col items-center p-4 bg-gray-100 rounded-lg shadow">
                        <v-icon name="vi-file-type-red" scale="3" class="h-12 w-12 mb-3" />
                        <h4 class="font-semibold mb-2">INNOVACIÓN</h4>
                        <p class="text-sm text-gray-600 text-center">
                            Impulsamos el avance tecnológico a través de la
                            investigación y el desarrollo de nuevos servicios y
                            productos que anticipan el futuro de las
                            telecomunicaciones.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center py-3 bg-sky-950 mt-10 text-white">
        <p>
            &copy; {{ new Date().getFullYear() }} Macga - Autor: Rodrigo Granja
        </p>
    </footer>
</template>
