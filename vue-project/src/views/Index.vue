<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue'
import { type Files, type Folders } from '@/types/Files'
import Member from '@/components/Member.vue'
import Table from '@/components/Table.vue'
import Folder from '@/components/Folder.vue'

export interface MembersSelect {
  all: boolean
  admin: boolean
}

export interface FolderSelect {
  parent: string
  children: string
}

type SortDirection = 'asc' | 'desc' | null
export type SortColumn = 'name' | 'dimension' | 'size' | null

const folders = ref<Folders[]>([])
const sortDirection = ref<SortDirection>(null)
const currentSort = ref<SortColumn>(null)
const search = ref<string>('')
const membersSelect = reactive<MembersSelect>({
  all: true,
  admin: false,
})
const folderSelect = reactive<FolderSelect>({
  parent: 'Uploads',
  children: '',
})

const fetchFiles = async (): Promise<void> => {
  try {
    const response = await fetch('/data.json')
    const data: Folders[] = await response.json()
    folders.value = data
  } catch (error) {
    console.error('Error fetching files:', error)
  }
}

const selectAll = (event: Event): void => {
  const target = event.target as HTMLInputElement
  filesFilter.value.forEach((file) => (file.selected = target.checked))
}

const getTotalSizeInFolder = (folders: Folders[]): number => {
  return folders.reduce(
    (acc, folder) =>
      acc +
      folder.children.reduce(
        (childAcc, child) => childAcc + child.children.reduce((fileAcc, file) => fileAcc + Number(file.size), 0),
        0,
      ),
    0,
  )
}

const totalSizeUsedOfStorage = computed((): number => {
  const STORAGE_LIMIT = 2147483648
  return (getTotalSizeInFolder(folders.value) / STORAGE_LIMIT) * 100
})

const filesFilter = computed(() => {
  const folderParent = folders.value.find((folder) => folder.name === folderSelect.parent)

  if (!folderParent) {
    return []
  }

  let result = []

  if (folderSelect.children) {
    result = folderParent.children.find((child) => child.name === folderSelect.children)?.children || []
  } else {
    result = folderParent.children.reduce((allFiles: Files[], child) => {
      return [...allFiles, ...child.children]
    }, [])
  }

  result = result.filter((file) => {
    if (membersSelect.admin && !membersSelect.all) {
      return file.photo_by === 'Admin'
    }
    return true
  })

  if (search.value) {
    result = result.filter((file) => file.name.toLowerCase().includes(search.value.toLowerCase()))
  }

  if (currentSort.value && sortDirection.value) {
    result.sort((a: Files, b: Files) => {
      const modifier = sortDirection.value === 'asc' ? 1 : -1

      if (currentSort.value === 'size') {
        return (Number(a[currentSort.value as keyof Files]) - Number(b[currentSort.value as keyof Files])) * modifier
      }
      return (
        String(a[currentSort.value as keyof Files]).localeCompare(String(b[currentSort.value as keyof Files])) *
        modifier
      )
    })
  }

  return result
})

// Sorting functionality
const sortBy = (column: SortColumn): void => {
  if (!column) return

  if (currentSort.value === column) {
    if (sortDirection.value === 'asc') {
      sortDirection.value = null
      currentSort.value = null
    } else {
      sortDirection.value = 'asc'
    }
  } else {
    currentSort.value = column
    sortDirection.value = 'desc'
  }
}

onMounted(() => {
  fetchFiles()
})
</script>

<template>
  <div class="relative flex bg-white rounded border border-gray-100 shadow-sm">
    <aside class="w-64 bg-white shadow p-4 flex flex-col fixed top-0 border-t border-gray-100">
      <button class="bg-blue-600 text-white px-4 py-2 rounded">Import documents</button>
      <hr class="my-8 border-gray-300" />
      <div class="text-gray-600 text-sm">
        <div class="flex justify-between items-center">
          <span class="text-gray-600 font-medium">Storage</span>
          <button class="text-gray-600 flex items-center text-sm underline">Change plan</button>
        </div>
        <div class="w-full bg-gray-200 h-3 rounded-full mt-1">
          <div class="bg-blue-600 h-3 rounded-full" :style="{ width: `${totalSizeUsedOfStorage.toFixed(2)}%` }"></div>
        </div>
        <p class="text-blue-600 mt-2">
          {{ totalSizeUsedOfStorage.toFixed(2) }}% <label class="text-black text-sm font-medium">used of 2GB</label>
        </p>
      </div>
      <div class="mt-4 relative">
        <label class="text-gray-500 text-sm">Search</label>
        <div class="relative mt-2">
          <input
            type="text"
            v-model="search"
            placeholder="e.g. image.png"
            class="w-full border border-gray-300 outline-none rounded p-2 pr-8"
          />
          <i class="absolute right-3 top-3 text-gray-400 fas fa-search"></i>
        </div>
      </div>
      <Folder :folders="folders" :folderSelect="folderSelect" />
      <Member :membersSelect="membersSelect" />
    </aside>

    <Table
      :files="filesFilter"
      :currentSort="currentSort"
      :sortDirection="sortDirection"
      @selectAll="selectAll"
      @sortBy="sortBy"
    />
  </div>
</template>

<style>
th,
td {
  padding: 10px;
}
</style>
