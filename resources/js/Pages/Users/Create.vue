<script setup>
import { useForm } from '@inertiajs/vue3';

    let form = useForm({
        name: '',
        email: '',
        password: '',
    })

    const submit = () => {
        form.post('/users');
    }
</script>

<template>
    Create

    <form @submit.prevent="submit"  method="post" action="/users">
        <label for="">Name</label>
        <input type="text" name="name" placeholder="Name" v-model="form.name">
        <div class="text-xs text-red-500" v-if="form.errors.name">{{ form.errors.name }}</div>

        <label for="">Email</label>
        <input type="email" name="email" placeholder="Email" v-model="form.email">
        <div class="text-xs text-red-500" v-if="form.errors.email">{{ form.errors.email }}</div>

        <label for="">Password</label>
        <input type="password" name="password" placeholder="Passeword" v-model="form.password">
        <div class="text-xs text-red-500" v-if="form.errors.password">{{ form.errors.password }}</div>

        <button type="submit" :disabled="form.processing">Create User</button>
    </form>
</template>
