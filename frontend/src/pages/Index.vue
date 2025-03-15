<script setup lang="ts">
import { Files, Folders, FolderChildren } from '@/type/Files'
import Table from '@/components/Table.vue'

interface MembersSelect {
  all: boolean
  admin: boolean
}

type SortDirection = 'asc' | 'desc' | null
type SortColumn = 'name' | 'dimension' | 'size' | null

const openFolders = ref<string[]>(['Uploads'])
const folders = ref<Folders[]>([])
const files = ref<Files[]>([])
const filesFilter = ref<Files[]>([])
const sortDirection = ref<SortDirection>(null)
const currentSort = ref<SortColumn>(null)
const membersSelect = reactive<MembersSelect>({
  all: true,
  admin: false,
})

const fetchFiles = async (): Promise<void> => {
  try {
    const response = await fetch('/data.json')
    const data: Folders[] = await response.json()
    folders.value = data

    const uploadsFolder = data.find(folder => folder.name === 'Uploads')
    if (uploadsFolder) {
      const dataFiles = uploadsFolder.children.reduce((allFiles: Files[], child) => {
        return [...allFiles, ...child.children]
      }, [])
      files.value = dataFiles
      filesFilter.value = dataFiles
    }
  } catch (error) {
    console.error('Error fetching files:', error)
  }
}

// Folder management
const toggleFolder = (folder: string): void => {
  const index = openFolders.value.indexOf(folder)
  if (index > -1) {
    openFolders.value.splice(index, 1)
  } else {
    openFolders.value.push(folder)
  }
}

// Sorting functionality
const sortBy = (column: SortColumn): void => {
  if (!column) return

  if (currentSort.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    currentSort.value = column
    sortDirection.value = 'asc'
  }

  filesFilter.value.sort((a: Files, b: Files) => {
    const modifier = sortDirection.value === 'asc' ? 1 : -1

    if (column === 'size') {
      return (Number(a[column]) - Number(b[column])) * modifier
    }
    return String(a[column]).localeCompare(String(b[column])) * modifier
  })
}

const selectAll = (event: Event): void => {
  const target = event.target as HTMLInputElement
  filesFilter.value.forEach(file => (file.selected = target.checked))
}

const getTotalFilesInFolder = (folder: Folders): number => {
  return folder.children.reduce((acc, child: FolderChildren) => acc + child.children.length, 0)
}

const getTotalSizeInFolder = (folders: Folders[]): number => {
  return folders.reduce(
    (acc, folder) =>
      acc +
      folder.children.reduce(
        (childAcc, child) => childAcc + child.children.reduce((fileAcc, file) => fileAcc + Number(file.size), 0),
        0
      ),
    0
  )
}

const totalSizeUsedOfStorage = computed((): number => {
  const STORAGE_LIMIT = 2147483648
  return (getTotalSizeInFolder(folders.value) / STORAGE_LIMIT) * 100
})

watch(membersSelect, (newVal: MembersSelect) => {
  currentSort.value = null
  sortDirection.value = null

  filesFilter.value = files.value.filter(file => {
    if (newVal.admin) return file.photo_by === 'Admin'
    return true
  })
})

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
        <div class="w-full bg-gray-200 h-3 rounded mt-1">
          <div class="bg-blue-600 h-3 rounded" :style="{ width: `${totalSizeUsedOfStorage.toFixed(2)}%` }"></div>
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
            placeholder="e.g. image.png"
            class="w-full border border-gray-300 outline-none rounded p-2 pr-8"
          />
          <i class="absolute right-3 top-3 text-gray-400 fas fa-search"></i>
        </div>
      </div>
      <div class="mt-4">
        <div class="flex justify-between items-center text-gray-600 text-sm font-medium">
          <span>Folders</span>
          <button class="text-gray-600 text-sm underline flex items-center">New folder</button>
        </div>
        <ul class="mt-2 flex flex-col gap-2">
          <li v-for="folder in folders" :key="folder.id">
            <button class="flex items-center text-gray-700 cursor-pointer" @click="toggleFolder(folder.name)">
              <i
                :class="{
                  'fas fa-caret-right': !openFolders.includes(folder.name),
                  'fas fa-caret-down': openFolders.includes(folder.name),
                }"
                class="mr-4"
              ></i>
              <i class="fas fa-folder-open mr-2"></i>
              {{ folder.name }}
              <span class="ml-3 text-[12px] text-black w-4 h-4 bg-gray-300 rounded-sm flex items-center justify-center">
                {{ getTotalFilesInFolder(folder) }}
              </span>
            </button>
            <ul
              v-if="openFolders.includes(folder.name) && folder.children.length > 0"
              class="flex flex-col gap-1 ml-6 mt-1"
            >
              <li v-for="child in folder.children" :key="child.id" class="cursor-pointer flex items-center">
                <i
                  :class="{
                    'fas fa-caret-right': !openFolders.includes(child.name),
                    'fas fa-caret-down': openFolders.includes(child.name),
                  }"
                  class="mr-4"
                ></i>
                <i class="fas fa-folder-open mr-2"></i>
                {{ child.name }}
                <span
                  class="ml-3 text-[12px] text-black w-4 h-4 bg-gray-300 rounded-sm flex items-center justify-center"
                >
                  {{ child.children.length }}
                </span>
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <div class="mt-4">
        <div class="flex justify-between items-center text-gray-600 text-sm font-medium">
          <span>Members</span>
          <button class="text-gray-600 text-sm underline flex items-center">Select all</button>
        </div>
        <div class="mt-2">
          <label class="flex items-center"
            ><input type="checkbox" v-model="membersSelect.all" class="mr-2" /> All</label
          >
          <label class="flex items-center"
            ><input type="checkbox" v-model="membersSelect.admin" class="mr-2" /> Admin</label
          >
        </div>
      </div>
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
