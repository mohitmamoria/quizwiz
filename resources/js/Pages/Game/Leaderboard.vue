<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Leaderboard from "@/Partials/Leaderboard.vue";
import { Head, router } from "@inertiajs/vue3";
import { onMounted } from "vue";

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
});

onMounted(() => {
    window.Echo.private(`App.Models.Game.${props.game.id}`).listen(
        "GameMadeProgress",
        (e) => {
            router.reload({ preserveState: false });
        }
    );
});
</script>

<template>
    <Head title="Leaderboard" />

    <div class="py-8 px-8 max-w-5xl mx-auto">
        <ApplicationLogo class="mx-auto mb-16"></ApplicationLogo>

        <div class="flex items-start">
            <div class="w-8/12">
                <div v-if="!game.ended_at && game.current_question">
                    <h1 class="font-bold mb-16">Current Question</h1>
                    <article class="prose-2xl mb-16 whitespace-pre-wrap">
                        {{ game.current_question.body }}
                    </article>
                </div>
                <div v-else>🎉 All done!</div>
            </div>

            <Leaderboard :game="game"></Leaderboard>
        </div>
    </div>
</template>
