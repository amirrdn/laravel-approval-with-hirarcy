<template>
  <Head title="Role" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-white leading-tight">Edit User</h2>
    </template>
    <div class="p-4">
      <div>
        <Link
          :href="route('roles.create')"
          class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 inline-flex items-center space-x-1 transition duration-150 ease-in-out"
        >
          <PlusIcon class="h-5 w-5" />
          <span>Tambah Role</span>
        </Link>
        <ul>
          <RoleTree v-for="role in treeRoles" :key="role.id" :role="role" />
        </ul>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { defineProps, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import RoleTree from "./RoleTree.vue";
import { Link, Head } from "@inertiajs/vue3";
import { PlusIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
  roles: {
    type: Array,
    required: true,
  },
});
const buildTree = (flatRoles, parentId = 0) => {
  return flatRoles
    .filter((role) => role.parent === parentId)
    .map((role) => ({
      ...role,
      children: buildTree(flatRoles, role.id),
    }));
};

const treeRoles = computed(() => buildTree(props.roles));

const editRole = (id) => {
  console.log(`Editing role with ID: ${id}`);
};

const deleteRole = (id) => {
  if (confirm("Apakah Anda yakin ingin menghapus peran ini?")) {
    console.log(`Deleting role with ID: ${id}`);
  }
};
</script>

<style scoped></style>
