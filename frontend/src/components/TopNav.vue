<template>
  <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b bg-background px-6">
    <div class="md:hidden">
      <router-link to="/" class="flex items-center gap-2 font-semibold">
        <FolderKanban class="h-6 w-6 text-primary" />
        <span>CREATE</span>
      </router-link>
    </div>
    <div class="w-full flex-1 md:gap-4 md:px-4 flex items-center">
      <slot></slot>
    </div>
  </header>
</template>

<script setup>
import { FolderKanban } from 'lucide-vue-next'
</script>