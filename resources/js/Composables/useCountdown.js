import { onMounted, onUnmounted, ref } from "vue";

export function useCountdown(setFrom, limit = 30) {
    let onCountdownEndHook = null;
    let countdownInterval = null;

    let startedAt = setFrom;
    let secondsLeft = ref(limit);
    let secondsElapsed = ref(0);

    const init = () => {
        /**
         * Bring startedAt to the type that we can work with.
         */
        if (!startedAt instanceof Date) {
            startedAt = new Date(startedAt);
        }
        startedAt = Math.ceil(new Date(startedAt).getTime() / 1000);

        /**
         * Run the countdown
         */
        countdownInterval = setInterval(updateCountdown, 1000);
    };

    const updateCountdown = () => {
        if (secondsLeft.value === 0) {
            if (onCountdownEndHook) {
                onCountdownEndHook();
            }
            clearInterval(countdownInterval);
        }

        const now = Math.ceil(new Date().getTime() / 1000);

        secondsElapsed.value = now - startedAt;
        secondsLeft.value = Math.max(0, limit - secondsElapsed.value);
    };

    const resetCountdown = (resetFrom, limit = 30) => {
        clearInterval(countdownInterval);
        countdownInterval = null;

        startedAt = resetFrom;
        secondsLeft.value = limit;
        secondsElapsed.value = 0;

        init();
    };

    onMounted(() => {
        init();
    });

    onUnmounted(() => {
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }
    });

    const onCountdownEnd = (hook) => {
        onCountdownEndHook = hook;
    };

    return { secondsLeft, onCountdownEnd, resetCountdown };
}
