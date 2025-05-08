<script setup>
    import Message from './Message.vue';
</script>

<template>
    <section class="mx-auto ml-0 lg:ml-[280px] flex-1 px-8 py-4 bg-neutral-950 h-auto overscroll-y-auto">
        <Message />
        <slot />
    </section>
</template>
