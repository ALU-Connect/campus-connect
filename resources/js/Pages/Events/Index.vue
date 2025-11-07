<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex justify-between items-center">
          <h1 class="text-3xl font-bold text-gray-900">Campus Events</h1>
          <Link
            v-if="$page.props.auth.user"
            :href="route('events.create')"
            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition"
          >
            Create Event
          </Link>
        </div>

        <!-- Search -->
        <div class="mt-6">
          <input
            v-model="form.search"
            type="text"
            placeholder="Search events..."
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
            @input="search"
          />
        </div>
      </div>
    </div>

    <!-- Events List -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div v-if="events.data.length === 0" class="text-center py-12">
        <p class="text-gray-500 text-lg">No upcoming events</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="event in events.data"
          :key="event.id"
          class="bg-white rounded-lg shadow hover:shadow-lg transition cursor-pointer"
          @click="$inertia.visit(route('events.show', event.id))"
        >
          <div class="p-6">
            <!-- Date Badge -->
            <div class="flex items-start justify-between mb-4">
              <div class="bg-green-100 rounded-lg p-3 text-center">
                <p class="text-2xl font-bold text-green-800">
                  {{ formatDay(event.event_date) }}
                </p>
                <p class="text-xs text-green-600 uppercase">
                  {{ formatMonth(event.event_date) }}
                </p>
              </div>
              <span v-if="event.petition" class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                From Petition
              </span>
            </div>

            <!-- Title -->
            <h3 class="text-lg font-bold text-gray-900 mb-2">
              {{ event.title }}
            </h3>

            <!-- Description -->
            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
              {{ event.description }}
            </p>

            <!-- Location and Time -->
            <div class="space-y-2 mb-4">
              <div class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ event.location }}
              </div>
              <div class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ formatTime(event.event_date) }}
              </div>
            </div>

            <!-- Creator and Attendees -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
              <span class="text-xs text-gray-500">
                by {{ event.creator.name }}
              </span>
              <span class="text-xs font-semibold text-gray-700">
                {{ event.rsvps_count || 0 }} attending
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="events.links.length > 3" class="mt-8 flex justify-center">
        <nav class="flex space-x-2">
          <Link
            v-for="(link, index) in events.links"
            :key="index"
            :href="link.url"
            :class="[
              'px-4 py-2 rounded-lg',
              link.active
                ? 'bg-green-600 text-white'
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
import { reactive } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';

const props = defineProps({
  events: Object,
  filters: Object,
});

const form = reactive({
  search: props.filters.search || '',
});

const search = debounce(() => {
  router.get(route('events.index'), form, {
    preserveState: true,
    replace: true,
  });
}, 300);

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
