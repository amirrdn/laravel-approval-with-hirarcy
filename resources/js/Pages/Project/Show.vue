<template>
  <Head :title="`Project Detail - ` + project.name" />
  <AuthenticatedLayout>
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6 mb-6">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-800">Informasi Proyek</h3>
            <span :class="statusClass" class="px-3 py-1 text-sm font-medium rounded-full">
              {{ statusText }}
            </span>
          </div>

          <div :class="gridColsClass" class="grid gap-x-8 gap-y-6">
            <div class="space-y-6">
              <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Informasi Dasar</h4>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label class="text-sm font-medium text-gray-600 w-1/2"
                      >Nama Proyek</label
                    >
                    <span class="text-sm text-gray-800 w-1/2">{{ project.name }}</span>
                  </div>
                  <div class="flex items-center justify-between">
                    <label class="text-sm font-medium text-gray-600 w-1/2">Pembuat</label>
                    <span class="text-sm text-gray-800 w-1/2">
                      {{ project.users[0]?.name || "Tidak ada pengguna" }}
                      <!-- ({{ project.users[0]?.role?.name || "Tidak ada peran" }}) -->
                    </span>
                  </div>
                  <div class="flex items-center justify-between">
                    <label class="text-sm font-medium text-gray-600 w-1/2"
                      >Tanggal Mulai</label
                    >
                    <span class="text-sm text-gray-800 w-1/2">{{
                      formattedStartDate
                    }}</span>
                  </div>
                  <div class="flex items-center justify-between">
                    <label class="text-sm font-medium text-gray-600 w-1/2"
                      >Tanggal Selesai</label
                    >
                    <span class="text-sm text-gray-800 w-1/2">{{
                      formattedEndDate
                    }}</span>
                  </div>
                </div>
              </div>

              <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Deskripsi</h4>
                <p class="text-sm text-gray-800">{{ project.description }}</p>
              </div>

              <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Lampiran</h4>
                <a
                  v-if="project.attachment"
                  :href="`/storage/${project.attachment}`"
                  class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  target="_blank"
                >
                  <svg
                    class="h-4 w-4 mr-2"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    ></path>
                  </svg>
                  Download PDF
                </a>
                <span v-else class="text-sm text-gray-500">Tidak ada file</span>
              </div>
            </div>

            <div v-if="canAssignUser" class="bg-gray-50 rounded-lg p-4">
              <h4 class="text-sm font-semibold text-gray-700 mb-3">Tindakan</h4>
              <form @submit.prevent="updateProject">
                <input type="hidden" name="name" :value="project.name" />
                <input type="hidden" name="description" :value="project.description" />
                <input type="hidden" name="start_date" :value="project.start_date" />
                <input type="hidden" name="end_date" :value="project.end_date" />

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"
                      >Tugaskan Pengguna</label
                    >
                    <select
                      name="assigned_to"
                      v-model="formData.assigned_to"
                      class="select2 mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                      <template v-if="users.length > 0">
                        <option v-for="user in users" :key="user.id" :value="user.id">
                          {{ user.name }}
                        </option>
                      </template>
                      <template v-else>
                        <option v-for="v in project.users" :key="v.id" :value="v.id">
                          {{ v.name }}
                        </option>
                      </template>
                    </select>
                    <span v-if="errors.assigned_to" class="mt-1 text-sm text-red-600">{{
                      errors.assigned_to
                    }}</span>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"
                      >Status</label
                    >
                    <select
                      name="status"
                      v-model="formData.status"
                      class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                      <option v-if="project.status === 4" value="">Pilih Opsi</option>
                      <option v-if="users.length > 0" value="2">Terima</option>
                      <option v-if="!users.length" value="3">Selesai</option>
                      <option value="4">Tolak</option>
                    </select>
                    <span v-if="errors.status" class="mt-1 text-sm text-red-600">{{
                      errors.status
                    }}</span>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"
                      >Deskripsi</label
                    >
                    <textarea
                      name="description"
                      v-model="formData.description"
                      rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    ></textarea>
                  </div>

                  <div class="flex justify-end">
                    <button
                      type="submit"
                      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                      Perbarui Proyek
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
          <h3 class="text-lg font-semibold mb-4">Riwayat Perubahan</h3>
          <div v-if="activities.length === 0" class="text-center py-8">
            <svg
              class="mx-auto h-12 w-12 text-gray-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
              ></path>
            </svg>
            <p class="mt-2 text-gray-500">Belum ada riwayat perubahan yang tercatat.</p>
          </div>

          <div v-else class="space-y-4">
            <div
              v-for="activity in activities"
              :key="activity.id"
              class="border-l-4 border-indigo-500 pl-4 py-2"
            >
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                  <span
                    :class="[
                      'px-2 py-1 text-xs font-semibold rounded-full',
                      activity.event === 'created'
                        ? 'bg-green-100 text-green-800'
                        : activity.event === 'updated'
                        ? 'bg-blue-100 text-blue-800'
                        : activity.event === 'deleted'
                        ? 'bg-red-100 text-red-800'
                        : 'bg-gray-100 text-gray-800',
                    ]"
                  >
                    {{ capitalizeFirstLetter(activity.event) }}
                  </span>
                  <span class="text-sm text-gray-600">
                    oleh {{ activity.causer?.name || "System" }}
                  </span>
                </div>
                <span class="text-xs text-gray-500">
                  {{ formatDate(activity.created_at) }}
                </span>
              </div>

              <div
                v-if="activity.properties.attributes"
                class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4"
              >
                <div v-if="activity.properties.old" class="bg-gray-50 p-3 rounded">
                  <h4 class="text-xs font-semibold text-gray-500 mb-1">
                    Nilai Sebelumnya
                  </h4>
                  <div class="text-sm">
                    <div
                      v-for="(value, key) in activity.properties.old"
                      :key="key"
                      class="mb-1"
                    >
                      <span class="font-medium">{{ formatKey(key) }}:</span>
                      <span class="text-gray-600">
                        <span v-if="key === 'status'">{{ getStatusLabel(value) }}</span>
                        <span v-else-if="isDateField(key)">{{ formatDate(value) }}</span>
                        <span v-else>{{
                          Array.isArray(value) ? JSON.stringify(value) : value
                        }}</span>
                      </span>
                    </div>
                  </div>
                </div>

                <div v-if="activity.properties.attributes" class="bg-gray-50 p-3 rounded">
                  <h4 class="text-xs font-semibold text-gray-500 mb-1">Nilai Baru</h4>
                  <div class="text-sm">
                    <div
                      v-for="(value, key) in activity.properties.attributes"
                      :key="key"
                      class="mb-1"
                    >
                      <span class="font-medium">{{ formatKey(key) }}:</span>
                      <span class="text-gray-600">
                        <span v-if="key === 'status'">{{
                          getStatusLabel(value.status)
                        }}</span>
                        <span v-else-if="isDateField(key)">{{ formatDate(value) }}</span>
                        <span v-else>{{
                          Array.isArray(value) ? JSON.stringify(value) : value
                        }}</span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { format } from "date-fns";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
  project: Object,
  users: Array,
  activities: Array,
  userid: String,
});

