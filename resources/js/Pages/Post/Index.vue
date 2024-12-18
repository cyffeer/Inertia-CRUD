<template>
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6 text-center">My Inertia CRUD</h1>
        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th v-for="header in headers" :key="header" class="border border-gray-300 p-3 text-left text-gray-700">
                        {{ header }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="post in posts" :key="post.id" class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="border border-gray-300 p-3">{{ post.title }}</td>
                    <td class="border border-gray-300 p-3">{{ post.body }}</td>
                    <td class="border border-gray-300 p-3">{{ post.quantity }}</td>
                    <td class="border border-gray-300 p-3 flex space-x-2">
                        <button @click="deletePost(post.id)" class="bg-pink-500 text-white px-3 py-1 rounded hover:bg-red-700 transition">Delete</button>
                        <Link :href="`posts/${post.id}/edit`" class="text-pink-500 hover:text-red-700 transition">Edit</Link>
                    </td>
                </tr>
            </tbody>
        </table>
        <form @submit.prevent="submit" class="mt-6">
            <div class="flex space-x-2">
                <input v-model="form.title" placeholder="Title" class="border border-gray-300 p-2 flex-1 rounded" />
                <input v-model="form.body" placeholder="Body" class="border border-gray-300 p-2 flex-1 rounded" />
                <input v-model="form.quantity" placeholder="Quantity" class="border border-gray-300 p-2 flex-1 rounded" />
                <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-red-700 transition">{{ props.isUpdating ? 'Update' : 'Add' }} Post</button>
            </div>
        </form>
    </div>
  </AuthenticatedLayout>
</template>
  
  <script setup>
  import { onMounted } from "vue";
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Link, useForm } from "@inertiajs/vue3";
  const props = defineProps({
    posts: {
      type: Array,
      default: () => [],
    },
    isUpdating: {
      type: Boolean,
      default: false,
    },
  });
  
const headers = ["Title", "Body", "Quantity","Actions"];

const form = useForm({
  title: "",
  body: "",
  quantity: "",
  id: null,
});

const deletePost = async (id) => {
    try {
        await form.delete(`posts/${id}`);
    } catch (error) {
        console.error("Error deleting post:", error);
    }
};

const resetForm = () => {
  form.title = "";
  form.body = "";
};

const submit = () => {
  if (props.isUpdating) {
    updatePost();
  } else {
    addPost();
  }
  resetForm(); // Reset the form after submission
};

// New methods for adding and updating posts
const addPost = () => {
  form.post('/posts'); // Adjust the URL as necessary
};

const updatePost = async () => {
    try {
        await form.put(`/posts/${form.id}`);
    } catch (error) {
        console.error("Error updating post:", error);
    }
};

const editPost = (post) => {
  form.title = post.title;
  form.body = post.body;
  form.quantity = post.quantity;
  form.id = post.id;
};

  </script>