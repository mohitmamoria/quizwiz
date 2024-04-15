<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
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
                <h4 class="text-[#058F66] font-extrabold">100</h4>
            </div>

            <div class="flex items-end">
                <img
                    class="w-6 h-6"
                    src="/images/icons/icon-timer.svg"
                    alt="Timer"
                />
                <h4 class="text-[#477ED2] font-extrabold">00:00</h4>
            </div>

            <div class="flex items-end">
                <img
                    class="w-6 h-6"
                    src="/images/icons/icon-leaderboard.svg"
                    alt="Leaderboard"
                />
                <h4 class="text-[#9B2BB7] font-extrabold">#1</h4>
            </div>
        </div>
    </div>
</template>
