<template>
  <Head title="Edit User" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-white leading-tight">Edit User</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
          <div class="border-b pb-4 mb-6">
            <h3 class="text-lg font-medium text-gray-900">Informasi User</h3>
            <p class="mt-1 text-sm text-gray-600">
              Silakan edit informasi user yang diperlukan.
            </p>
          </div>

          <form @submit.prevent="form.post(route('users.store'))">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
              <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                  Name <span class="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  v-model="form.name"
                  class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:bg-gray-100"
                  required
                />
                <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">
                  {{ form.errors.name }}
                </div>
              </div>

              <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                  Email <span class="text-red-500">*</span>
                </label>
                <input
                  type="email"
                  v-model="form.email"
                  class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:bg-gray-100"
                  required
                />
                <div v-if="form.errors.email" class="text-red-600 text-sm mt-1">
                  {{ form.errors.email }}
                </div>
              </div>

              <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Position</label>
                <input
                  type="text"
                  v-model="form.position"
                  class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:bg-gray-100"
                />
                <div v-if="form.errors.position" class="text-red-600 text-sm mt-1">
                  {{ form.errors.position }}
                </div>
              </div>

              <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Hire Date</label>
                <input
                  id="hire_date"
                  type="datetime-local"
                  v-model="form.hire_date"
                  class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50"
                />

                <div v-if="form.errors.hire_date" class="text-red-600 text-sm mt-1">
                  {{ form.errors.hire_date }}
                </div>
              </div>

              <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select
                  v-model="form.is_active"
                  class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:bg-gray-100"
                >
                  <option :value="1">Active</option>
                  <option :value="0">Inactive</option>
                </select>
                <div v-if="form.errors.is_active" class="text-red-600 text-sm mt-1">
                  {{ form.errors.is_active }}
                </div>
              </div>

              <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                  Password <span class="text-red-500">*</span>
                </label>
                <input
                  type="password"
                  v-model="form.password"
                  class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:bg-gray-100"
                  required
                />
                <div v-if="form.errors.password" class="text-red-600 text-sm mt-1">
                  {{ form.errors.password }}
                </div>
              </div>

              <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                  Konfirmasi Password <span class="text-red-500">*</span>
                </label>
                <input
                  type="password"
                  v-model="form.password_confirmation"
                  class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:bg-gray-100"
                  required
                />
                <div
                  v-if="form.errors.password_confirmation"
                  class="text-red-600 text-sm mt-1"
                >
                  {{ form.errors.password_confirmation }}
                </div>
              </div>

              <div class="col-span-2 space-y-2">
                <label class="block text-sm font-medium text-gray-700">Role</label>

                <select
                  id="role"
                  v-model="form.role"
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
                <div v-if="form.errors.role" class="text-red-600 text-sm mt-1">
                  {{ form.errors.role }}
                </div>
              </div>
            </div>

            <div class="mt-8 flex justify-between items-center pt-6 border-t">
              <Link
                :href="route('users.index')"
                class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
              >
                <svg
                  class="w-4 h-4 mr-2"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                  />
                </svg>
                Kembali
              </Link>
              <button
                type="submit"
                class="inline-flex items-center px-6 py-2.5 bg-blue-600 text-white font-medium text-sm rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm"
              >
                <svg
                  class="w-4 h-4 mr-2"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
                  />
                </svg>
                Update User
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { onMounted, computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { Indonesian } from "flatpickr/dist/l10n/id.js";

const props = defineProps({
  roles: Array,
});

const form = useForm({
  name: "",
  email: "",
  position: "",
  hire_date: "",
  is_active: "1",
  password: "",
  password_confirmation: "",
  role: "0",
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
onMounted(() => {
  flatpickr("#hire_date", {
    dateFormat: "d/m/Y",
    enableTime: false,
    locale: Indonesian,
    defaultDate: form.hire_date,
    onChange: (selectedDates, dateStr) => {
      form.hire_date = dateStr;
    },
  });
});
</script>
