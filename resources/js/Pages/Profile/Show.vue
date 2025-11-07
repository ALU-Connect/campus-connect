<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Profile Header -->
    <div class="bg-white shadow">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-6">
            <!-- Profile Picture -->
            <div class="h-24 w-24 rounded-full bg-blue-600 flex items-center justify-center text-white text-3xl font-bold">
              {{ profileUser.name.charAt(0) }}
            </div>

            <!-- User Info -->
            <div>
              <h1 class="text-3xl font-bold text-gray-900">
                {{ profileUser.name }}
              </h1>
              <p class="text-gray-600 mt-1">{{ profileUser.email }}</p>
              <p v-if="profileUser.bio" class="text-gray-700 mt-2">
                {{ profileUser.bio }}
              </p>
            </div>
          </div>

          <!-- Edit Button (if own profile) -->
          <Link
            v-if="$page.props.auth.user && $page.props.auth.user.id === profileUser.id"
            :href="route('profile.edit')"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
          >
            Edit Profile
          </Link>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-6 mt-8">
          <div class="text-center">
            <p class="text-3xl font-bold text-gray-900">{{ stats.petitions_created }}</p>
            <p class="text-sm text-gray-600">Petitions Created</p>
          </div>
          <div class="text-center">
            <p class="text-3xl font-bold text-gray-900">{{ stats.events_created }}</p>
            <p class="text-sm text-gray-600">Events Organized</p>
          </div>
          <div class="text-center">
            <p class="text-3xl font-bold text-gray-900">{{ stats.total_votes }}</p>
            <p class="text-sm text-gray-600">Total Signatures</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Activity -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Petitions -->
        <div>
          <h2 class="text-2xl font-bold text-gray-900 mb-4">Recent Petitions</h2>
          
          <div v-if="profileUser.petitions && profileUser.petitions.length > 0" class="space-y-4">
            <div
              v-for="petition in profileUser.petitions"
              :key="petition.id"
              class="bg-white rounded-lg shadow p-4 hover:shadow-md transition cursor-pointer"
              @click="$inertia.visit(route('petitions.show', petition.id))"
            >
              <h3 class="font-semibold text-gray-900 mb-2">{{ petition.title }}</h3>
              <div class="flex items-center justify-between text-sm">
                <span class="text-gray-600">{{ petition.upvote_count }} signatures</span>
                <span class="text-gray-500">{{ formatDate(petition.created_at) }}</span>
              </div>
              <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                <div
                  class="bg-blue-600 h-1.5 rounded-full"
                  :style="{ width: `${Math.min((petition.upvote_count / petition.threshold) * 100, 100)}%` }"
                ></div>
              </div>
            </div>
          </div>

          <p v-else class="text-gray-500">No petitions created yet</p>
        </div>

        <!-- Events -->
        <div>
          <h2 class="text-2xl font-bold text-gray-900 mb-4">Upcoming Events</h2>
          
          <div v-if="profileUser.created_events && profileUser.created_events.length > 0" class="space-y-4">
            <div
              v-for="event in profileUser.created_events"
              :key="event.id"
              class="bg-white rounded-lg shadow p-4 hover:shadow-md transition cursor-pointer"
              @click="$inertia.visit(route('events.show', event.id))"
            >
              <div class="flex items-start space-x-3">
                <div class="bg-green-100 rounded-lg p-2 text-center flex-shrink-0">
                  <p class="text-lg font-bold text-green-800">
                    {{ formatDay(event.event_date) }}
                  </p>
                  <p class="text-xs text-green-600">
                    {{ formatMonth(event.event_date) }}
                  </p>
                </div>
                <div class="flex-1">
                  <h3 class="font-semibold text-gray-900">{{ event.title }}</h3>
                  <p class="text-sm text-gray-600 mt-1">{{ event.location }}</p>
                  <p class="text-xs text-gray-500 mt-1">
                    {{ formatTime(event.event_date) }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <p v-else class="text-gray-500">No upcoming events</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  profileUser: Object,
  stats: Object,
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
  });
};

const formatDay = (date) => {
  return new Date(date).getDate();
};

const formatMonth = (date) => {
  return new Date(date).toLocaleDateString('en-US', { month: 'short' });
};

const formatTime = (date) => {
  return new Date(date).toLocaleTimeString('en-US', {
    hour: 'numeric',
    minute: '2-digit',
    hour12: true,
  });
};
</script>
