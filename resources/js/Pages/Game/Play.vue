<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import GameplayAfterEnd from "@/Partials/GameplayAfterEnd.vue";
import GameplayBeforeStart from "@/Partials/GameplayBeforeStart.vue";
import GameplayEliminated from "@/Partials/GameplayEliminated.vue";
import GameplayQuestion from "@/Partials/GameplayQuestion.vue";
import GameplayUserGamestate from "@/Partials/GameplayUserGamestate.vue";
import { Head, router } from "@inertiajs/vue3";
import { onMounted } from "vue";

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
    discountWon: {
        type: Object,
        default: null,
    },
});

onMounted(() => {
    if (!props.game.is_solo) {
        window.Echo.private(`App.Models.Game.${props.game.id}`).listen(
            "GameMadeProgress",
            (e) => {
                router.reload({ preserveState: false });
            }
        );
    }
});
</script>

<template>
    <Head title="Enjoy!" />

    <div class="py-8 px-8 max-w-xl mx-auto">
        <ApplicationLogo class="mx-auto mb-4"></ApplicationLogo>

        <GameplayUserGamestate
            class="mb-16"
            :game="game"
            :user="user"
        ></GameplayUserGamestate>

        <GameplayEliminated
            v-if="user.gamestate.health < 1"
            :game="game"
            :user="user"
        ></GameplayEliminated>

        <GameplayBeforeStart
            v-else-if="game.started_at === null"
            :game="game"
            :user="user"
        ></GameplayBeforeStart>

        <GameplayAfterEnd
            v-else-if="game.ended_at !== null"
            :game="game"
            :user="user"
            :discount-won="discountWon"
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
