<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Leaderboard from "@/Partials/Leaderboard.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head title="Leaderboard" />

    <div class="py-8 px-8 max-w-xl mx-auto">
        <ApplicationLogo class="mx-auto mb-4"></ApplicationLogo>

        <Leaderboard :game="game"></Leaderboard>
    </div>
</template>
