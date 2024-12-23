<template>
  <Head title="Posts" />
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 rounded-lg shadow-md">
      <h1 class="text-3xl font-bold mb-6 text-center">My Inertia CRUD</h1>

      <!-- Search Bar -->
      <div class="mb-4 ">
        <input 
          v-model="filterUser"
          type="text"
          placeholder="Filter by User's ID"
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
            <td class="border border-gray-300 p-3">{{ post.title }}</td>
            <td class="border border-gray-300 p-3">{{ post.body }}</td>
            <td class="border border-gray-300 p-3">{{ post.quantity }}</td>
            <td class="border border-gray-300 p-3">
              {{ post.user ? post.user.id : 'Unknown' }} - {{ post.user ? post.user.name : 'Unknown' }}
            </td>
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
          class="px-4 py-2 bg-pink-500 text-white rounded hover:bg-red-700 transition">
          Previous
        </button>

        <span class="self-center text-gray-700">
          Page {{ posts.current_page }} of {{ posts.last_page }}
        </span>

        <button 
          @click="goToPage(posts.next_page_url)" 
          :disabled="!posts.next_page_url" 
          class="px-4 py-2 bg-pink-500 text-white rounded hover:bg-red-700 transition">
          Next
        </button>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from "@inertiajs/vue3";
import { Head } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

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

const headers = ["Title", "Body", "Quantity", "User's ID", "Actions"];
const form = useForm({
  title: "",
  body: "",
  quantity: "",
  id: null,
});
const warningMessage = ref("");
const filterUser = ref(""); 
const allPosts = ref(props.posts.data); 

// Filtered posts 
const filteredPosts = computed(() => {
  const filterValue = filterUser.value.trim().toLowerCase();  

  if (!filterValue) return allPosts.value; 
  return allPosts.value.filter(post => {
    const userId = post.user?.id?.toString() || "";
    return userId.includes(filterValue); 
  });
});

// Delete Post
const deletePost = async (id) => {
  try {
    await form.delete(`posts/${id}`);
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

// Delete and edit actions
const handlePostClick = (post) => {
  if (!props.authUser) {
    warningMessage.value = "User not authenticated";
    return;
  }

  if (post.user && post.user.id !== props.authUser.id) {
    warningMessage.value = "You cannot delete another user's post.";
  } else {
    deletePost(post.id);
  }
};

const handleEditClick = (post) => {
  if (!props.authUser) {
    warningMessage.value = "User not authenticated";
    return;
  }

  if (post.user && post.user.id !== props.authUser.id) {
    warningMessage.value = "You cannot edit another user's post.";
  } else {
    console.log(Inertia);
    Inertia.visit(route('posts.edit', { post: post.id }));
  }
};
</script>

