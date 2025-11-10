<template>
  <AppLayout>
    <!-- Header with Create Button -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Petitions</h1>
      <a
        href="/petitions/create"
        class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-full hover:shadow-lg transition font-semibold"
      >
        + Create
      </a>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search petitions..."
        class="w-full px-4 py-3 bg-white rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500"
        @input="debouncedSearch"
      />
    </div>

    <!-- Petitions Feed -->
    <div class="space-y-4">
      <!-- Empty State -->
      <div v-if="petitions.data.length === 0" class="bg-white rounded-lg shadow p-12 text-center">
        <div class="text-6xl mb-4">📝</div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">No petitions yet</h3>
        <p class="text-gray-600 mb-6">Be the first to create a petition and make your voice heard!</p>
        <a
          href="/petitions/create"
          class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-3 rounded-full hover:shadow-lg transition font-semibold"
        >
          Create First Petition
        </a>
      </div>

      <!-- Petition Cards -->
      <div
        v-for="petition in petitions.data"
        :key="petition.id"
        class="bg-white rounded-lg shadow hover:shadow-lg transition"
      >
        <!-- Petition Header -->
        <div class="p-4 border-b border-gray-100">
          <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-600 to-pink-600 flex items-center justify-center text-white font-bold">
              {{ petition.creator?.name?.charAt(0) || 'U' }}
            </div>
            <div class="ml-3">
              <p class="font-semibold text-gray-900">{{ petition.creator?.name || 'Anonymous' }}</p>
              <p class="text-sm text-gray-500">{{ formatDate(petition.created_at) }}</p>
            </div>
          </div>
        </div>

        <!-- Petition Content -->
        <a :href="`/petitions/${petition.id}`" class="block p-4">
          <h2 class="text-xl font-bold text-gray-900 mb-2">{{ petition.title }}</h2>
          <p class="text-gray-600 mb-4 line-clamp-3">{{ petition.description }}</p>

          <!-- Category Badge -->
          <span class="inline-block px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium mb-4">
            {{ petition.category }}
          </span>

          <!-- Progress Bar -->
          <div class="mb-2">
            <div class="flex justify-between text-sm mb-1">
              <span class="font-semibold text-gray-900">{{ petition.votes_count || 0 }} signatures</span>
              <span class="text-gray-500">Goal: {{ petition.target_signatures || 100 }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div
                class="bg-gradient-to-r from-purple-600 to-pink-600 h-2 rounded-full transition-all"
                :style="{ width: `${Math.min((petition.votes_count || 0) / (petition.target_signatures || 100) * 100, 100)}%` }"
              ></div>
            </div>
          </div>
        </a>

        <!-- Petition Actions -->
        <div class="px-4 pb-4 flex items-center justify-between border-t border-gray-100 pt-4">
          <button class="flex items-center text-gray-600 hover:text-purple-600 transition">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
            </svg>
            <span class="font-semibold">Sign</span>
          </button>
          <button class="flex items-center text-gray-600 hover:text-purple-600 transition">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span class="font-semibold">{{ petition.comments_count || 0 }}</span>
          </button>
          <button class="flex items-center text-gray-600 hover:text-purple-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="petitions.last_page > 1" class="mt-8 flex justify-center space-x-2">
      <button
        v-for="page in petitions.last_page"
        :key="page"
        @click="goToPage(page)"
        :class="[
          'px-4 py-2 rounded-lg font-semibold transition',
          page === petitions.current_page
            ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white'
            : 'bg-white text-gray-700 hover:bg-gray-100'
        ]"
      >
        {{ page }}
      </button>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  petitions: Object,
  filters: Object
})

const searchQuery = ref(props.filters?.search || '')

let debounceTimer
const debouncedSearch = () => {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => {
    window.location.href = `/petitions?search=${searchQuery.value}`
  }, 300)
}

const formatDate = (date) => {
  const d = new Date(date)
  const now = new Date()
  const diff = Math.floor((now - d) / 1000)
  
  if (diff < 60) return 'Just now'
  if (diff < 3600) return `${Math.floor(diff / 60)}m ago`
  if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`
  if (diff < 604800) return `${Math.floor(diff / 86400)}d ago`
  return d.toLocaleDateString()
}

const goToPage = (page) => {
  window.location.href = `/petitions?page=${page}`
}
</script>
