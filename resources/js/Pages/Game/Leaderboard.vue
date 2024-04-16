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

    <div class="py-8 px-8 max-w-xl mx-auto">
        <ApplicationLogo class="mx-auto mb-4"></ApplicationLogo>

        <Leaderboard :game="game"></Leaderboard>
    </div>
</template>
