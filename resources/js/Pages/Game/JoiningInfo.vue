<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head title="Enter the arena" />

    <div class="py-8 px-8 max-w-xl mx-auto">
        <ApplicationLogo class="mx-auto mb-16"></ApplicationLogo>

        <p>Please visit</p>
        <h1 class="text-3xl font-bold">https://playquizwiz.com</h1>
        <p class="mt-6">and enter the following code</p>
        <h1 class="text-6xl font-bold">{{ game.joining_code }}</h1>
    </div>
</template>
