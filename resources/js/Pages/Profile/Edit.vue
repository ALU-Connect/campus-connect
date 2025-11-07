<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Profile Information -->
      <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Profile Information</h2>

        <form @submit.prevent="updateProfile">
          <!-- Name -->
          <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
              Name *
            </label>
            <input
              id="name"
              v-model="profileForm.name"
              type="text"
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              required
            />
            <p v-if="profileForm.errors.name" class="mt-1 text-sm text-red-600">
              {{ profileForm.errors.name }}
            </p>
          </div>

          <!-- Email -->
          <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
              Email *
            </label>
            <input
              id="email"
              v-model="profileForm.email"
              type="email"
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              required
            />
            <p v-if="profileForm.errors.email" class="mt-1 text-sm text-red-600">
              {{ profileForm.errors.email }}
            </p>
          </div>

          <!-- Bio -->
          <div class="mb-6">
            <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
              Bio
            </label>
            <textarea
              id="bio"
              v-model="profileForm.bio"
              rows="4"
              placeholder="Tell us about yourself..."
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            ></textarea>
            <p class="mt-1 text-sm text-gray-500">
              Maximum 500 characters
            </p>
            <p v-if="profileForm.errors.bio" class="mt-1 text-sm text-red-600">
              {{ profileForm.errors.bio }}
            </p>
          </div>

          <!-- Profile Picture -->
          <div class="mb-6">
            <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-2">
              Profile Picture
            </label>
            <input
              id="profile_picture"
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
              Max 2MB. JPG, PNG, or GIF.
            </p>
            <p v-if="profileForm.errors.profile_picture" class="mt-1 text-sm text-red-600">
              {{ profileForm.errors.profile_picture }}
            </p>

            <!-- Image Preview -->
            <div v-if="imagePreview" class="mt-4">
              <img :src="imagePreview" alt="Preview" class="h-24 w-24 rounded-full object-cover" />
            </div>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end">
            <button
              type="submit"
              :disabled="profileForm.processing"
              class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition disabled:opacity-50"
            >
              {{ profileForm.processing ? 'Saving...' : 'Save Changes' }}
            </button>
          </div>
        </form>
      </div>

      <!-- Change Password -->
      <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Change Password</h2>

        <form @submit.prevent="updatePassword">
          <!-- Current Password -->
          <div class="mb-6">
            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
              Current Password *
            </label>
            <input
              id="current_password"
              v-model="passwordForm.current_password"
              type="password"
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              required
            />
            <p v-if="passwordForm.errors.current_password" class="mt-1 text-sm text-red-600">
              {{ passwordForm.errors.current_password }}
            </p>
          </div>

          <!-- New Password -->
          <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
              New Password *
            </label>
            <input
              id="password"
              v-model="passwordForm.password"
              type="password"
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              required
            />
            <p class="mt-1 text-sm text-gray-500">
              Minimum 8 characters
            </p>
            <p v-if="passwordForm.errors.password" class="mt-1 text-sm text-red-600">
              {{ passwordForm.errors.password }}
            </p>
          </div>

          <!-- Confirm Password -->
          <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
              Confirm New Password *
            </label>
            <input
              id="password_confirmation"
              v-model="passwordForm.password_confirmation"
              type="password"
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              required
            />
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end">
            <button
              type="submit"
              :disabled="passwordForm.processing"
              class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition disabled:opacity-50"
            >
              {{ passwordForm.processing ? 'Updating...' : 'Update Password' }}
            </button>
          </div>
        </form>
      </div>

      <!-- Back to Profile -->
      <div class="mt-6 text-center">
        <Link
          :href="route('profile.show', $page.props.auth.user)"
          class="text-blue-600 hover:text-blue-800"
        >
          ← Back to Profile
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
  user: Object,
});

const profileForm = useForm({
  name: props.user.name,
  email: props.user.email,
  bio: props.user.bio || '',
  profile_picture: null,
});

const passwordForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const imagePreview = ref(null);

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    profileForm.profile_picture = file;
    
    // Create preview
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const updateProfile = () => {
  profileForm.put(route('profile.update'), {
    preserveScroll: true,
  });
};

const updatePassword = () => {
  passwordForm.put(route('profile.password'), {
    preserveScroll: true,
    onSuccess: () => {
      passwordForm.reset();
    },
  });
};
</script>
