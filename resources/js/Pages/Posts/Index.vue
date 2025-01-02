<script setup>
import { defineProps, ref } from 'vue'
import { useIntersectionObserver } from '@vueuse/core'
import { router } from '@inertiajs/vue3'
import axios from 'axios';

const props = defineProps({
  posts: Object
})

const loadMoreTrigger = ref(null)
const postsData = ref(props.posts.data)
const loading = ref(false)
const path = ref(props.posts.meta.path)
const next_cursor = ref(props.posts.meta.next_cursor)

// Sort posts in descending order by ID
postsData.value = [...postsData.value].sort((a, b) => b.id - a.id)

const {stop} = useIntersectionObserver(loadMoreTrigger, ([{ isIntersecting }]) => {
  console.log(isIntersecting);
  if (isIntersecting && props.posts.next_page_url && !loading.value) {
    loading.value = true
  } 

    axios.get(`${path.value}?cursor=${next_cursor.value}`).then((response) => {
      postsData.value = [...postsData.value, ...response.data.data];
      next_cursor.value = response.data.meta.next_cursor;

      if (!next_cursor.value) {
        stop();
      }
    });
  //   router.reload({
  //     data: {
  //       page: props.posts.meta.current_page + 1,
  //     },
  //     onSuccess: () => {
  //       // Sort and append new posts in descending order
  //       const newPosts = [...props.posts.data].sort((a, b) => b.id - a.id)
  //       postsData.value = [...postsData.value, ...newPosts]
  //       loading.value = false
  //     },
  //   })
  // }
});
</script>

<template>
  <!-- {{  props.posts.meta }} -->
  <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="grid gap-4">
      <div v-for="post in postsData" :key="post.id" class="bg-white p-6 rounded-lg shadow">
        <span class="text-sm text-gray-500">{{ post.id }}</span>
        <h2 class="text-xl font-bold">{{ post.title }}</h2>
        <p class="mt-2 text-gray-600">{{ post.teaser }}</p>
      </div>
      <div ref="lastElement" class="-translate-y-32"></div>
    </div>
    
    <!-- Intersection Observer Trigger -->
    <div ref="loadMoreTrigger" class="h-10 w-full"></div>
    
    <!-- Loading State -->
    <div v-if="loading" class="text-center py-4">
      Loading more posts...
    </div>
  </div>
</template>
