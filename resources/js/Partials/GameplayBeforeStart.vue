<script setup>
const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
    user: {
        type: Object,
        required: true,
    }
});
</script>

<template>
    <article class="prose mb-16 text-center">
        <p>Waiting for all players to join.</p>
    </article>
</template>
