<template>
  <li class="mb-6">
    <div
      class="flex justify-between items-center py-2 px-4 bg-gray-100 rounded-lg shadow hover:bg-gray-200 transition mb-4"
    >
      <span class="font-semibold text-lg">{{ role.name }}</span>
      <div class="space-x-2">
        <Link
          :href="route('roles.edit', role.id)"
          class="bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 transition"
        >
          Edit
        </Link>
        <Link
          :href="route('roles.destroy', role.id)"
          method="delete"
          as="button"
          class="bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-700 transition"
        >
          Hapus
        </Link>
      </div>
    </div>
    <ul v-if="role.children.length" class="ml-4 border-l border-gray-300 pl-4">
      <RoleTree v-for="child in role.children" :key="child.id" :role="child" />
    </ul>
  </li>
</template>

<script setup>
import { defineProps } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
  role: {
    type: Object,
    required: true,
  },
});
</script>

<style scoped>
ul {
  list-style: none;
  padding-left: 0;
}
</style>
