<script setup>
import Leaderboard from "@/Partials/Leaderboard.vue";

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
    user: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <article class="prose mb-16 text-center">
        <p>Thank you for playing today.</p>
    </article>

    <Leaderboard :game="game"></Leaderboard>
</template>
