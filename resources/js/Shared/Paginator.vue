<script setup>
    const props = defineProps({
        links: Array
    })
</script>

<template>
    <ul class="flex justify-center items-center gap-1">
        <li v-for="(link) in links">
            <Component
                :is="link.url ? 'Link' : 'span'"
                :href="link.url"
                v-html="link.label"
                preserve-scroll
                :class="[
                    link.active ? 'bg-gray-400/20 text-emerald-500' : '',
                    link.url
                        ? 'py-2 px-3 hover:bg-gray-400/20'
                        : 'text-gray-700 cursor-default',
                    'py-2 px-3 duration-200 transition-colors rounded-md',
                ]"
            />
        </li>
    </ul>
</template>
