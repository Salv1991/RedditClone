<template>
    <p>Hello worlds {{ name }}</p>
</template>

<script setup>
    defineProps({
        name: {
            type: String,
            default: 'Apostolos'
        },
    })
</script>