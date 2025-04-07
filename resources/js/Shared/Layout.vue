<script setup>
    import Header from './Header.vue';
    import { Head } from '@inertiajs/vue3';
    import SideMenuContent from './SideMenuContent.vue';
import LayoutSection from './LayoutSection.vue';
</script>

<template>
    <Head title="Reddit Clone">
        <meta type="description" content="Information about app" head-key="description">
    </Head>

    <Header class="sticky top-0  overscroll-none"/>

    <section class="flex justify-start min-h-full">

        <!-- SIDE MENU ON LARGE SCREENS -->
        <aside class="h-auto hidden fixed left-0 bottom-0 top-[62px] lg:block w-[280px] border-r border-gray-700/80
        bg-neutral-900 transition-transform">
            <!-- LINKS -->
            <SideMenuContent />
        </aside>

        <LayoutSection>
            <slot />
        </LayoutSection>
    </section>
</template>
