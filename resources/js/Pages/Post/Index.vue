<template>
  <Head title="Posts" />
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 rounded-lg shadow-md">
      <h1 class="text-3xl font-bold mb-6 text-center">Inertia CRUD</h1>

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

      <!-- Pagination -->
      <div v-if="!filterUser.trim()" class="flex justify-between items-center mt-4">
        <button 
          @click="goToPage(posts.prev_page_url)" 
          :disabled="!posts.prev_page_url" 
          class="px-3 py-2 bg-pink-500 text-white rounded hover:bg-red-700 transition">
          Previous
        </button>

        <span class="self-center text-gray-700">
          Page {{ posts.current_page }} of {{ posts.last_page }}
        </span>

        <button 
          @click="goToPage(posts.next_page_url)" 
          :disabled="!posts.next_page_url" 
          class="px-6 py-2 bg-pink-500 text-white rounded hover:bg-red-700 transition">
          Next
        </button>
      </div>

      <!-- View All Posts Button -->
      <div class="text-center">
        <button 
          @click="viewAllPosts"
          class="px-3 py-1 bg-pink-500 text-white rounded hover:bg-pink-700 transition">
          View All Posts
        </button>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from "@inertiajs/vue3";
import { Inertia } from '@inertiajs/inertia';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { format } from 'date-fns'; 

const props = defineProps({
  posts: {
    type: Object,
    default: () => ({ data: [], prev_page_url: null, next_page_url: null, current_page: 1, last_page: 1 }),
  },
  authUser: {
    type: Object,
    required: true,
  }
});

const headers = ["User's ID", "Title", "Body", "Quantity", "Date", "Actions"];
const form = useForm({
  title: "",
  body: "",
  quantity: "",
  published_at: "",
  id: null,
});
const warningMessage = ref("");
const filterUser = ref(""); 
const allPosts = ref(props.posts.data); 
const filterUserTitle = ref("");
const filterUserBody = ref("");

// Filtered posts
const filteredPosts = computed(() => {
  const filterTitle = filterUserTitle.value.trim().toLowerCase();
  const filterBody = filterUserBody.value.trim().toLowerCase();

  return allPosts.value.filter(post => {
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
    allPosts.value = allPosts.value.filter(post => post.id !== id);
    
    // Show success message
    alert('The post has been deleted successfully.');
  } catch (error) {
    console.error("Error deleting post:", error);
  }
};

// Pagination
const goToPage = (url) => {
  if (url) {
    form.get(url);
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

// View All Posts
const viewAllPosts = () => {
  window.location.href = '/posts/show';
};

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