<template>
  <Head :title="`Edit Project ` + project.name" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-white leading-tight">Edit User</h2>
    </template>
    <div>
      <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl rounded-lg">
          <div class="bg-indigo-600 px-6 py-4">
            <h3 class="text-lg font-medium text-white flex items-center">
              <svg
                class="w-5 h-5 mr-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                />
              </svg>
              Create New Project
            </h3>
          </div>

          <div class="p-6">
            <form @submit.prevent="submitForm">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Project Name -->
                <div>
                  <label for="name" class="block text-sm font-medium text-gray-700"
                    >Project Name</label
                  >
                  <div class="mt-1 relative rounded-md shadow-sm">
                    <input
                      v-model="formData.name"
                      type="text"
                      id="name"
                      class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      required
                    />
                  </div>
                </div>

                <!-- Status -->
                <div>
                  <label for="status" class="block text-sm font-medium text-gray-700"
                    >Status</label
                  >
                  <div class="mt-1 relative rounded-md shadow-sm">
                    <select
                      v-model="formData.status"
                      id="status"
                      class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                    >
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                  </div>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                  <label for="description" class="block text-sm font-medium text-gray-700"
                    >Description</label
                  >
                  <div class="mt-1">
                    <textarea
                      v-model="formData.description"
                      id="description"
                      rows="3"
                      class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    ></textarea>
                  </div>
                </div>

                <!-- Start Date -->
                <div>
                  <label for="start_date" class="block text-sm font-medium text-gray-700"
                    >Start Date</label
                  >
                  <div class="mt-1 relative rounded-md shadow-sm">
                    <input
                      v-model="formData.start_date"
                      type="datetime-local"
                      id="start_date"
                      class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                    />
                  </div>
                </div>

                <!-- End Date -->
                <div>
                  <label for="end_date" class="block text-sm font-medium text-gray-700"
                    >End Date</label
                  >
                  <div class="mt-1 relative rounded-md shadow-sm">
                    <input
                      v-model="formData.end_date"
                      type="datetime-local"
                      id="end_date"
                      class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                    />
                  </div>
                </div>

                <!-- Select Users -->
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700"
                    >Select Users</label
                  >
                  <div class="mt-1 relative rounded-md shadow-sm">
                    <select
                      v-model="formData.assigned_to"
                      class="select2 focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                    >
                      <option v-for="user in users" :key="user.id" :value="user.id">
                        {{ user.name }}
                      </option>
                    </select>
                  </div>
                </div>

                <!-- Attachment -->
                <div class="md:col-span-2">
                  <label for="attachment" class="block text-sm font-medium text-gray-700"
                    >Attachment (PDF)</label
                  >
                  <div
                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md"
                  >
                    <div class="space-y-1 text-center">
                      <svg
                        class="mx-auto h-12 w-12 text-gray-400"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <path
                          d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                          stroke-width="2"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        />
                      </svg>
                      <input
                        @change="handleFileUpload"
                        type="file"
                        id="attachment"
                        name="attachment"
                        accept="application/pdf"
                        class="sr-only"
                      />
                      <p class="pl-1">or drag and drop</p>
                      <p class="text-xs text-gray-500">PDF up to 10MB</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-8 flex justify-end">
                <button
                  type="submit"
                  class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700"
                >
                  <svg
                    class="w-5 h-5 mr-2"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                  Save Project
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm, Head, Link } from "@inertiajs/vue3";

const props = defineProps({
  project: Object,
  users: {
    type: Array,
    required: true,
  },
  selectedUsers: Array,
});
const formData = useForm({
  name: props.project.name,
  status: props.project.status,
  description: props.project.description,
  start_date: props.project.start_date,
  end_date: props.project.end_date,
  assigned_to: String(props.project.assigned_to),
  attachment: props.project.attachment,
});

const submitForm = async () => {
  formData.post(route("projects.store"));
};

const handleFileUpload = (event) => {
  formData.value.attachment = event.target.files[0];
};
</script>

<style scoped></style>
