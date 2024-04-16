<script setup>
import GameplayUserGamestate from "@/Partials/GameplayUserGamestate.vue";
const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <h1 class="text-center font-bold">Leaderboard</h1>

    <div
        v-for="player in game.users"
        :key="`gamestate-${player.id}`"
        class="flex items-center my-4 py-4 border-b border-b-yellow-400"
    >
        <h2 class="flex-1">{{ player.name }}</h2>
        <GameplayUserGamestate
            :game="game"
            :user="player"
        ></GameplayUserGamestate>
    </div>
</template>
