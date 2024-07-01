<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Head, useForm } from "@inertiajs/vue3";

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

const form = useForm({});
const next = () => {
    form.post(route("game.next", { game: props.game.id }));
};
</script>

<template>
    <Head title="Instructions to play!" />

    <div class="py-8 px-8 max-w-xl mx-auto">
        <ApplicationLogo class="mx-auto mb-4"></ApplicationLogo>

        <article class="prose-lg my-16">
            <ul>
                <li>
                    1️⃣ There are <span class="font-bold">5 questions</span> in
                    this quiz game.
                </li>
                <li class="mt-2">
                    2️⃣ Every correct answer will give you
                    <span class="font-bold">10 points</span>.
                </li>
                <li class="mt-2">
                    3️⃣ Your score = your discount
                    <span class="font-bold">(e.g. 40 score = 40% off)</span>
                </li>
                <li class="mt-2">
                    4️⃣ You will have
                    <span class="font-bold">20 seconds per question</span> to
                    answer.
                </li>
            </ul>
        </article>

        <form @submit.prevent="next" class="">
            <button
                type="submit"
                :disabled="form.processing"
                class="flex w-full justify-center rounded-md bg-yellow-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600 disabled:opacity-50"
            >
                Start Playing &rarr;
            </button>
        </form>
    </div>
</template>
