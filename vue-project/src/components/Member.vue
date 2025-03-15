<script setup lang="ts">
import type { MembersSelect } from '@/views/Index.vue'
import { ref } from 'vue'

interface Props {
  membersSelect: MembersSelect
}
const props = defineProps<Props>()
const membersSelect = ref(props.membersSelect)
</script>

<template>
  <div class="mt-4">
    <div class="flex justify-between items-center text-gray-600 text-sm font-medium">
      <span>Members</span>
      <button class="text-gray-600 text-sm underline flex items-center">Select all</button>
    </div>
    <div class="mt-2">
      <label class="flex items-center"><input type="checkbox" v-model="membersSelect.all" class="mr-2" /> All</label>
      <label class="flex items-center"
        ><input type="checkbox" v-model="membersSelect.admin" class="mr-2" /> Admin</label
      >
    </div>
  </div>
</template>
