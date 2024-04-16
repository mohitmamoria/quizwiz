<script setup>
import GameplayAfterEnd from "@/Partials/GameplayAfterEnd.vue";
import GameplayBeforeStart from "@/Partials/GameplayBeforeStart.vue";
import GameplayHeader from "@/Partials/GameplayHeader.vue";
import GameplayQuestion from "@/Partials/GameplayQuestion.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
    user: {
        type: Object,
        required: true,
    },
    attempt: {
        type: Object,
        default: null,
    },
    wasPreviousAttemptCorrect: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <Head title="Enter the arena" />

    <div class="py-8 px-8 max-w-xl mx-auto">
        <GameplayHeader :game="game" :user="user"></GameplayHeader>

        <GameplayBeforeStart
            v-if="game.started_at === null"
            :game="game"
            :user="user"
        ></GameplayBeforeStart>

        <GameplayAfterEnd
            v-else-if="game.ended_at !== null"
            :game="game"
            :user="user"
        ></GameplayAfterEnd>

        <GameplayQuestion
            v-else
            :game="game"
            :user="user"
            :attempt="attempt"
            :wasPreviousAttemptCorrect="wasPreviousAttemptCorrect"
        ></GameplayQuestion>
    </div>
</template>
