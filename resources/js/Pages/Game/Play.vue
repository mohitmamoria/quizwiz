<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import GameplayAfterEnd from "@/Partials/GameplayAfterEnd.vue";
import GameplayBeforeStart from "@/Partials/GameplayBeforeStart.vue";
import GameplayQuestion from "@/Partials/GameplayQuestion.vue";
import GameplayUserGamestate from "@/Partials/GameplayUserGamestate.vue";
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
        <ApplicationLogo class="mx-auto mb-4"></ApplicationLogo>

        <GameplayUserGamestate
            class="mb-16"
            :game="game"
            :user="user"
        ></GameplayUserGamestate>

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
