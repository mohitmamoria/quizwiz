<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
    user: {
        type: Object,
        required: true,
    }
})

const timeSpentForHumans = computed
</script>

<template>
    <div class="mb-16">
        <ApplicationLogo class="mx-auto mb-4"></ApplicationLogo>

        <div class="mx-auto flex items-center justify-center space-x-5">
            <div class="flex items-end">
                <img
                    class="w-6 h-6"
                    src="/images/icons/icon-health.svg"
                    alt="Health"
                />
                <h4 class="text-[#058F66] font-extrabold">{{ user.gamestate.health }}</h4>
            </div>

            <div class="flex items-end">
                <img
                    class="w-6 h-6"
                    src="/images/icons/icon-timer.svg"
                    alt="Timer"
                />
                <h4 class="text-[#477ED2] font-extrabold">{{ user.gamestate.time_spent }}</h4>
            </div>

            <div class="flex items-end">
                <img
                    class="w-6 h-6"
                    src="/images/icons/icon-leaderboard.svg"
                    alt="Leaderboard"
                />
                <h4 class="text-[#9B2BB7] font-extrabold">{{ user.gamestate.rank ? '#' + user.gamestate.rank : 'TBD' }}</h4>
            </div>
        </div>
    </div>
</template>
