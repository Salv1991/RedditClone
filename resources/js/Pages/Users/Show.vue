<script setup>
    import { router, usePage } from '@inertiajs/vue3';
    import { onMounted, onUnmounted, ref, useTemplateRef } from 'vue';
    import NavLink from '../../Shared/Components/User/NavLink.vue';
    import VoteDownIcon from '../../Shared/Icons/VoteDownIcon.vue';
    import VoteUpIcon from '../../Shared/Icons/VoteUpIcon.vue';
    import CommentsIcon from '../../Shared/Icons/CommentsIcon.vue';
    import ThreeHorizontalDotsIcon from '../../Shared/Icons/ThreeHorizontalDotsIcon.vue';
    import ArrowDownIcon from '../../Shared/Icons/ArrowDownIcon.vue';
    import LoadingIcon from '../../Shared/Icons/LoadingIcon.vue';
    import Paginator from '../../Shared/Paginator.vue';
    import CreatePostButton from '../../Shared/CreatePostButton.vue';

    const props = defineProps({
        data: Array,
        user: Array,
        links: Object,

    })

    const page = usePage();
    const username = props.user.name;
    const slug = props.user.slug;
    const isMenuOpen = ref(false);
    const sortOptionsMenu = useTemplateRef('sortOptionsMenu');
    const sortOptionsButton = useTemplateRef('sortOptionsButton');
    const isLoading = ref(false);
    console.log(props.links);
    const changeSort = (value) => {
        console.log(value)

        const array = ['new', 'hot', 'top'];

        if(!array.includes(value)){
            value = 'new';
        }

        isLoading.value = true;

        router.get(`/profile/${slug}/${page.props.pageType}`,
            {sort: value},
            {
                preserveScroll: true,
                preserveState: true,
                onFinish: () => isLoading.value = false,
            }
        );
    }

    onMounted(() => {
        document.addEventListener('click', handleClickOutside);
    })

    onUnmounted(() => {
        document.removeEventListener('click', handleClickOutside);
    })

    const openMenu = () => {
        isMenuOpen.value = !isMenuOpen.value;
    }

    const handleClickOutside = (event) => {
        if(sortOptionsMenu.value && !sortOptionsButton.value.contains(event.target)){
            isMenuOpen.value = false;
        };
    }

</script>

