<script setup>
    defineProps({
        isActive: {
            type: Boolean,
            default: false,
        }
    })
</script>

<template>
   <Link :class="[
        'p-3 text-sm cursor-pointer',
        {
            'bg-gray-500/20 rounded-full': isActive,
            '': !isActive
        }]">
        <slot/>
    </Link>
</template>
