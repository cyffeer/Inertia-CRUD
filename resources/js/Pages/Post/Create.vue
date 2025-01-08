<template>
  <Head title="Create" />
  <AuthenticatedLayout>
    <div class="py-12">
      <div class="mx-auto max-w-2xl sm:px-6 lg:px-4">
        <h1 class="text-2xl font-bold mb-4">Create Post</h1>
        <form @submit.prevent="submit" class="space-y-4 bg-white p-6 rounded-lg shadow-md">
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" id="title" v-model="form.title" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-pink-500 focus:border-pink-500" placeholder="Post Title" />
          </div>
          <div>
            <label for="body" class="block text-sm font-medium text-gray-700">Body</label>
            <input type="text" id="body" v-model="form.body" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-pink-500 focus:border-pink-500" placeholder="Post Body" />
          </div>
          <div>
            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
            <input 
              type="text" 
              id="quantity" 
              v-model="form.quantity" 
              @input="validateQuantity" 
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-pink-500 focus:border-pink-500" 
              placeholder="Post Quantity" 
            />
            <span v-if="errorMessage" class="text-red-500 text-sm">{{ errorMessage }}</span>
          </div>
          <div class="mb-4">
            <label for="published_at" class="block text-sm font-medium text-gray-700">Published At</label>
            <input v-model="form.published_at" type="date" id="published_at" class="rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 w-full" />
          </div>
          <button type="submit" class="mt-2 px-4 py-2 bg-pink-500 hover:bg-red-700 text-white rounded-md transition duration-200">Create</button><br>
          <button type="button" @click="shareOnTwitter" class="mt-2 px-3 py-2 bg-blue-700 hover:bg-blue-900 text-white rounded-md transition duration-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path d="M23.954 4.569c-.885.389-1.83.654-2.825.775 1.014-.611 1.794-1.574 2.163-2.723-.951.555-2.005.959-3.127 1.184-.897-.959-2.178-1.559-3.594-1.559-2.717 0-4.92 2.203-4.92 4.917 0 .39.045.765.127 1.124-4.087-.205-7.719-2.165-10.148-5.144-.422.722-.666 1.561-.666 2.475 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.061c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.314 0-.615-.03-.916-.086.631 1.953 2.445 3.377 4.604 3.417-1.68 1.319-3.809 2.105-6.102 2.105-.39 0-.779-.023-1.17-.067 2.189 1.394 4.768 2.209 7.557 2.209 9.054 0 14.002-7.496 14.002-13.986 0-.21 0-.423-.015-.634.961-.689 1.8-1.56 2.46-2.548l-.047-.02z"/>
            </svg>
            Share on Twitter
          </button>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const form = useForm({
  title: "",
  body: "",
  quantity: "",
  published_at: '', // Ensure this field is included
});

const errorMessage = ref("");

const submit = () => {
  if (!form.title || !form.body || !form.quantity) {
    errorMessage.value = "Please fill in all fields to add a post.";
    return;
  }

  if (isNaN(form.quantity)) {
    errorMessage.value = "Quantity must be a valid number.";
    return;
  }

  errorMessage.value = "";
  form.post("/posts");
};

const validateQuantity = () => {
  if (isNaN(form.quantity)) {
    errorMessage.value = "Quantity must be a valid number.";
  } else {
    errorMessage.value = "";
  }
};

const shareOnTwitter = () => {
  if (!form.title || !form.body || !form.quantity) {
    errorMessage.value = "Please fill in all fields before sharing on Twitter.";
    return;
  }

  if (isNaN(form.quantity)) {
    errorMessage.value = "Quantity must be a valid number.";
    return;
  }

  errorMessage.value = "";

  // Submit the form to create the post
  form.post("/posts", {
    onSuccess: () => {
      // After the post is successfully created, share on Twitter
      const url = encodeURIComponent(window.location.href);
      const text = encodeURIComponent(`Check out this post: \nTitle: ${form.title}\nBody: ${form.body}\nQuantity: ${form.quantity}\n`);
      const twitterUrl = `https://twitter.com/intent/tweet?url=${url}&text=${text}`;
      window.open(twitterUrl, '_blank');
    }
  });
};
</script>