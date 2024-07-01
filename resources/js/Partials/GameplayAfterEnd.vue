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
                Use coupon code:
                <span class="font-bold">{{ discountWon.discount_code }}</span>
                to redeem your discount (valid only for the next 30 minutes) to
                our next online show
                <a
                    class="font-medium underline text-blue-500"
                    :href="discountWon.discounted_link"
                    >on this link.</a
                >
            </p>
        </div>
    </div>

    <Leaderboard v-else :game="game"></Leaderboard>
</template>
