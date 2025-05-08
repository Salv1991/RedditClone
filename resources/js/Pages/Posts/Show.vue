<script setup>
import VoteDownIcon from '../../Shared/Icons/VoteDownIcon.vue';
import VoteUpIcon from '../../Shared/Icons/VoteUpIcon.vue';
import CommentsIcon from '../../Shared/Icons/CommentsIcon.vue';
import ThreeHorizontalDotsIcon from '../../Shared/Icons/ThreeHorizontalDotsIcon.vue';
import Comments from '../../Shared/Components/Comments.vue';
import { useForm } from '@inertiajs/vue3';

    const props = defineProps({
        post: Object,
        comments: Array,
        title: String,
    });

    const form = useForm({
        comment: '',
    });

    console.log('COMMENT:', props.post)

    const scrollToComments = () => {
        const commentsSection = document.getElementById('comments');
        if (commentsSection) {
            commentsSection.scrollIntoView({ behavior: 'smooth' });
        }
    };
</script>

<template>
    <article v-if="post">
        <div class="flex justify-between items-center">
            <div>
                <span class="text-xs text-gray-300 mr-1">{{ post.username ?? 'Unknown user'}}</span>
                <span class="text-xs text-gray-500">• {{ post.created_at }}</span>
            </div>
            <div>
                <ThreeHorizontalDotsIcon class="cursor-pointer bg-neutral-800 hover:bg-neutral-700 rounded-full"/>
            </div>
        </div>

        <!-- TITLE AND CONTENT -->
        <div class="mt-2">
            <h2 class="text-3xl">{{ post.title }}</h2>
            <p class="mt-2 text-gray-300 text-sm whitespace-pre-line">{{ post.text }}</p>
        </div>

        <!-- VOTES AND COMMENTS BUTTON -->
        <div class="mt-8 flex justify-start items-center gap-2">
            <div :class="[' w-fit gap-2 flex justify-center items-center rounded-full',
                    {
                        'bg-red-500' : post.user_vote === 'dislike',
                        'bg-green-700' : post.user_vote === 'like',
                        'bg-neutral-800' : post.user_vote == null,
                    }
                ]">
                <Link preserve-scroll method="post" as="button" :href="`/post/${post.id}/upvote`" class="">
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
            <a @click.prevent="scrollToComments" href="#comments" class="bg-neutral-800 w-fit flex justify-center items-center rounded-full hover:bg-neutral-600 cursor-pointer">
                <CommentsIcon class="rounded-full"/>
                <span class="pr-3">{{  post.total_comments }}</span>
            </a>
        </div>

        <!-- COMMENT INPUT-->
        <form method="post" @submit.prevent="form.post(`/post/${post.id}/comment`, {
            preserveScroll: true,
            onSuccess: () => form.reset('comment')
        })">
            <input
                v-model="form.comment"
                placeholder="Add a comment"
                class="mt-4 border outline-none focus:border-gray-400 border-gray-400/50
                rounded-full py-2 px-4 placeholder:text-sm w-full" />
        </form>

        <!-- COMMENT SECTION -->
        <div id="comments" class="min-h-[600px] mt-4">
            <div class="space-y-4">
                <Comments :comments="comments" />
            </div>
        </div>
    </article>
</template>