const errors = ref({});

const formData = useForm({
  status: props.project.status,
  description: "",
  assigned_to: "",
  id: props.project.id,
});

const statusText = computed(() => {
  switch (props.project.status) {
    case 1:
      return "Approved";
    case 3:
      return "Completed";
    case 4:
      return "Rejected";
    default:
      return "Pending";
  }
});

const statusClass = computed(() => {
  switch (props.project.status) {
    case 1:
      return "bg-green-100 text-green-700";
    case 3:
      return "bg-blue-100 text-blue-700";
    case 4:
      return "bg-red-100 text-red-700";
    default:
      return "bg-yellow-100 text-yellow-700";
  }
});

const canAssignUser = computed(() => {
  return (
    (props.project.project_user.assigned_to === props.userid ||
      props.project.status === 4) &&
    props.project.status !== 3
  );
});

const formattedStartDate = computed(() => {
  return new Date(props.project.start_date).toLocaleDateString();
});

const formattedEndDate = computed(() => {
  return new Date(props.project.end_date).toLocaleDateString();
});

const capitalizeFirstLetter = (string) => {
  return string.charAt(0).toUpperCase() + string.slice(1);
};

const formatDate = (date) => {
  return format(new Date(date), "d MMMM yyyy, HH:mm");
};
const getStatusLabel = (value) => {
  switch (value) {
    case 1:
      return "Aktif";
    case 2:
      return "Diterima";
    case 3:
      return "Selesai";
    case 4:
      return "Ditolak";
    case 5:
      return "Revisi";
    default:
      return "Tidak Aktif";
  }
};
const isDateField = (key) => {
  return ["start_date", "end_date", "created_at", "updated_at"].includes(key);
};

const formatKey = (key) => {
  return key.replace("_", " ").replace(/\b\w/g, (char) => char.toUpperCase());
};

const updateProject = () => {
  formData.post(route("users.assign.form"));
};
</script>

<style scoped>
.select2 {
  border-radius: 0.375rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  padding: 0.5rem;
  font-size: 0.875rem;
}
</style>
