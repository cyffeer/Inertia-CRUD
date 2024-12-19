<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/AccessLayout.vue';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();
</script>

<template>

  <Head title="Welcom to Inertia-CRUD" />
  
  <component :is="props.auth.user ? AuthenticatedLayout : GuestLayout">
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
      <h1 class="text-4xl font-bold text-pink-500 mb-4">Welcome to Inertia CRUD</h1>
      <button 
        class="px-4 py-2 bg-pink-500 text-white rounded hover:bg-gray-600" 
        @click="props.auth.user ? $inertia.visit('/post') : $inertia.visit('/login')"
      >
        Go to Posts
      </button>
    </div>
  </component>
</template>

<script>
export default {
  name: 'Welcome'
}
</script>
  