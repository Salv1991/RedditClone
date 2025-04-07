<script setup>
    defineProps({
        isActive: Boolean,
    })
</script>

<template>
    <Link class="text-white px-6 py-2 text-sm w-full block rounded-lg" :class="{'bg-white/10': isActive, 'hover:bg-white/5': !isActive}">
        <slot></slot>
    </Link>
</template>
