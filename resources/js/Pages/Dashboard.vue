<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    activities: Array,
    authUser: Object,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-bold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        Welcome, {{ $page.props.auth.user.name }}!
                    </div>
                </div>

                <div class="mt-6 bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-pink-500">Recent Activities</h3>                       
                        <ul class="divide-y divide-gray-200">
                            <li v-for="activity in activities" :key="activity.id" class="py-4 flex items-center space-x-4">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ activity.user.name }}</p>
                                    <p class="text-sm text-pink-500 truncate">
                                        {{ activity.action }} a post
                                        <span v-if="activity.post"> "{{ activity.post.title }}"</span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ new Date(activity.created_at).toLocaleString() }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>