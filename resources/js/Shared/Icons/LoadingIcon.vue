<script setup></script>

<template>
    <img src="../../../png/loading-icon.png" class="spinner" alt="Loading">
</template>

<style scoped>
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .spinner {
        animation: spin 1s linear infinite;
    }
</style>
