<script setup>
    import NavLink from './NavLink.vue';
    import MenuIcon from './Icons/MenuIcon.vue';
    import HomeIcon from './Icons/HomeIcon.vue';
    import HomeIconActive from './Icons/HomeActiveIcon.vue';
    import UserIcon from './Icons/UserIcon.vue';
    import UserActiveIcon from './Icons/UserActiveIcon.vue';
    import PopularIcon from './Icons/PopularIcon.vue';
    import UserPostsIcon from './Icons/UserPostsIcon.vue';
    import UserPostsActiveIcon from './Icons/UserPostsActiveIcon.vue';
</script>

<template>
    <section class="px-4 *:py-4">
     <ul class="flex flex-col justify-start items-start *:w-full">
        <li>
            <NavLink href="/" :isActive="$page.component === 'Home'">
                <HomeIconActive v-if="$page.component === 'Home'" class="inline"/>
                <HomeIcon v-else class="inline"/>
                <span class="ml-3">Home</span>
            </NavLink>
        </li>
        <li>
            <NavLink href="/popular" :isActive="$page.component === 'Posts/Index' && $page.props.postType === 'popular'">
                <PopularIcon class="inline"/>
                <span class="ml-3">Popular</span>
            </NavLink>
        </li>
        <li>
            <NavLink href="/users" :isActive="$page.component === 'Users/Index'">
                <UserActiveIcon v-if="$page.component === 'Users/Index'" class="inline"/>
                <UserIcon v-else class="inline"/>
                <span class="ml-3">Users</span>
            </NavLink>
        </li>
        <li>
            <NavLink href="/settings" :isActive="$page.component === 'Settings'">
                <MenuIcon class="inline"/>
                <span class="ml-3">Settings</span>
            </NavLink>
        </li>
    </ul>

    <ul class="flex flex-col justify-start items-start *:w-full border-t border-t-gray-700">
        <li class="">
            <NavLink href="/user-posts" :isActive="$page.component === 'Posts/Index' && $page.props.postType === 'default'">
                <UserPostsActiveIcon v-if="$page.component === 'Posts/Index' && $page.props.postType === 'default'" class="inline"/>
                <UserPostsIcon v-else class="inline"/>
                <span class="ml-3">My Posts</span>
            </NavLink>
        </li>
        <!-- <p class="text-xs text-gray-400">Hello, {{ $page.props.auth?.user.username ?? 'Guest' }}</p> -->
    </ul>
</section>
</template>
