<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useCountdown } from "@/Composables/useCountdown";
import { router, useForm } from "@inertiajs/vue3";

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

const form = useForm({
    answer: "",
});

const submit = () => {
    form.post(route("game.submit-answer", { game: props.game.id }), {
        onSuccess: () => {
            form.reset();
        },
    });
};

const nextStep = useForm({});
const next = () => {
    nextStep.post(route("game.next", { game: props.game.id }));
};

const MAX_SECONDS_ALLOWED = 20;

let { secondsLeft, onCountdownEnd, resetCountdown } = useCountdown(
    props.game.current_question_asked_at,
    MAX_SECONDS_ALLOWED
);

onCountdownEnd(() => {
    if (
        // Solo game
        props.game.is_solo &&
        // Not on the answer screen
        !props.game.current_question_answered_at &&
        // Not on the waiting for the next question screen
        !(
            props.attempt &&
            props.attempt.question_id === props.game.current_question.id
        )
    ) {
        next();
    }
});

router.on("success", (event) => {
    form.reset();
    resetCountdown(props.game.current_question_asked_at, MAX_SECONDS_ALLOWED);
});
</script>

<template>
    <div class="prose" v-if="game.current_question_answered_at">
        <div class="bg-white rounded p-4 border border-yellow-400">
            <h1 class="uppercase text-xs font-thin">Answer</h1>
            <p class="text-xl font-bold">
                {{ game.current_question.correct_answer }}
            </p>
        </div>

        <div class="mt-16 text-center">
            <p v-if="!game.is_solo">⏳ Please wait for the next question.</p>
            <form v-else @submit.prevent="next" class="">
                <PrimaryButton type="submit">Next &rarr;</PrimaryButton>
            </form>
        </div>
    </div>
    <div
        class="prose text-center"
        v-else-if="attempt && attempt.question_id === game.current_question.id"
    >
        <p class="text-center">Wait for the answer to be revealed next.</p>
    </div>
    <div v-else>
        <article
            class="pros mb-16 whitespace-pre-wrap"
            v-html="game.current_question.body_html"
        ></article>

        <form class="space-y-2" @submit.prevent="submit">
            <div>
                <label
                    for="answer"
                    class="block text-xs font-medium leading-6 text-yellow-600 uppercase"
                    >What's your answer?</label
                >
                <div>
                    <input
                        id="answer"
                        name="answer"
                        type="text"
                        required
                        placeholder="Your attempt..."
                        autofocus
                        v-model="form.answer"
                        class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-600"
                    />
                </div>
            </div>

            <div>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="flex w-full justify-center rounded-md bg-yellow-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600 disabled:opacity-50"
                >
                    Submit
                    <span v-if="game.is_solo" v-cloak
                        >({{ secondsLeft }}s)</span
                    >
                </button>
            </div>
        </form>
    </div>
</template>
