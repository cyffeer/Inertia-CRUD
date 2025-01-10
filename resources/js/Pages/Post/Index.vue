<template>
  <Head title="Posts" />
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 rounded-lg shadow-md">
      <h1 class="text-3xl font-bold mb-6 text-center">Inertia CRUD</h1>

      <!-- Search Bar -->
      <div class="mb-3">
        <input 
          v-model="filterText"
          @input="fetchPosts"
          type="text"
          placeholder="Search by Title or Body"
          class="rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500"
        />
      </div>

      <!-- Items Per Page Form -->
      <div class="mb-3">
        <label for="perPage" class="mr-2">Items per page:</label>
        <select id="perPage" v-model.number="perPage" @change="fetchPosts" class="rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
          <option v-for="option in perPageOptions" :key="option" :value="option">{{ option }}</option>
        </select>
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
          <tr v-for="post in posts.data" :key="post.id" class="border-b border-gray-200 hover:bg-gray-50">
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
      <Pagination :page="posts.current_page" :totalPages="posts.last_page" :onPageChange="goToPage" />   
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, watch } from "vue";
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { format } from 'date-fns'; 
import Pagination from '@/Components/Pagination.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const headers = ["User's ID", "Title", "Body", "Quantity", "Date", "Actions"];
const perPageOptions = [3, 5, 8, 10, 'All'];

const warningMessage = ref("");
const filterText = ref(""); 
const perPage = ref(5);
const authUser = ref(null);
const posts = ref({ data: [], prev_page_url: null, next_page_url: null, current_page: 1, last_page: 1 });

//FETCH AUTHENTICATED USER
axios.get('/api/auth-user')
  .then(response => {
    authUser.value = response.data;
  })
  .catch(error => {
    console.error("Error fetching authenticated user:", error);
  })

// Watch for changes to perPage and reload the page
watch(perPage, (newPerPage) => {
  fetchPosts();
});

// Watch for changes to filterText and fetch filtered posts
watch(filterText, (newFilterText) => {
  fetchPosts();
});

// Fetch posts based on filterText and perPage
const fetchPosts = (page = 1) => {
  axios.get('/api/posts', {
    params: {
      q: filterText.value,
      per_page: perPage.value === 'All' ? 0 : perPage.value,
      page: page
    }
  })
  .then(response => {
    posts.value = response.data;
  })
  .catch(error => {
    console.error("Error fetching posts:", error);
    warningMessage.value = 'Error fetching posts. Please try again.';
  });
};

// Pagination
const goToPage = (page) => {
  fetchPosts(page);
};

const handlePostClick = (post) => {
  if (!authUser.value) {
    warningMessage.value = "User not authenticated";
    return;
  }

  if (confirm('Are you sure you want to delete this post?')) {
    axios.delete(`/posts/${post.id}`)
      .then(() => {
        warningMessage.value = 'The post has been deleted successfully.';
        fetchPosts();
      })
      .catch((error) => {
        console.error("Error deleting post:", error);
        warningMessage.value = 'Error deleting post. Please try again.';
      });
  }
};

const handleEditClick = (post) => {
  if (!authUser.value) {
    warningMessage.value = "User not authenticated";
    return; 
  }
  window.location.href = `/posts/${post.id}/edit`;
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

// Fetch initial posts
fetchPosts();
</script>