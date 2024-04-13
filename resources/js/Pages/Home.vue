<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Head, useForm } from "@inertiajs/vue3";

const form = useForm({
    code: "",
});

const join = () => {
    form.post(route("game.verify-code"), {
        preserveState: false,
    });
};
</script>

<template>
    <Head title="Join a game" />

    <div class="py-8 px-8 max-w-xl mx-auto">
        <ApplicationLogo class="mx-auto mb-16"></ApplicationLogo>

        <form class="space-y-4" @submit.prevent="join">
            <div>
                <label
                    for="code"
                    class="block text-sm font-medium leading-6 text-gray-900"
                    >What is the GAME CODE?</label
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
                    Enter the arena
                </button>
            </div>
        </form>
    </div>
</template>
