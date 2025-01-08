<template>
  <Head title="Posts" />
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 rounded-lg shadow-md">
      <h1 class="text-3xl font-bold mb-6 text-center">Inertia CRUD</h1>
      <h2 class="text-xl mb-6 text-center">View All</h2>

      <!-- Search Bar for Title -->
      <div class="mb-3">
        <input 
          v-model="filterUserTitle"
          type="text"
          placeholder="Filter by Title"
          class="rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500"
        />
      </div>
      <!-- Search Bar for Body -->
      <div class="mb-3">
        <input 
          v-model="filterUserBody"
          type="text"
          placeholder="Filter by Body"
          class="rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500"
        />
      </div>

      <!-- Warning Message -->
      <div v-if="warningMessage" class="bg-pink-800 text-white p-4 mb-4 rounded-lg">
        {{ warningMessage }}
      </div>

      <!-- Table -->
      <table class="min-w-full border-collapse border border-gray-300 mb-4">
        <thead>
          <tr class="bg-gray-200">
            <th v-for="header in headers" :key="header" class="border border-gray-300 p-3 text-left text-gray-700">
              {{ header }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="post in filteredPosts" :key="post.id" class="border-b border-gray-200 hover:bg-gray-50">
            <td class="border border-gray-300 p-3">
              {{ post.user ? post.user.id : 'Unknown' }} - {{ post.user ? post.user.name : 'Unknown' }}
            </td>
            <td class="border border-gray-300 p-3">{{ post.title }}</td>
            <td class="border border-gray-300 p-3">{{ post.body }}</td>
            <td class="border border-gray-300 p-3">{{ post.quantity }}</td>
            <td class="border border-gray-300 p-3">{{ formatDate(post.published_at) }}</td>
            <td class="border border-gray-300 p-3 flex space-x-2">
              <button 
                @click="handlePostClick(post)" 
                class="bg-pink-500 text-white px-3 py-1 rounded hover:bg-red-700 transition">
                Delete
              </button>
              <button 
                @click="handleEditClick(post)" 
                class="text-pink-500 hover:text-red-700 transition">
                Edit
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- View All Posts Button -->
      <div class="mb-5 text-center">
        <button
          @click="goToIndex"
          class="px-4 py-2 bg-pink-500 text-white rounded hover:bg-red-700 transition">
          Go Back
        </button>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from "@inertiajs/vue3";
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { format } from 'date-fns'; 

// Props
const props = defineProps({
  posts: {
    type: Array,
    required: true,
  },
  authUser: {
    type: Object,
    required: true,
  }
});

// Variables and refs
const headers = ["User's ID", "Title", "Body", "Quantity", "Date", "Actions"];
const warningMessage = ref("");
const filterUserTitle = ref("");
const filterUserBody = ref("");

// Filtered posts
const filteredPosts = computed(() => {
  const filterTitle = filterUserTitle.value.trim().toLowerCase();
  const filterBody = filterUserBody.value.trim().toLowerCase();

  return props.posts.filter(post => {
    const titleMatch = post.title.toLowerCase().startsWith(filterTitle);
    const bodyMatch = post.body.toLowerCase().startsWith(filterBody);
    return titleMatch && bodyMatch;
  });
});

// Delete Post
const deletePost = async (id) => {
  try {
    await axios.delete(`/posts/${id}`);
    // Update the posts after successful deletion without refreshing the page
    props.posts = props.posts.filter(post => post.id !== id);
    
    // Show success message
    alert('The post has been deleted successfully.');
  } catch (error) {
    console.error("Error deleting post:", error);
  }
};

const handlePostClick = (post) => {
  if (!props.authUser) {
    warningMessage.value = "User not authenticated";
    return;
  }

  deletePost(post.id);
};

const handleEditClick = (post) => {
  if (!props.authUser) {
    warningMessage.value = "User not authenticated";
    return; // Exit the function if user is not authenticated
  }

  // Only proceed to the edit page if no issues
  window.location.href = `/posts/${post.id}/edit`;
};

// Go to Index page
const goToIndex = () => {
  window.location.href = '/posts';
}

// Format date
const formatDate = (dateString) => {
  if (!dateString) return ''; 
  try {
    return format(new Date(dateString), 'MMMM dd, yyyy');
  } catch (error) {
    console.error("Invalid date value:", dateString);
    return 'N/A'; 
  }
};
</script>