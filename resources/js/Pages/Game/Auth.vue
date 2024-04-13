<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import HelpText from "@/Components/HelpText.vue";
import { useAPIForm } from "@/Composables/useAPIForm";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
});

const stage = ref("starting");

const form = useAPIForm({
    name: "",
    email: "",
    code: "",
});

const auth = () => {
    if (stage.value === "starting") {
        startAuth();
    } else if (stage.value === "completing") {
        completeAuth();
    }
};

const startAuth = () => {
    form.post(route("game-auth.start", { game: props.game.id }), {
        onSuccess: () => {
            stage.value = "completing";
        },
    });
};

const completeAuth = () => {
    form.put(route("game-auth.complete", { game: props.game.id }), {
        onSuccess: () => {
            window.location.reload();
        },
    });
};
</script>

<template>
    <Head title="Enter the arena" />

    <div class="py-8 px-8 max-w-xl mx-auto">
        <ApplicationLogo class="mx-auto mb-16"></ApplicationLogo>

        <form class="space-y-4" @submit.prevent="auth">
            <div>
                <label
                    for="name"
                    class="block text-sm font-medium leading-6 text-gray-900"
                    >Your name for the leaderboard?</label
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
                    >Confirm your email to play</label
                >
                <div class="mt-1">
                    <input
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required
                        placeholder="hello@example.com"
                        v-model="form.email"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    />
                </div>
                <HelpText>
                    We will send a unique code to this email for verification.
                </HelpText>
            </div>

            <div v-if="stage === 'completing'">
                <label
                    for="code"
                    class="block text-sm font-medium leading-6 text-gray-900"
                    >Enter the code that we sent you on the email</label
                >
                <div class="mt-1">
                    <input
                        id="code"
                        name="code"
                        type="number"
                        required
                        placeholder="* * * * *"
                        v-model="form.code"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    />
                </div>
            </div>

            <div>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    Verify
                </button>
            </div>
        </form>
    </div>
</template>
