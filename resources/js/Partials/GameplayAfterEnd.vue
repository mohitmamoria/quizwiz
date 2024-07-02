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
        <p>Thank you for playing today. Did you enjoy?</p>
    </article>

    <div v-if="game.is_solo">
        <div class="bg-white rounded p-4 border border-yellow-400">
            <p class="mt-4 text-lg font-bold">{{ discountWon.reason }}</p>
            <p class="mt-4">
                Enjoyed playing this quiz? You will love our 90-min online quiz
                show full of fun and learning! Also, make new friends.
            </p>
            <p class="mt-4">
                Use your winning coupon code:
                <span class="font-bold">{{ discountWon.discount_code }}</span>
                to redeem your discount (valid only for the next 30 minutes).
            </p>
            <a
                :href="discountWon.discounted_link"
                target="_blank"
                class="mt-4 flex w-full justify-center rounded-md bg-yellow-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600 disabled:opacity-50"
                >Know more about the online quiz show &rarr;</a
            >
        </div>
    </div>

    <Leaderboard v-else :game="game"></Leaderboard>
</template>
