<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const isLoggedIn = ref(false); // Replace with actual login state management
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0"
    >
        <div v-if="!isLoggedIn" class="text-red-500 text-center">
            Please log in to access this content.
            <br><br>
            <Link href="/login" class="mt-2 px-4 py-2 bg-pink-500 text-white rounded">
                Go to Login
            </Link>
        </div>
        <div v-else>
            <div
                class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
            >
                <slot />
            </div>
        </div>
    </div>
</template>

