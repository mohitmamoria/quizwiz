<script setup>
import { useAPIForm } from "@/Composables/useAPIForm";

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

const form = useAPIForm({
    answer: "",
});
</script>

<template>
    <article class="prose mb-16">
        {{ game.current_question.body }}
    </article>

    <form class="space-y-2" action="">
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
                class="flex w-full justify-center rounded-md bg-yellow-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600"
            >
                Submit
            </button>
        </div>
    </form>
</template>
