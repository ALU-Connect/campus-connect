<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Create a Petition</h1>
        <p class="text-gray-600 mb-8">
          Start a petition to bring about change on campus
        </p>

        <form @submit.prevent="submit">
          <!-- Title -->
          <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
              Petition Title *
            </label>
            <input
              id="title"
              v-model="form.title"
              type="text"
              placeholder="e.g., Extend Library Hours During Exam Period"
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              required
            />
            <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">
              {{ form.errors.title }}
            </p>
          </div>

          <!-- Category -->
          <div class="mb-6">
            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
              Category *
            </label>
            <select
              id="category"
              v-model="form.category"
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              required
            >
              <option value="">Select a category</option>
              <option value="academic">Academic</option>
              <option value="facilities">Facilities</option>
              <option value="food">Food & Dining</option>
              <option value="housing">Housing</option>
              <option value="health">Health & Wellness</option>
              <option value="other">Other</option>
            </select>
            <p v-if="form.errors.category" class="mt-1 text-sm text-red-600">
              {{ form.errors.category }}
            </p>
          </div>

          <!-- Description -->
          <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
              Description *
            </label>
            <textarea
              id="description"
              v-model="form.description"
              rows="8"
              placeholder="Explain why this petition is important and what you hope to achieve..."
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              required
            ></textarea>
            <p class="mt-1 text-sm text-gray-500">
              Provide details about the issue and your proposed solution
            </p>
            <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
              {{ form.errors.description }}
            </p>
          </div>

          <!-- Signature Goal -->
          <div class="mb-6">
            <label for="threshold" class="block text-sm font-medium text-gray-700 mb-2">
              Signature Goal
            </label>
            <input
              id="threshold"
              v-model.number="form.threshold"
              type="number"
              min="10"
              placeholder="100"
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
            <p class="mt-1 text-sm text-gray-500">
              How many signatures do you need? (Default: 100)
            </p>
            <p v-if="form.errors.threshold" class="mt-1 text-sm text-red-600">
              {{ form.errors.threshold }}
            </p>
          </div>

          <!-- Image Upload -->
          <div class="mb-8">
            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
              Petition Image (Optional)
            </label>
            <input
              id="image"
              type="file"
              accept="image/*"
              @change="handleImageUpload"
              class="w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-lg file:border-0
                file:text-sm file:font-semibold
                file:bg-blue-50 file:text-blue-700
                hover:file:bg-blue-100"
            />
            <p class="mt-1 text-sm text-gray-500">
              Add an image to make your petition more compelling (Max 2MB)
            </p>
            <p v-if="form.errors.image" class="mt-1 text-sm text-red-600">
              {{ form.errors.image }}
            </p>

            <!-- Image Preview -->
            <div v-if="imagePreview" class="mt-4">
              <img :src="imagePreview" alt="Preview" class="h-48 rounded-lg object-cover" />
            </div>
          </div>

          <!-- Submit Buttons -->
          <div class="flex items-center justify-between">
            <Link
              :href="route('petitions.index')"
              class="px-6 py-3 text-gray-700 hover:text-gray-900 transition"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition disabled:opacity-50"
            >
              {{ form.processing ? 'Creating...' : 'Create Petition' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
  title: '',
  category: '',
  description: '',
  threshold: 100,
  image: null,
});

const imagePreview = ref(null);

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.image = file;
    
    // Create preview
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const submit = () => {
  form.post(route('petitions.store'));
};
</script>
