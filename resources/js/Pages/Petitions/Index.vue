<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex justify-between items-center">
          <h1 class="text-3xl font-bold text-gray-900">Petitions</h1>
          <Link
            v-if="$page.props.auth.user"
            :href="route('petitions.create')"
            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
          >
            Create Petition
          </Link>
        </div>

        <!-- Search and Filters -->
        <div class="mt-6 flex flex-col sm:flex-row gap-4">
          <input
            v-model="form.search"
            type="text"
            placeholder="Search petitions..."
            class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            @input="search"
          />
          
          <select
            v-model="form.category"
            class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            @change="search"
          >
            <option value="all">All Categories</option>
            <option value="academic">Academic</option>
            <option value="facilities">Facilities</option>
            <option value="food">Food</option>
            <option value="housing">Housing</option>
            <option value="other">Other</option>
          </select>

          <select
            v-model="form.sort"
            class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            @change="search"
          >
            <option value="recent">Most Recent</option>
            <option value="popular">Most Popular</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Petitions Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div v-if="petitions.data.length === 0" class="text-center py-12">
        <p class="text-gray-500 text-lg">No petitions found</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="petition in petitions.data"
          :key="petition.id"
          class="bg-white rounded-lg shadow hover:shadow-lg transition cursor-pointer"
          @click="$inertia.visit(route('petitions.show', petition.id))"
        >
          <div v-if="petition.image_path" class="h-48 overflow-hidden rounded-t-lg">
            <img
              :src="`/storage/${petition.image_path}`"
              :alt="petition.title"
              class="w-full h-full object-cover"
            />
          </div>
          
          <div class="p-6">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-semibold text-blue-600 uppercase">
                {{ petition.category }}
              </span>
              <span class="text-xs text-gray-500">
                {{ formatDate(petition.created_at) }}
              </span>
            </div>

            <h3 class="text-lg font-bold text-gray-900 mb-2">
              {{ petition.title }}
            </h3>

            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
              {{ petition.description }}
            </p>

            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                </svg>
                <span class="text-sm font-semibold text-gray-700">
                  {{ petition.upvote_count }}
                </span>
              </div>

              <span class="text-xs text-gray-500">
                by {{ petition.creator.name }}
              </span>
            </div>

            <!-- Progress Bar -->
            <div class="mt-4">
              <div class="flex justify-between text-xs text-gray-600 mb-1">
                <span>{{ petition.upvote_count }} / {{ petition.threshold }} signatures</span>
                <span>{{ Math.round((petition.upvote_count / petition.threshold) * 100) }}%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div
                  class="bg-blue-600 h-2 rounded-full transition-all"
                  :style="{ width: `${Math.min((petition.upvote_count / petition.threshold) * 100, 100)}%` }"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="petitions.links.length > 3" class="mt-8 flex justify-center">
        <nav class="flex space-x-2">
          <Link
            v-for="(link, index) in petitions.links"
            :key="index"
            :href="link.url"
            :class="[
              'px-4 py-2 rounded-lg',
              link.active
                ? 'bg-blue-600 text-white'
                : 'bg-white text-gray-700 hover:bg-gray-100'
            ]"
            v-html="link.label"
          />
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';

const props = defineProps({
  petitions: Object,
  filters: Object,
});

const form = reactive({
  search: props.filters.search || '',
  category: props.filters.category || 'all',
  sort: props.filters.sort || 'recent',
});

const search = debounce(() => {
  router.get(route('petitions.index'), form, {
    preserveState: true,
    replace: true,
  });
}, 300);

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
  });
};
</script>
