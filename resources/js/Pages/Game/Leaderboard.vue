<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Leaderboard from "@/Partials/Leaderboard.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
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

const form = useForm({});

const next = () => {
    form.post(route("game.next", { game: props.game.id }));
};
</script>

<template>
    <Head title="Leaderboard" />

    <div class="py-8 px-8 w-full flex flex-col min-h-dvh mx-auto">
        <ApplicationLogo class="mx-auto mb-16"></ApplicationLogo>

        <div class="flex flex-1 items-start space-x-2">
            <div
                class="w-8/12 h-full bg-white rounded p-4 border border-yellow-400"
            >
                <div v-if="!game.started_at" class="text-center my-16">
                    ⏳ Waiting for the game to start. Sit tight!
                </div>
                <div
                    class="overflow-hidden"
                    v-else-if="!game.ended_at && game.current_question"
                >
                    <h1 class="font-bold mb-2 text-gray-400 uppercase text-xs">
                        Question
                    </h1>

                    <article
                        class="prose-img:max-w-sm mb-16 whitespace-pre-wrap"
                        v-html="game.current_question.body_html"
                    ></article>

                    <div v-if="game.current_question_answered_at">
                        <h1
                            class="font-bold mb-2 text-gray-400 uppercase text-xs"
                        >
                            Answer
                        </h1>
                        <article class="whitespace-pre-wrap">
                            <p class="text-2xl">
                                {{ game.current_question.correct_answer }}
                            </p>
                        </article>
                    </div>
                </div>
                <div v-else class="text-center my-16">
                    😻 Thank you for playing with us today.
                </div>
            </div>

            <form @submit.prevent="next" class="">
                <PrimaryButton type="submit">&rarr;</PrimaryButton>
            </form>
            <Leaderboard class="w-4/12 p-4" :game="game"></Leaderboard>
        </div>
    </div>
</template>
