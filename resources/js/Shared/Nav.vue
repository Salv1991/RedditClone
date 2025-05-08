<script setup>
    import Logo from './Icons/Logo.vue';
    import MenuIcon from './Icons/MenuIcon.vue';
    import { onMounted, onUnmounted, ref, useTemplateRef } from 'vue';
    import SideMenuContent from './SideMenuContent.vue';
    import CreatePostButton from './CreatePostButton.vue';

    const isProfileMenuOpen = ref(false);
    const isSideMenuOpen = ref(false);

    const profileMenu = useTemplateRef('profileMenu');
    const profileButton = useTemplateRef('profileButton');
    const sideMenuButton = useTemplateRef('sideMenuButton');
    const sideMenu = useTemplateRef('sideMenu');

    const openProfileMenu = () => {
        isProfileMenuOpen.value = !isProfileMenuOpen.value;
    }

    const openSideMenu = () => {
        isSideMenuOpen.value = !isSideMenuOpen.value;
    }

    onMounted(() => {
        document.addEventListener('click', handleClickOutside);
    });

    onUnmounted(() => {
        document.removeEventListener('click', handleClickOutside);
    });

    const handleClickOutside = (event) => {
        if(isProfileMenuOpen.value
            && profileMenu.value
            && !profileMenu.value.contains(event.target)
            && !profileButton.value.contains(event.target)){
            isProfileMenuOpen.value = false;
        }

        if(isSideMenuOpen.value
            && sideMenu.value
            && !sideMenu.value.contains(event.target)
            && !sideMenuButton.value.contains(event.target)){
            isSideMenuOpen.value = false;
        }
    };

</script>

<template>
    <nav class="flex justify-between items-center p-4 h-[60px] relative z-50 bg-neutral-900  overscroll-none">
        <div class="flex justify-start items-center gap-6" >

            <!-- SIDE MENU BUTTON -->
            <div ref="sideMenuButton" class="lg:hidden">
                <MenuIcon @click="openSideMenu"/>
            </div>

            <!-- LOGO -->
            <Logo class="w-8"/>
        </div>

        <!-- SIDE MENU -->
         <div class="fixed lg:hidden">
            <div :class="['bg-black/30 fixed opacity-0 left-0 bottom-0 right-0 top-[61] duration-300 transition-opacity', isSideMenuOpen ? 'opacity-100 pointer-events-auto' : 'pointer-events-none']"></div>
            <aside
                ref="sideMenu"
                :class="['fixed top-[61px] left-0 bottom-0 w-[280px] shadow-emerald-400 bg-neutral-900 transition-transform',
                isSideMenuOpen ? 'translate-x-0 duration-500 shadow-md' : '-translate-x-full duration-200']">

                <!-- LINKS -->
               <SideMenuContent />
            </aside>
        </div>

        <!-- PROFILE BUTTON -->
        <div v-if="$page.props.auth" class="flex justify-center items-center gap-4">

            <CreatePostButton />

            <div
                ref="profileButton"
                @click="openProfileMenu"
                class="rounded-full bg-gray-400 border border-transparent hover:border-gray-300 h-9 w-9
                flex justify-center items-center relative cursor-pointer">
                <span>U</span>
                <div :class="[
                    'absolute h-2 w-2 bottom-0 border border-black right-1 rounded-full',
                    $page.props.auth ? 'bg-green-600' : 'bg-gray-500']"></div>
            </div>

            <!-- PROFILE MENU -->
            <ul
                ref="profileMenu"
                v-if="isProfileMenuOpen"
                class="bg-gray-900 border border-gray-800 absolute right-4 top-20 w-[200px]
                rounded-md overflow-hidden">
                <li>
                    <a class="text-sm hover:bg-gray-700 p-2 cursor-pointer block text-center" href="/settings">Setting</a>
                </li>
                <li>
                    <a class="text-sm hover:bg-gray-700 p-2 cursor-pointer block text-center" :href="`/profile/${$page.props.auth?.user.slug}`">View Profile</a>
                </li>
                <li class="">
                    <Link method="post" as="button" :data="{foo: 'bar', 'array': [1,2,4]}" href="/logout" class="w-full text-sm hover:bg-gray-700 p-2 cursor-pointer">Logout</Link>
                </li>
            </ul>
        </div>

        <!-- LOGIN AND SIGNUP BUTTONS -->
        <div v-else class="*:inline-block">
            <a class="text-sm bg-emerald-600 rounded-lg hover:bg-emerald-700 p-2 cursor-pointer block text-center mr-2" href="/login">Login</a>
            <a class="text-sm bg-emerald-600 rounded-lg hover:bg-emerald-700 p-2 cursor-pointer block text-center" href="/signup">Signup</a>
        </div>



    </nav>
</template>
