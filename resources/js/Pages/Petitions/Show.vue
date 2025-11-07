<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <Link :href="route('petitions.index')" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
          ← Back to Petitions
        </Link>
      </div>
    </div>

    <!-- Petition Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Main Petition Card -->
      <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <!-- Image -->
        <div v-if="petition.image_path" class="h-64 overflow-hidden">
          <img
            :src="`/storage/${petition.image_path}`"
            :alt="petition.title"
            class="w-full h-full object-cover"
          />
        </div>

        <div class="p-8">
          <!-- Category and Date -->
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-semibold text-blue-600 uppercase">
              {{ petition.category }}
            </span>
            <span class="text-sm text-gray-500">
              {{ formatDate(petition.created_at) }}
            </span>
          </div>

          <!-- Title -->
          <h1 class="text-3xl font-bold text-gray-900 mb-4">
            {{ petition.title }}
          </h1>

          <!-- Creator -->
          <div class="flex items-center mb-6">
            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold">
              {{ petition.creator.name.charAt(0) }}
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-gray-900">
                {{ petition.creator.name }}
              </p>
              <p class="text-xs text-gray-500">Petition Creator</p>
            </div>
          </div>

          <!-- Description -->
          <div class="prose max-w-none mb-8">
            <p class="text-gray-700 whitespace-pre-line">{{ petition.description }}</p>
          </div>

          <!-- Voting Section -->
          <div class="border-t border-b border-gray-200 py-6 mb-6">
            <div class="flex items-center justify-between mb-4">
              <div>
                <p class="text-2xl font-bold text-gray-900">
                  {{ petition.upvote_count }} signatures
                </p>
                <p class="text-sm text-gray-500">
                  Goal: {{ petition.threshold }} signatures
                </p>
              </div>

              <form v-if="$page.props.auth.user" @submit.prevent="toggleVote">
                <button
                  type="submit"
                  :class="[
                    'px-6 py-3 rounded-lg font-semibold transition',
                    hasVoted
                      ? 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                      : 'bg-blue-600 text-white hover:bg-blue-700'
                  ]"
                >
                  {{ hasVoted ? 'Remove Signature' : 'Sign Petition' }}
                </button>
              </form>

              <Link
                v-else
                :href="route('login')"
                class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition"
              >
                Sign in to Support
              </Link>
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-gray-200 rounded-full h-3">
              <div
                class="bg-blue-600 h-3 rounded-full transition-all"
                :style="{ width: `${Math.min((petition.upvote_count / petition.threshold) * 100, 100)}%` }"
              ></div>
            </div>
            <p class="text-sm text-gray-600 mt-2 text-right">
              {{ Math.round((petition.upvote_count / petition.threshold) * 100) }}% of goal
            </p>
          </div>

          <!-- Status Badge -->
          <div v-if="petition.upvote_count >= petition.threshold" class="bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-green-800 font-semibold">
              🎉 This petition has reached its goal!
            </p>
          </div>
        </div>
      </div>

      <!-- Comments/Threads Section -->
      <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">
          Discussion ({{ petition.threads.length }})
        </h2>

        <!-- Add Comment Form -->
        <form v-if="$page.props.auth.user" @submit.prevent="submitComment" class="mb-8">
          <textarea
            v-model="commentForm.content"
            rows="3"
            placeholder="Add your thoughts..."
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            required
          ></textarea>
          <button
            type="submit"
            class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
          >
            Post Comment
          </button>
        </form>

        <div v-else class="mb-8 p-4 bg-gray-50 rounded-lg text-center">
          <Link :href="route('login')" class="text-blue-600 hover:text-blue-800">
            Sign in to join the discussion
          </Link>
        </div>

        <!-- Comments List -->
        <div v-if="petition.threads.length === 0" class="text-center py-8 text-gray-500">
          No comments yet. Be the first to share your thoughts!
        </div>

        <div v-else class="space-y-6">
          <div
            v-for="thread in petition.threads"
            :key="thread.id"
            class="border-l-2 border-gray-200 pl-4"
          >
            <div class="flex items-start space-x-3">
              <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-700 font-semibold text-sm">
                {{ thread.user.name.charAt(0) }}
              </div>
              <div class="flex-1">
                <div class="flex items-center space-x-2">
                  <span class="font-semibold text-gray-900">{{ thread.user.name }}</span>
                  <span class="text-xs text-gray-500">{{ formatDate(thread.created_at) }}</span>
                </div>
                <p class="text-gray-700 mt-1">{{ thread.content }}</p>

                <!-- Replies -->
                <div v-if="thread.replies && thread.replies.length > 0" class="mt-4 space-y-4">
                  <div
                    v-for="reply in thread.replies"
                    :key="reply.id"
                    class="flex items-start space-x-3 pl-4 border-l-2 border-gray-100"
                  >
                    <div class="flex-shrink-0 h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-semibold text-xs">
                      {{ reply.user.name.charAt(0) }}
                    </div>
                    <div class="flex-1">
                      <div class="flex items-center space-x-2">
                        <span class="font-medium text-sm text-gray-900">{{ reply.user.name }}</span>
                        <span class="text-xs text-gray-500">{{ formatDate(reply.created_at) }}</span>
                      </div>
                      <p class="text-sm text-gray-700 mt-1">{{ reply.content }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
  petition: Object,
  hasVoted: Boolean,
});

const commentForm = reactive({
  content: '',
});

const toggleVote = () => {
  router.post(route('petitions.vote', props.petition.id), {}, {
    preserveScroll: true,
  });
};

const submitComment = () => {
  router.post(route('petitions.comment', props.petition.id), commentForm, {
    preserveScroll: true,
    onSuccess: () => {
      commentForm.content = '';
    },
  });
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    month: 'long',
    day: 'numeric',
    year: 'numeric',
  });
};
</script>
