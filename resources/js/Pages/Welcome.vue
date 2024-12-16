<template>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
      <h1 class="text-4xl font-bold text-pink-500 mb-4">Welcome to Inertia CRUD</h1>
      <button class="px-4 py-2 bg-pink-500 text-white rounded hover:bg-gray-600" @click="$inertia.visit('/post')">Go to Posts</button>
    </div>
  </template>
  
  <script>
  export default {
    name: 'Welcome'
  }
  </script>
  