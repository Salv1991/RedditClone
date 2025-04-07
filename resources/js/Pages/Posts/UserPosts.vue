<script setup>
import VoteDownIcon from '../../Shared/Icons/VoteDownIcon.vue';
import VoteUpIcon from '../../Shared/Icons/VoteUpIcon.vue';
import CommentsIcon from '../../Shared/Icons/CommentsIcon.vue';
import ThreeHorizontalDotsIcon from '../../Shared/Icons/ThreeHorizontalDotsIcon.vue';

    defineProps({
        posts:Array,
    });

</script>

<template>
    <div class="divide-y-1 divide-gray-600/50">
        <article v-for="post in posts">
            <a href="/" class="block hover:bg-neutral-800/50 rounded-lg p-4 my-2">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-xs text-gray-300 mr-1">{{ $page.props.auth.user.username ?? 'Unknown user'}}</span>
                        <span class="text-xs text-gray-500">• {{ post.created_at }}</span>
                    </div>
                    <div>
                        <ThreeHorizontalDotsIcon class="cursor-pointer bg-neutral-800 hover:bg-neutral-700 rounded-full"/>
                    </div>
                </div>
                <div class="mt-2">
                    <h2 class="text-xl">{{ post.title }}</h2>
                    <p class="mt-1 text-gray-300 text-sm">{{ post.text }}</p>
                </div>
                <div class="mt-4 flex justify-start items-center gap-2">
                    <div class="bg-neutral-800 w-fit gap-1 flex justify-center items-center rounded-full">
                        <VoteUpIcon class="rounded-full bg-neutral-800 hover:bg-neutral-700 cursor-pointer"/>
                        <span>{{ post.likes }}</span>
                        <VoteDownIcon class="rounded-full bg-neutral-800 hover:bg-neutral-700 cursor-pointer"/>
                    </div>
                    <div class="bg-neutral-800 w-fit flex justify-center items-center rounded-full hover:bg-neutral-600 cursor-pointer">
                        <CommentsIcon class="rounded-full"/>
                        <span class="pr-3">{{post.commentsCount}}</span>
                    </div>
                </div>
            </a>
        </article>
    </div>
</template>
