<script setup>
    import { usePage } from '@inertiajs/vue3';
    import { computed, watch, ref } from 'vue';

    const page = usePage();
    const flashMessage = computed(() => page.props.flash?.message);

    const visibleMessage = ref(null);

    watch(flashMessage, (newMessage) => {
        if(newMessage) {
            visibleMessage.value = newMessage;
            setTimeout(() => {
                visibleMessage.value = null;
            }, 2000);
        }
    })

</script>

<template>
    <div :class="[
            'bg-emerald-500 fixed text-sm right-0 top-20 p-3 rounded-lg w-fit transition-transform duration-300  ease-in-out',
            visibleMessage ? '-translate-x-4' : 'translate-x-full'
        ]">

        {{ flashMessage }}
    </div>
</template>
