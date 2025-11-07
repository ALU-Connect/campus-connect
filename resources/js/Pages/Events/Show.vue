<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <Link :href="route('events.index')" class="text-green-600 hover:text-green-800 mb-4 inline-block">
          ← Back to Events
        </Link>
      </div>
    </div>

    <!-- Event Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-8">
          <!-- Date Badge -->
          <div class="flex items-start justify-between mb-6">
            <div class="bg-green-100 rounded-lg p-4 text-center">
              <p class="text-3xl font-bold text-green-800">
                {{ formatDay(event.event_date) }}
              </p>
              <p class="text-sm text-green-600 uppercase">
                {{ formatMonth(event.event_date) }}
              </p>
              <p class="text-xs text-green-600 mt-1">
                {{ formatYear(event.event_date) }}
              </p>
            </div>

            <!-- RSVP Button -->
            <form v-if="$page.props.auth.user" @submit.prevent="toggleRsvp">
              <button
                type="submit"
                :class="[
                  'px-6 py-3 rounded-lg font-semibold transition',
                  hasRsvped
                    ? 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                    : 'bg-green-600 text-white hover:bg-green-700'
                ]"
              >
                {{ hasRsvped ? 'Cancel RSVP' : 'RSVP' }}
              </button>
            </form>

            <Link
              v-else
              :href="route('login')"
              class="px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition"
            >
              Sign in to RSVP
            </Link>
          </div>

          <!-- Title -->
          <h1 class="text-3xl font-bold text-gray-900 mb-4">
            {{ event.title }}
          </h1>

          <!-- Event Details -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="flex items-start space-x-3">
              <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div>
                <p class="text-sm font-medium text-gray-700">Date & Time</p>
                <p class="text-gray-600">
                  {{ formatFullDate(event.event_date) }}
                </p>
                <p class="text-gray-600">
                  {{ formatTime(event.event_date) }}
                </p>
              </div>
            </div>

            <div class="flex items-start space-x-3">
              <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <div>
                <p class="text-sm font-medium text-gray-700">Location</p>
                <p class="text-gray-600">{{ event.location }}</p>
              </div>
            </div>
          </div>

          <!-- Organizer -->
          <div class="flex items-center mb-6 pb-6 border-b border-gray-200">
            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-green-600 flex items-center justify-center text-white font-semibold">
              {{ event.creator.name.charAt(0) }}
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-900">
                Organized by {{ event.creator.name }}
              </p>
              <p class="text-xs text-gray-500">Event Organizer</p>
            </div>
          </div>

          <!-- Description -->
          <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-3">About this event</h2>
            <div class="prose max-w-none">
              <p class="text-gray-700 whitespace-pre-line">{{ event.description }}</p>
            </div>
          </div>

          <!-- Linked Petition -->
          <div v-if="event.petition" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-sm font-medium text-blue-900 mb-2">
              This event was created from a successful petition
            </p>
            <Link
              :href="route('petitions.show', event.petition.id)"
              class="text-blue-600 hover:text-blue-800 font-semibold"
            >
              View the petition →
            </Link>
          </div>

          <!-- Attendees -->
          <div class="border-t border-gray-200 pt-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">
              Attendees ({{ attendeeCount }})
            </h2>

            <div v-if="event.rsvps && event.rsvps.length > 0" class="flex flex-wrap gap-2">
              <div
                v-for="rsvp in event.rsvps.slice(0, 10)"
                :key="rsvp.id"
                class="flex items-center space-x-2 bg-gray-100 rounded-full px-3 py-1"
              >
                <div class="h-6 w-6 rounded-full bg-green-600 flex items-center justify-center text-white text-xs font-semibold">
                  {{ rsvp.user.name.charAt(0) }}
                </div>
                <span class="text-sm text-gray-700">{{ rsvp.user.name }}</span>
              </div>
              <div v-if="attendeeCount > 10" class="flex items-center px-3 py-1">
                <span class="text-sm text-gray-500">
                  +{{ attendeeCount - 10 }} more
                </span>
              </div>
            </div>

            <p v-else class="text-gray-500">
              No one has RSVP'd yet. Be the first!
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
  event: Object,
  hasRsvped: Boolean,
  attendeeCount: Number,
});

const toggleRsvp = () => {
  router.post(route('events.rsvp', props.event.id), {}, {
    preserveScroll: true,
  });
};

const formatDay = (date) => {
  return new Date(date).getDate();
};

const formatMonth = (date) => {
  return new Date(date).toLocaleDateString('en-US', { month: 'short' });
};

const formatYear = (date) => {
  return new Date(date).getFullYear();
};

const formatFullDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    weekday: 'long',
    month: 'long',
    day: 'numeric',
    year: 'numeric',
  });
};

const formatTime = (date) => {
  return new Date(date).toLocaleTimeString('en-US', {
    hour: 'numeric',
    minute: '2-digit',
    hour12: true,
  });
};
</script>
