<template>
    <div v-if="timeLeft" class="countdown-timer flex items-center justify-center">
        <div class="flex items-center justify-center space-x-4 bg-black p-4 text-white">
            <span>{{ timeLeft.days }}d</span>
            <span>:</span>
            <span>{{ timeLeft.hours }}h</span>
            <span>:</span>
            <span>{{ timeLeft.minutes }}m</span>
            <span>:</span>
            <span>{{ timeLeft.seconds }}s</span>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    targetDate: {
        type: Date,
        required: true
    }
});

const timeLeft = ref({ days: 0, hours: 0, minutes: 0, seconds: 0 });

const calculateTimeLeft = () => {
    const now = new Date();
    const difference = props.targetDate - now;

    if (difference > 0) {
        timeLeft.value = {
            days: Math.floor(difference / (1000 * 60 * 60 * 24)),
            hours: Math.floor((difference / (1000 * 60 * 60)) % 24),
            minutes: Math.floor((difference / 1000 / 60) % 60),
            seconds: Math.floor((difference / 1000) % 60)
        };
    } else {
        timeLeft.value = { days: 0, hours: 0, minutes: 0, seconds: 0 };
    }
};

let timerInterval;

onMounted(() => {
    calculateTimeLeft();
    timerInterval = setInterval(calculateTimeLeft, 1000);
});

onUnmounted(() => {
    clearInterval(timerInterval);
});
</script>

<style scoped>
.countdown-timer {
    margin-top: -90px;
}

.countdown-timer span {
    font-size: 6rem;
}
</style>