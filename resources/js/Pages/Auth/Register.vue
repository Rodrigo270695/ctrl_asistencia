<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import NotPermission from '@/Components/NotPermission.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <NotPermission />
</template>
