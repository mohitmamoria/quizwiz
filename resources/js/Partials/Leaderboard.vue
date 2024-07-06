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
    <div>
        <h1 class="text-center font-bold mb-4">
            Leaderboard ({{ game.users.length }})
        </h1>
        <div v-if="game.users.length === 0" class="prose text-center">
            <p>No players yet.</p>
        </div>
        <div
            v-else
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
    </div>
</template>
