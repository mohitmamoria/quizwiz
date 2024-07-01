<script setup>
import { computed } from "vue";

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

const timeSpentForHumans = computed(() => {
    return new Date(props.user.gamestate.time_spent * 1000)
        .toISOString()
        .slice(14, 19);
});
</script>

<template>
    <div class="mx-auto flex items-center justify-center space-x-5">
        <div class="flex items-end">
            <img
                class="w-6 h-6"
                src="/images/icons/icon-score.svg"
                alt="Health"
            />
            <h4 class="text-[#058F66] font-extrabold">
                {{ user.gamestate.score }}
            </h4>
        </div>

        <div class="flex items-end" v-if="!game.is_solo">
            <img
                class="w-6 h-6"
                src="/images/icons/icon-timer.svg"
                alt="Timer"
            />
            <h4 class="text-[#477ED2] font-extrabold">
                {{ timeSpentForHumans }}
            </h4>
        </div>

        <div class="flex items-end" v-if="!game.is_solo">
            <img
                class="w-6 h-6"
                src="/images/icons/icon-leaderboard.svg"
                alt="Leaderboard"
            />
            <h4 class="text-[#9B2BB7] font-extrabold">
                {{ user.gamestate.rank ? "#" + user.gamestate.rank : "TBD" }}
            </h4>
        </div>
    </div>
</template>
