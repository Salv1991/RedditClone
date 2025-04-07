<script setup>
    import Paginator from '../../Shared/Paginator.vue';
    import { ref, watch } from 'vue';
    import { router } from '@inertiajs/vue3'
    import debounce from "lodash/debounce";

    const props = defineProps({
        time: String,
        users:Object,
        filters: Object,
    });

    const search = ref(props.filters.search);
    watch(search, debounce(function (value) {
        router.get('/users', { search: value }, { preserveState: true, replace: true }, 500);
    }, 400));

</script>

<template>
    <Head>
        <title>Users</title>
    </Head>
    <div class="flex justify-between items-center">
        <p>Users</p>

        <!-- Search -->
        <input placeholder="Search..." v-model="search" >
    </div>

    <!-- Users -->
    <ul>
        <li v-for="user in users.data" :key="user.id" v-text="user.name"></li>
    </ul>

    <!-- Paginator-->
    <Paginator :links="users.links" />
</template>

