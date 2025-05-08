<script setup>
    import { useForm } from '@inertiajs/vue3';

    const props = defineProps({

    });

    const form = useForm({
        title: null,
        text: null,
     })

</script>

<template>
    <h1 class="text-2xl mb-4">Create Post</h1>
   <form @submit.prevent="form.post('/profile/post/store')" class="w-full">
        <div class="mb-3">
            <input
                id="title"
                type="text"
                class="border border-gray-600/50 rounded-2xl p-3 w-full
                invalid:border-red-400 focus:border-emerald-600 outline-none mb-1"
                placeholder="Title"
                v-model="form.title">
                <span class="text-red-600 text-xs" v-if="form.errors.title">{{ form.errors.title }}</span>
        </div>

        <div class="mb-3">
            <textarea
                id="body"
                rows="6"
                class="border border-gray-600/50 rounded-2xl p-3 w-full
                invalid:border-red-400 focus:border-emerald-600 outline-none mb-1"
                v-model="form.text"
                placeholder="Text"></textarea>
            <span class="text-red-600 text-xs p-1" v-if="form.errors.text">{{ form.errors.text }}</span>
        </div>

        <button
            type="submit"
            class="bg-emerald-500 hover:bg-emerald-600 rounded-full px-5 py-2 mt-4
            block ml-auto">
            Post
        </button>
   </form>
</template>
