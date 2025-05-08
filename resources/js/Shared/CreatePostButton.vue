<script setup>
    import PlusIcon from './Icons/PlusIcon.vue';
</script>

<template>
     <Link
        as="button"
        method="get"
        href="/profile/post/create"
        class="px-4 py-2 border border-gray-500/70 bg-gray-500/10 rounded-full cursor-pointer
        hover:bg-neutral-800 flex justify-center items-center gap-1 text-sm">
        <span><PlusIcon /></span>
        <span>Create Post</span>
    </Link>
</template>