<template>
    <div>
        <div class="text-2xl">{{username}}</div>
        <div class="text-md text-gray-500">@{{slug}}</div>
    </div>

    <div class="mt-8">
        <ul class="flex justify-start items-center gap-1">
            <li><NavLink :href="`/profile/${slug}/submitted`" :isActive="page.props.pageType === 'submitted'">Posts</NavLink></li>
            <li><NavLink :href="`/profile/${slug}/comments`" :isActive="page.props.pageType === 'comments'">Comments</NavLink></li>
            <li><NavLink :href="`/profile/${slug}/saved`" :isActive="page.props.pageType === 'saved'">Saved</NavLink></li>
        </ul>

        <div class="flex justify-start items-center gap-2 w-full border-b-gray-100/10 border-b mt-6 py-2">
            <CreatePostButton v-if="$page.props.auth.user.id === props.user.id  && $page.props.pageType === 'submitted'"/>

            <div
                @click="openMenu"
                ref="sortOptionsButton"
                :class="['relative cursor-pointer px-4 py-2 hover:bg-neutral-800 w-fit rounded-full flex justify-start items-center gap-1 hover:text-gray-200 text-gray-400',
                    {'bg-neutral-800': isMenuOpen}
                ]">
                <span class="text-sm ">{{page.props.sort}}</span>
                <span><ArrowDownIcon/></span>

                <!-- SORT OPTIONS MENU -->
                <div
                    ref="sortOptionsMenu"
                    class="absolute top-14 left-0 bg-neutral-800 flex
                    flex-col justify-start items-center rounded-xl overflow-hidden w-fit *:py-3 *:px-6 *:w-full
                    transition-all duration-200 origin-top *:cursor-pointer"
                    :class="{
                        'scale-y-0': !isMenuOpen,
                        'scale-y-100': isMenuOpen,
                    }">
                    <a @click.prevent="changeSort('new')"
                        class="hover:bg-neutral-700 text-sm inline"
                        :class="{
                            'text-emerald-500': page.props.sort==='New',
                            'text-gray-200': page.props.sort!=='New'
                        }">
                        New
                    </a>

                    <a @click.prevent="changeSort('hot')"
                        class="hover:bg-neutral-700 text-sm inline"
                        :class="{
                            'text-emerald-500': page.props.sort==='Hot',
                            'text-gray-200': page.props.sort!=='Hot'
                        }">
                        Hot
                    </a>

                    <a @click.prevent="changeSort('top')"
                        class="hover:bg-neutral-700 text-sm inline"
                        :class="{
                            'text-emerald-500': page.props.sort==='Top',
                            'text-gray-200': page.props.sort!=='Top'
                        }">
                        Top
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <div v-if="isLoading" class=" fixed inset-0 min-h-40 md:min-h-96 flex justify-center items-center">
                <LoadingIcon class="h-10"/>
            </div>
            <!-- POSTS -->
            <div v-if="$page.props.pageType === 'submitted'" class="divide-y-1 divide-gray-600/50">
                <article v-for="post in data" :key="post.id">
                    <a :href="`/${post.post_author_slug}/post/${post.id}`" class="block hover:bg-neutral-800/50 rounded-lg p-4 my-2">
                        <div class="flex justify-between items-center">
                            <div>
                                <a :href="`/profile/${post.post_author_slug}`"
                                    class="text-xs text-gray-300 mr-1 hover:text-emerald-500">
                                        {{ post.post_author_username ?? 'Unknown user'}}
                                </a>
                                <span class="text-xs text-gray-500">• {{ post.created_at }}</span>
                            </div>
                            <div>
                                <ThreeHorizontalDotsIcon class="cursor-pointer bg-neutral-800 hover:bg-neutral-700 rounded-full"/>
                            </div>
                        </div>
                        <div class="mt-2">
                            <h2 class="text-xl">{{ post.title }}</h2>
                            <p class="mt-1 text-gray-300 text-sm whitespace-pre-line">{{ post.text }}</p>
                        </div>
                        <div class="mt-4 flex justify-start items-center gap-2">
                            <div :class="[' w-fit gap-2 flex justify-center items-center rounded-full', {
                                        'bg-red-500' : post.user_vote === 'dislike',
                                        'bg-green-700' : post.user_vote === 'like',
                                        'bg-neutral-800' : post.user_vote == null }
                                ]">

                                <Link
                                    preserve-scroll
                                    method="post"
                                    as="button"
                                    :href="`/post/${post.id}/upvote`">
                                    <VoteUpIcon :class="['rounded-full bg-transparent  hover:bg-neutral-700/40 cursor-pointer ',
                                        {
                                            'hover:text-white': post.user_vote === 'dislike' || post.user_vote === 'like',
                                            'hover:text-green-700': post.user_vote === null,
                                        }
                                    ]"/>
                                </Link>

                                <span class="block min-w-2.5">{{ post.total_votes }}</span>

                                <Link preserve-scroll method="post" as="button" :href="`/post/${post.id}/downvote`" class="">
                                    <VoteDownIcon :class="['rounded-full bg-transparent  hover:bg-neutral-700/40 cursor-pointer ',
                                        {
                                            'hover:text-white': post.user_vote === 'dislike' || post.user_vote === 'like',
                                            'hover:text-red-500': post.user_vote === null,
                                        }
                                    ]"/>
                                </Link>
                            </div>
                            <div class="bg-neutral-800 w-fit flex justify-center items-center rounded-full hover:bg-neutral-600 cursor-pointer">
                                <CommentsIcon class="rounded-full"/>
                                <span class="pr-3">{{ post.total_comments }}</span>
                            </div>
                        </div>
                    </a>
                </article>
            </div>

            <!-- COMMENTS -->
            <div v-if="$page.props.pageType === 'comments'" class="divide-y-1 divide-gray-600/50">
                <article v-for="comment in data" :key="comment.id">
                    <a :href="`/${comment.post_author_slug}/post/${comment.post_id}`" class="block hover:bg-neutral-800/50 rounded-lg p-4 my-2">
                        <div class="flex justify-between items-center">
                            <div>
                                <a :href="`/profile/${comment.post_author_slug}`" class="text-xs text-gray-300 mr-1 hover:text-emerald-500">{{ comment.post_author_username ?? 'Unknown user'}}</a>
                                <a :href="`/${comment.post_author_slug}/post/${comment.post_id}`" class="text-xs text-gray-500 hover:text-emerald-500"> • {{ comment.post_title }}</a>
                            </div>
                            <div>
                                <ThreeHorizontalDotsIcon class="cursor-pointer bg-neutral-800 hover:bg-neutral-700 rounded-full"/>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="*:inline">
                                <a :href="`/profile/${comment.commenter_slug}`" class="text-sm hover:text-emerald-500 font-bold">{{ comment.commenter_username }}</a>
                                <span class="text-xs text-gray-500"> • commented {{ comment.created_at }}</span>
                            </div>
                            <p class="mt-1 text-gray-300 text-sm">{{ comment.text }}</p>
                        </div>
                        <div class="mt-4 flex justify-start items-center gap-2">
                            <div :class="[' w-fit gap-2 flex justify-center items-center rounded-full',
                                    {
                                        'bg-red-500' : comment.user_vote === 'dislike',
                                        'bg-green-700' : comment.user_vote === 'like',
                                        'bg-neutral-800' : comment.user_vote == null,
                                    }
                                ]">
                                <Link preserve-scroll method="post" as="button" :href="`/comment/${comment.id}/upvote`">
                                    <VoteUpIcon :class="['rounded-full bg-transparent  hover:bg-neutral-700/40 cursor-pointer ',
                                        {
                                            'hover:text-white': comment.user_vote === 'dislike' || comment.user_vote === 'like',
                                            'hover:text-green-700': comment.user_vote === null,
                                        }
                                    ]"/>
                                </Link>

                                <span class="block min-w-2.5">{{ comment.total_votes }}</span>

                                <Link preserve-scroll method="post" as="button" :href="`/comment/${comment.id}/downvote`">
                                    <VoteDownIcon :class="['rounded-full bg-transparent  hover:bg-neutral-700/40 cursor-pointer ',
                                        {
                                            'hover:text-white': comment.user_vote === 'dislike' || comment.user_vote === 'like',
                                            'hover:text-red-500': comment.user_vote === null,
                                        }
                                    ]"/>
                                </Link>
                            </div>
                            <div class="bg-neutral-800 w-fit flex justify-center items-center rounded-full hover:bg-neutral-600 cursor-pointer">
                                <CommentsIcon class="rounded-full"/>
                                <span class="pr-3">Reply</span>
                            </div>
                        </div>
                    </a>
                </article>
            </div>

            <Paginator v-if="props.links.length > 3" :links="props.links" class="py-8"/>

            <!-- SAVED POSTS -->
        </div>
    </div>
</template>
