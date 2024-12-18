<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-4">
    <form @submit.prevent="submit" class="space-y-4">
      
      <input type="text" id="title" v-model="form.title" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-pink-500 focus:border-pink-500" placeholder="Post Title" />
      <input type="text" id="body" v-model="form.body" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-pink-500 focus:border-pink-500" placeholder="Post Body" />
      <input type="text" id="body" v-model="form.quantity" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-pink-500 focus:border-pink-500" placeholder="Post Quantity" />
      <button type="submit" class="mt-2 px-4 py-2 bg-pink-500 hover:bg-red-700 text-white rounded-md transition duration-200">Submit</button>
   
    </form>
    </div>
        </div>
</AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from "@inertiajs/vue3";

const props = defineProps(['post']);
const form = useForm({
    title: props.post.title, // Initialize with current post title
    body: props.post.body,  
    quantity: props.post.quantity,  // Initialize with current post body
});

// Update the submit function to handle updates
const submit = () => {
    form.put(`/posts/${props.post.id}`); // Use PUT for updating the existing post
};
</script>
