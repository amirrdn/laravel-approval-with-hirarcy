<template>
  <Head :title="`Edit Role ` + role.name" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Edit Role</h2>
    </template>

    <div class="py-12">
      <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div
          class="bg-white p-8 shadow-lg rounded-lg transition duration-300 hover:shadow-xl"
        >
          <form @submit.prevent="submit">
            <div class="mb-8">
              <label for="name" class="text-lg font-medium">Nama Role</label>
              <input
                v-model="form.name"
                id="name"
                type="text"
                placeholder="Masukkan nama role"
                class="mt-2 block w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 ease-in-out hover:border-indigo-400"
                required
              />
              <div v-if="form.errors.name" class="text-red-500 text-sm mt-2">
                {{ form.errors.name }}
              </div>
            </div>

            <div class="mb-8">
              <label for="parent" class="text-lg font-medium"
                >Parent Role (opsional)</label
              >
              <select
                v-model="form.parent"
                id="parent"
                class="mt-2 block w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 ease-in-out hover:border-indigo-400"
              >
                <option value="0">Tidak ada (Root Role)</option>
                <option v-for="role in hierarchicalRoles" :key="role.id" :value="role.id">
                  {{ "—".repeat(role.level) }} {{ role.name }}
                </option>
              </select>
              <div v-if="form.errors.parent" class="text-red-500 text-sm mt-2">
                {{ form.errors.parent }}
              </div>
            </div>

            <div class="mb-8">
              <label class="text-lg font-medium mb-3">Permissions</label>
              <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                  <label
                    v-for="permission in permissions"
                    :key="permission.id"
                    class="flex items-center space-x-3 p-2 rounded hover:bg-gray-100 transition duration-200"
                  >
                    <input
                      type="checkbox"
                      :value="permission.name"
                      v-model="form.permissions"
                      class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300"
                    />
                    <span class="text-sm text-gray-700">{{ permission.name }}</span>
                  </label>
                </div>
              </div>
              <div v-if="form.errors.permissions" class="text-red-500 text-sm mt-2">
                {{ form.errors.permissions }}
              </div>
            </div>

            <div
              class="flex justify-between items-center mt-10 pt-6 border-t border-gray-200"
            >
              <Link
                :href="route('roles.index')"
                class="px-4 py-2 rounded-lg text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition duration-200"
              >
                ← Kembali
              </Link>
              <div>
                <button
                  type="submit"
                  class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 transition duration-200 text-base font-medium text-white rounded-lg"
                >
                  Simpan Perubahan
                </button>
              </div>
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
  role: Object,
  permissions: Array,
  roles: Array,
});

const buildHierarchy = (roles, parent = 0, level = 0) => {
  return roles
    .filter((r) => r.parent === parent)
    .flatMap((r) => [{ ...r, level }, ...buildHierarchy(roles, r.id, level + 1)]);
};

const hierarchicalRoles = computed(() => buildHierarchy(props.roles));

const form = useForm({
  name: props.role.name,
  permissions: props.permissions.map((p) => p.name),
  parent: props.role.parent || 0,
});

const submit = () => {
  form.put(route("roles.update", props.role.id));
};
</script>
