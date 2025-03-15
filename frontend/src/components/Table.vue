<script setup lang="ts">
import { Files } from '@/type/Files'

interface Props {
  files: Files[]
  currentSort: string | null
  sortDirection: 'asc' | 'desc' | null
}
defineProps<Props>()

const emit = defineEmits<{
  (e: 'selectAll', event: Event): void
  (e: 'sortBy', value: string): void
}>()

const formatToKB = (bytes: string): string => {
  return `${(Number(bytes) / 1024).toFixed(1)} KB`
}
</script>

<template>
  <main class="flex-1 p-4 pl-64">
    <table class="w-full ml-8">
      <thead>
        <tr class="text-gray-500 text-sm font-medium text-left">
          <th class="flex items-center gap-3">
            <input type="checkbox" @click="emit('selectAll', $event)" />
            <span class="text-gray-600 text-sm font-medium">Select all</span>
          </th>
          <th @click="emit('sortBy', 'name')" class="cursor-pointer">
            Name
            <i
              v-if="currentSort === 'name'"
              :class="{
                'fas fa-sort-up': sortDirection === 'asc',
                'fas fa-sort-down': sortDirection === 'desc',
              }"
              class="text-gray-500 text-[10px]"
            ></i>
            <i v-else class="fas fa-sort text-gray-500 text-[10px]"></i>
          </th>
          <th @click="emit('sortBy', 'dimension')" class="cursor-pointer">
            Dimension
            <i
              v-if="currentSort === 'dimension'"
              :class="{
                'fas fa-sort-up': sortDirection === 'asc',
                'fas fa-sort-down': sortDirection === 'desc',
              }"
              class="text-gray-500 text-[10px]"
            ></i>
            <i v-else class="fas fa-sort text-gray-500 text-[10px]"></i>
          </th>
          <th @click="emit('sortBy', 'size')" class="cursor-pointer">
            Size
            <i
              v-if="currentSort === 'size'"
              :class="{
                'fas fa-sort-up': sortDirection === 'asc',
                'fas fa-sort-down': sortDirection === 'desc',
              }"
              class="text-gray-500 text-[10px]"
            ></i>
            <i v-else class="fas fa-sort text-gray-500 text-[10px]"></i>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="file in files" :key="file.id" class="hover:bg-gray-100">
          <td class="flex items-center gap-8">
            <input type="checkbox" v-model="file.selected" />
            <img :src="file.url" class="w-80 rounded-sm" />
          </td>
          <td>{{ file.name }}</td>
          <td>{{ file.dimension }}</td>
          <td>{{ formatToKB(file.size) }}</td>
        </tr>
      </tbody>
    </table>
  </main>
</template>

<style>
th,
td {
  padding: 10px;
}
</style>
