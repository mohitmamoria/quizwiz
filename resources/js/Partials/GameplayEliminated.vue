<script setup>
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
</script>

<template>
    <article class="prose mb-16 text-center">
        <p>🚨 Oops. Your health has reached zero. 🚨</p>
    </article>
</template>
