<script setup>
import VoteCommentUpIcon from '../../Shared/Icons/VoteCommentUpIcon.vue';
import VoteCommentDownIcon from '../../Shared/Icons/VoteCommentDownIcon.vue';
import CommentsIcon from '../../Shared/Icons/CommentsIcon.vue';

    const props = defineProps({
        comments: Array,
    });

</script>

<template>
    <div id="comments" class="min-h-[600px]">
        <h2 class="mt-8 underline underline-offset-4">Comments</h2>

        <div v-if="comments.length"  v-for="comment in comments" :key="comment.id" class="px-4 hover:bg-gray-800/50 rounded-lg p-2 mt-4">
            <div>
                <a href="#" class="text-xs text-gray-300 mr-1">{{ comment.commenter_username ?? 'Unknown user'}}</a>
                <span class="text-xs text-gray-500">• {{ comment.created_at }}</span>
            </div>

            <div class="mt-2">
                <p class="mt-2 text-gray-400 text-xs">{{ comment.text }}</p>
            </div>

            <div class="mt-2 flex justify-start items-center gap-2">
                <div :class="[' w-fit gap-2 flex justify-center items-center rounded-full',
                    ]">
                    <Link preserve-scroll method="post" as="button" :href="`/comment/${comment.id}/upvote`" class="">
                        <VoteCommentUpIcon :class="[
                            'rounded-full bg-transparent hover:bg-neutral-700/40 cursor-pointer ',
                            {'text-green-600': comment.user_vote === 'like'}
                        ]"/>
                    </Link>

                    <span class="block min-w-2.5">{{ comment.total_votes }}</span>

                    <Link preserve-scroll method="post" as="button" :href="`/comment/${comment.id}/downvote`" class="">
                        <VoteCommentDownIcon :class="[
                            'rounded-full bg-transparent  hover:bg-neutral-700/40 cursor-pointer ',
                            {'text-red-500': comment.user_vote === 'dislike'}
                        ]"/>
                    </Link>
                </div>
                <a @click.prevent="scrollToComments" href="#comments" class="bg-neutral-800 w-fit flex justify-center items-center rounded-full hover:bg-neutral-600 cursor-pointer">
                    <CommentsIcon class="rounded-full"/>
                    <span class="pr-3 text-xs">Reply</span>
                </a>
            </div>
        </div>

        <div v-else class="bg-neutral-900 min-h-60 sm:min-h-80 text-gray-400 flex justify-center items-center border border-neutral-700 rounded-lg mt-4">
            No comments
        </div>
    </div>
</template>
