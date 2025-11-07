<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Create an Event</h1>
        <p class="text-gray-600 mb-8">
          Organize a campus event to bring the community together
        </p>

        <form @submit.prevent="submit">
          <!-- Title -->
          <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
              Event Title *
            </label>
            <input
              id="title"
              v-model="form.title"
              type="text"
              placeholder="e.g., Study Session for Final Exams"
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
              required
            />
            <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">
              {{ form.errors.title }}
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
              rows="6"
              placeholder="Describe what the event is about, what to expect, and any other important details..."
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
              required
            ></textarea>
            <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
              {{ form.errors.description }}
            </p>
          </div>

          <!-- Date and Time -->
          <div class="mb-6">
            <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">
              Date & Time *
            </label>
            <input
              id="event_date"
              v-model="form.event_date"
              type="datetime-local"
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
              required
            />
            <p v-if="form.errors.event_date" class="mt-1 text-sm text-red-600">
              {{ form.errors.event_date }}
            </p>
          </div>

          <!-- Location -->
          <div class="mb-6">
            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
              Location *
            </label>
            <input
              id="location"
              v-model="form.location"
              type="text"
              placeholder="e.g., Main Library, Room 201"
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
              required
            />
            <p v-if="form.errors.location" class="mt-1 text-sm text-red-600">
              {{ form.errors.location }}
            </p>
          </div>

          <!-- Max Attendees -->
          <div class="mb-6">
            <label for="max_attendees" class="block text-sm font-medium text-gray-700 mb-2">
              Maximum Attendees (Optional)
            </label>
            <input
              id="max_attendees"
              v-model.number="form.max_attendees"
              type="number"
              min="1"
              placeholder="Leave empty for unlimited"
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
            />
            <p class="mt-1 text-sm text-gray-500">
              Set a limit on the number of people who can RSVP
            </p>
            <p v-if="form.errors.max_attendees" class="mt-1 text-sm text-red-600">
              {{ form.errors.max_attendees }}
            </p>
          </div>

          <!-- Link to Petition -->
          <div v-if="petitions.length > 0" class="mb-8">
            <label for="petition_id" class="block text-sm font-medium text-gray-700 mb-2">
              Link to Petition (Optional)
            </label>
            <select
              id="petition_id"
              v-model="form.petition_id"
              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
            >
              <option :value="null">No petition</option>
              <option v-for="petition in petitions" :key="petition.id" :value="petition.id">
                {{ petition.title }}
              </option>
            </select>
            <p class="mt-1 text-sm text-gray-500">
              Link this event to one of your successful petitions
            </p>
            <p v-if="form.errors.petition_id" class="mt-1 text-sm text-red-600">
              {{ form.errors.petition_id }}
            </p>
          </div>

          <!-- Submit Buttons -->
          <div class="flex items-center justify-between">
            <Link
              :href="route('events.index')"
              class="px-6 py-3 text-gray-700 hover:text-gray-900 transition"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-8 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition disabled:opacity-50"
            >
              {{ form.processing ? 'Creating...' : 'Create Event' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
  petitions: Array,
});

const form = useForm({
  title: '',
  description: '',
  event_date: '',
  location: '',
  max_attendees: null,
  petition_id: null,
});

const submit = () => {
  form.post(route('events.store'));
};
</script>
