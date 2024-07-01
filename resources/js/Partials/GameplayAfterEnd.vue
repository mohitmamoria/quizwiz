<script setup>
import Leaderboard from "@/Partials/Leaderboard.vue";

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
    user: {
        type: Object,
        required: true,
    },
    discountWon: {
        type: Object,
        default: null,
    },
});
</script>

<template>
    <article class="prose mb-16 text-center">
        <p>Thank you for playing today.</p>
    </article>

    <div v-if="game.is_solo">
        <div class="bg-white rounded p-4 border border-yellow-400">
            <h1 class="uppercase text-xs font-thin">RESULT</h1>
            <p class="mt-4 text-xl font-bold">{{ discountWon.reason }}</p>
            <p class="mt-4">
                Buy tickets to our next online show
                <a
                    class="font-medium underline text-blue-500"
                    :href="discountWon.discounted_link"
                    >using this link</a
                >
                and redeem your winnings (valid only for the next 30 minutes).
            </p>
        </div>
    </div>

    <Leaderboard v-else :game="game"></Leaderboard>
</template>
