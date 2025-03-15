<script setup lang="ts">
import { type Files } from '@/types/Files'
import { type SortColumn } from '@/views/Index.vue'
interface Props {
  files: Files[]
  currentSort: SortColumn | null
  sortDirection: 'asc' | 'desc' | null
}
const props = defineProps<Props>()

const emit = defineEmits<{
  (e: 'selectAll', event: Event): void
  (e: 'sortBy', value: SortColumn): void
}>()

const formatToKB = (bytes: string): string => {
  return `${(Number(bytes) / 1024).toFixed(1)} KB`
}

const getSortIcon = (column: SortColumn) => {
  if (props.currentSort === column) {
    return {
      icon: props.sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down',
      class: 'text-gray-500 text-[10px]',
    }
  }
  return {
    icon: 'fa-sort',
    class: 'text-gray-500 text-[10px]',
  }
}
</script>

<template>
  <main class="flex-1 p-4 pl-64">
    <table class="w-full ml-8">
      <thead>
        <tr class="text-gray-500 text-sm text-left">
          <th class="flex items-center gap-3">
            <input type="checkbox" @click="emit('selectAll', $event)" />
            <span class="text-gray-500 text-sm">Select all</span>
          </th>
          <th @click="emit('sortBy', 'name')" class="cursor-pointer">
            Name
            <i class="fas" :class="[getSortIcon('name').icon, getSortIcon('name').class]"></i>
          </th>
          <th @click="emit('sortBy', 'dimension')" class="cursor-pointer">
            Dimension
            <i class="fas" :class="[getSortIcon('dimension').icon, getSortIcon('dimension').class]"></i>
          </th>
          <th @click="emit('sortBy', 'size')" class="cursor-pointer">
            Size
            <i class="fas" :class="[getSortIcon('size').icon, getSortIcon('size').class]"></i>
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

<style scoped>
th,
td {
  padding: 10px;
}
</style>
