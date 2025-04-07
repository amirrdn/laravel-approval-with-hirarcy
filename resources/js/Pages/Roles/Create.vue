<template>
  <Head title="Tambah Role Baru" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Role Baru</h2>
    </template>

    <div class="py-6">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-8 shadow-lg rounded-lg border border-gray-100">
          <form @submit.prevent="submit">
            <div class="space-y-6">
              <!-- Nama Role -->
              <div>
                <label for="name" class="text-gray-700 font-medium">Nama Role</label>
                <input
                  v-model="form.name"
                  id="name"
                  type="text"
                  placeholder="Masukkan nama role"
                  class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  required
                  autofocus
                />
                <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                  {{ form.errors.name }}
                </div>
              </div>

              <!-- Permissions -->
              <div>
                <label class="text-gray-700 font-medium mb-3 block">Permissions</label>
                <div
                  class="grid grid-cols-2 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg border border-gray-200"
                >
                  <label
                    v-for="permission in permissions"
                    :key="permission.id"
                    class="flex items-center space-x-3 py-2 px-3 rounded hover:bg-white transition duration-150"
                  >
                    <input
                      type="checkbox"
                      :value="permission.name"
                      v-model="form.permissions"
                      class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    />
                    <span class="text-sm text-gray-700">{{ permission.name }}</span>
                  </label>
                </div>
              </div>

              <!-- Parent Role -->
              <div>
                <label for="parent" class="text-gray-700 font-medium"
                  >Parent Role (opsional)</label
                >
                <select
                  id="role"
                  v-model="form.parent"
                  class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:bg-gray-100"
                >
                  <option value="" disabled selected>Pilih salah satu</option>
                  <option
                    v-for="role in hierarchicalRoles"
                    :key="role.id"
                    :value="role.id"
                  >
                    {{ "—".repeat(role.level) + " " + role.name }}
                  </option>
                </select>
                <div v-if="form.errors.parent" class="text-red-500 text-sm mt-1">
                  {{ form.errors.parent }}
                </div>
              </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-4 mt-8">
              <Link
                :href="route('roles.index')"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
              >
                Batal
              </Link>
              <button
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Simpan Role
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { useForm, Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
  permissions: Array,
  roles: Array,
});

const hierarchicalRoles = computed(() => {
  const buildHierarchy = (roles, parentId = 0, level = 0) => {
    const filteredRoles = roles.filter((role) => role.parent === parentId);

    return filteredRoles.flatMap((role) => [
      { ...role, level },
      ...buildHierarchy(roles, role.id, level + 1),
    ]);
  };

  return buildHierarchy(props.roles);
});

const form = useForm({
  name: "",
  permissions: [],
  parent: 0,
});

const submit = () => {
  form.post(route("roles.store"));
};
</script>
