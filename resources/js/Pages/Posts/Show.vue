<script setup>
import VoteDownIcon from '../../Shared/Icons/VoteDownIcon.vue';
import VoteUpIcon from '../../Shared/Icons/VoteUpIcon.vue';
import CommentsIcon from '../../Shared/Icons/CommentsIcon.vue';
import ThreeHorizontalDotsIcon from '../../Shared/Icons/ThreeHorizontalDotsIcon.vue';
import Comment from '../../Shared/Components/Comment.vue';

    const props = defineProps({
        post: Object,
        comments: Array,
        title: String,
    });

    console.log('COMMENT:', props.comments)

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
            <p class="mt-2 text-gray-300 text-sm">{{ post.text }}</p>
        </div>

        <!-- VOTES AND COMMENTS BUTTON -->
        <div class="mt-8 flex justify-start items-center gap-2">
            <div :class="[' w-fit gap-2 flex justify-center items-center rounded-full',
                    {
                        'bg-red-500' : post.userVote === 'dislike',
                        'bg-green-700' : post.userVote === 'like',
                        'bg-neutral-800' : post.userVote == null,
                    }
                ]">
                <Link preserve-scroll method="post" as="button" :href="`/post/${post.id}/upvote`" class="">
                    <VoteUpIcon :class="['rounded-full bg-transparent  hover:bg-neutral-700/40 cursor-pointer ',
                        {
                            'hover:text-white': post.userVote === 'dislike' || post.userVote === 'like',
                            'hover:text-green-700': post.userVote === null,
                        }
                    ]"/>
                </Link>

                <span class="block min-w-2.5">{{ post.votesCount }}</span>

                <Link preserve-scroll method="post" as="button" :href="`/post/${post.id}/downvote`" class="">
                    <VoteDownIcon :class="['rounded-full bg-transparent  hover:bg-neutral-700/40 cursor-pointer ',
                        {
                            'hover:text-white': post.userVote === 'dislike' || post.userVote === 'like',
                            'hover:text-red-500': post.userVote === null,
                        }
                    ]"/>
                </Link>
            </div>
            <a @click.prevent="scrollToComments" href="#comments" class="bg-neutral-800 w-fit flex justify-center items-center rounded-full hover:bg-neutral-600 cursor-pointer">
                <CommentsIcon class="rounded-full"/>
                <span class="pr-3">{{  post.commentsCount }}</span>
            </a>
        </div>

        <!-- SEARCH COMMENT -->
        <input placeholder="Add a comment" class="mt-4 border outline-none focus:border-gray-400 border-gray-400/50 rounded-full py-2 px-4 placeholder:text-sm w-full" />

        <!-- COMMENT SECTION -->
        <div id="comments" class="min-h-[600px]">
            <h2 class="mt-8 underline underline-offset-4">Comments</h2>
            <div class="mt-4 space-y-4">
                <Comment v-for="comment in comments" :comment="comment" />
            </div>
        </div>
    </article>
</template>
