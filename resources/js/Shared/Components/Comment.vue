<script setup>
import VoteCommentUpIcon from '../../Shared/Icons/VoteCommentUpIcon.vue';
import VoteCommentDownIcon from '../../Shared/Icons/VoteCommentDownIcon.vue';
import CommentsIcon from '../../Shared/Icons/CommentsIcon.vue';

    const props = defineProps({
        comment: Object,
    });

    console.log('COMMENT COMP',props.comments);
</script>

<template>
    <div :key="comment.id" class="px-4 hover:bg-gray-800/50 rounded-lg p-2">
        <div>
            <a href="#" class="text-xs text-gray-300 mr-1">{{ comment.username ?? 'Unknown user'}}</a>
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
                        {'text-green-600': comment.userVote === 'like'}
                    ]"/>
                </Link>

                <span class="block min-w-2.5">{{ comment.votesCount }}</span>

                <Link preserve-scroll method="post" as="button" :href="`/comment/${comment.id}/downvote`" class="">
                    <VoteCommentDownIcon :class="[
                        'rounded-full bg-transparent  hover:bg-neutral-700/40 cursor-pointer ',
                        {'text-red-500': comment.userVote === 'dislike'}
                    ]"/>
                </Link>
            </div>
            <a @click.prevent="scrollToComments" href="#comments" class="bg-neutral-800 w-fit flex justify-center items-center rounded-full hover:bg-neutral-600 cursor-pointer">
                <CommentsIcon class="rounded-full"/>
                <span class="pr-3 text-xs">Reply</span>
            </a>
        </div>
    </div>
</template>
