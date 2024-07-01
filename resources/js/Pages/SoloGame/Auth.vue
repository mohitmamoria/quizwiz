<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import HelpText from "@/Components/HelpText.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    quiz: {
        type: Object,
        required: true,
    },
});

const stage = ref("starting");

const form = useForm({
    name: "",
    email: "",
});

const auth = () => {
    startAuth();
};

const startAuth = () => {
    form.post(route("solo-game.store", { quiz: props.quiz.id }));
};
</script>

<template>
    <Head title="Play and win!" />

    <div class="py-8 px-8 max-w-xl mx-auto">
        <ApplicationLogo class="mx-auto mb-16"></ApplicationLogo>

        <form class="space-y-4" @submit.prevent="auth">
            <div>
                <label
                    for="name"
                    class="block text-sm font-medium leading-6 text-gray-900"
                    >What's your name?</label
                >
                <div class="mt-1">
                    <input
                        id="name"
                        name="email"
                        autocomplete="name"
                        required
                        placeholder="Harry Potter"
                        v-model="form.name"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    />
                </div>
            </div>

            <div>
                <label
                    for="email"
                    class="block text-sm font-medium leading-6 text-gray-900"
                    >What is your email to play?</label
                >
                <div class="mt-1">
                    <input
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="username"
                        required
                        placeholder="hello@example.com"
                        v-model="form.email"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    />
                </div>
                <HelpText>
                    This will be used to send your unique discount coupon. Make
                    sure there are no typos in it.
                </HelpText>
            </div>

            <div>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    Start
                </button>
            </div>
        </form>
    </div>
</template>
