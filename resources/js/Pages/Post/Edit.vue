<template>
    <Head title="Edit" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-4">
                <h1 class="text-2xl font-bold mb-4">Edit Post</h1>
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
                    <button type="submit" class="mt-2 px-4 py-2 bg-pink-500 hover:bg-red-700 text-white rounded-md transition duration-200">Submit</button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from '@inertiajs/vue3';

const props = defineProps(['post']);
const form = useForm({
    title: props.post.title, 
    body: props.post.body,  
    quantity: props.post.quantity,  
});

const errorMessage = ref("");

const submit = () => {
    if (!form.title || !form.body || !form.quantity) {
        errorMessage.value = "Please fill in all fields to edit the post.";
        return;
    }

    if (isNaN(form.quantity)) {
        errorMessage.value = "Quantity must be a valid number.";
        return;
    }

    errorMessage.value = "";
    form.put(`/posts/${props.post.id}`, {
        onSuccess: () => {
            alert("Your post has been updated successfully!");
        }
    });
};

const validateQuantity = () => {
    if (isNaN(form.quantity)) {
        errorMessage.value = "Quantity must be a valid number.";
    } else {
        errorMessage.value = "";
    }
};
</script>