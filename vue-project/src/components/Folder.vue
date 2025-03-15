<script setup lang="ts">
import type { FolderChildren, Folders } from '@/types/Files'
import type { FolderSelect } from '@/views/Index.vue'
interface Props {
  folders: Folders[]
  folderSelect: FolderSelect
}
const props = defineProps<Props>()

const getTotalFilesInFolder = (folder: Folders): number => {
  return folder.children.reduce((acc, child: FolderChildren) => acc + child.children.length, 0)
}

// Folder management
const toggleFolder = (folder: string, children: string): void => {
  // eslint-disable-next-line vue/no-mutating-props
  props.folderSelect.parent = folder
  // eslint-disable-next-line vue/no-mutating-props
  props.folderSelect.children = children
}
</script>

<template>
  <div class="mt-4">
    <div class="flex justify-between items-center text-gray-600 text-sm font-medium">
      <span>Folders</span>
      <button class="text-gray-600 text-sm underline flex items-center">New folder</button>
    </div>
    <ul class="mt-2 flex flex-col gap-2">
      <li v-for="folder in folders" :key="folder.id">
        <button
          class="flex items-center cursor-pointer"
          @click="toggleFolder(folder.name, '')"
          :class="{
            'text-black': folder.name === folderSelect.parent,
            'text-gray-400': folder.name !== folderSelect.parent,
          }"
        >
          <i
            :class="{
              'fas fa-caret-right': folderSelect.parent !== folder.name,
              'fas fa-caret-down': folderSelect.parent === folder.name,
            }"
            class="mr-4"
          ></i>
          <i class="fas fa-folder-open mr-2"></i>
          <span class="text-black">{{ folder.name }}</span>
          <span
            class="font-medium ml-3 text-[12px] text-black w-4 h-4 bg-gray-300 rounded-sm flex items-center justify-center"
          >
            {{ getTotalFilesInFolder(folder) }}
          </span>
        </button>
        <ul
          v-if="folderSelect.parent === folder.name && folder.children.length > 0"
          class="flex text-gray-400 flex-col gap-1 ml-6 mt-1"
        >
          <li
            v-for="child in folder.children"
            :key="child.id"
            class="cursor-pointer flex items-center px-2 py-1 rounded-sm"
            @click="toggleFolder(folder.name, child.name)"
            :class="{
              'text-blue-600 bg-blue-50': folderSelect.children === child.name,
              'text-gray-400': folderSelect.children !== child.name,
            }"
          >
            <i
              :class="{
                'fas fa-caret-right': folderSelect.children !== child.name,
                'fas fa-caret-down': folderSelect.children === child.name,
              }"
              class="mr-4"
            ></i>
            <i class="fas fa-folder-open mr-2"></i>
            <span :class="{ 'text-blue-600': folderSelect.children === child.name }">{{ child.name }}</span>
            <span
              class="font-medium ml-3 text-[12px] w-4 h-4 rounded-sm flex items-center justify-center"
              :class="{
                'bg-blue-600 text-white': folderSelect.children === child.name,
                'bg-gray-300 text-black': folderSelect.children !== child.name,
              }"
            >
              {{ child.children.length }}
            </span>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</template>
