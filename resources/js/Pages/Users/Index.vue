<template>
  <Head title="Users" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Management</h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Tombol Tambah dan Export -->
        <div class="flex items-center space-x-2 mb-4" v-if="canManageUsers">
          <Link
            :href="route('users.create')"
            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 inline-flex items-center space-x-1 transition duration-150 ease-in-out"
          >
            <PlusIcon class="h-5 w-5" />
            <span>Tambah User</span>
          </Link>

          <a
            v-if="canExport"
            :href="route('export.users')"
            target="_blank"
            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 inline-flex items-center space-x-1 transition duration-150 ease-in-out"
          >
            <ArrowDownTrayIcon class="h-5 w-5" />
            <span>Export</span>
          </a>
        </div>
        <div v-else>
          <p>
            Tombol tidak ditampilkan karena Anda tidak memiliki izin untuk mengelola
            pengguna.
          </p>
        </div>

        <!-- Pesan Berhasil -->
        <div
          v-if="flash?.success"
          class="bg-green-100 text-green-800 p-4 mb-4 rounded-md"
        >
          {{ flash.success }}
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <!-- Form Import -->
          <div class="mb-3 p-3" v-if="canExport">
            <form
              @submit.prevent="importUsers"
              enctype="multipart/form-data"
              class="bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-200"
            >
              <div class="flex items-center space-x-4">
                <div class="flex-1">
                  <label class="block text-sm font-medium text-gray-700 mb-2"
                    >Pilih File Excel</label
                  >
                  <div class="flex items-center space-x-4">
                    <input
                      ref="fileInput"
                      type="file"
                      required
                      class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                    />
                    <button
                      type="submit"
                      class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 inline-flex items-center space-x-1 transition duration-150 ease-in-out"
                    >
                      <ArrowDownTrayIcon class="h-5 w-5" />
                      <span>Import</span>
                    </button>
                  </div>
                  <p class="mt-2 text-sm text-gray-500">
                    Format file yang didukung: .xlsx, .xls
                  </p>
                </div>
              </div>
            </form>
          </div>

          <!-- Table Data User -->
          <div class="p-4">
            <table id="user-table" class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Nama
                  </th>
                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Email
                  </th>
                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Role
                  </th>
                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Aksi
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Link, router, Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from "axios";
import $ from "jquery";
import "datatables.net";
import "datatables.net-dt/css/dataTables.dataTables.css";
import { PlusIcon, ArrowDownTrayIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
  canManageUsers: {
    type: Boolean,
    default: false,
  },
  canExport: {
    type: Boolean,
    default: false,
  },
  flash: {
    type: Object,
    default: () => ({}),
  },
  csrf_token: {
    type: String,
    required: true,
  },
});

const successMessage = ref("");

const fileInput = ref(null);

const importUsers = async () => {
  const formData = new FormData();
  formData.append("file", fileInput.value.files[0]);

  try {
    const response = await axios.post(route("import.users"), formData, {
      headers: { "Content-Type": "multipart/form-data" },
    });
    successMessage.value = "Import berhasil";
    $("#user-table").DataTable().ajax.reload();
  } catch (error) {
    console.error("Gagal import:", error);
  }
};

const deleteUser = (id) => {
  if (confirm("Yakin hapus user?")) {
    router.delete(route("users.destroy", id), {
      onSuccess: () => {
        $("#user-table").DataTable().ajax.reload();
      },
    });
  }
};

const goToEditPage = (id) => {
  router.visit(route("users.edit", id));
};

onMounted(() => {
  $("#user-table").DataTable({
    processing: true,
    serverSide: true,
    ajax: route("datatable.users"),
    columns: [
      { data: "name", name: "name" },
      { data: "email", name: "email" },
      { data: "roles", name: "roles", orderable: false, searchable: false },
      {
        data: "action",
        name: "action",
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          if (type === "display") {
            const editButton = `
              <button onclick="goToEditPage('${row.id}')"
                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 rounded-md transition duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
              </button>
            `;
            const deleteForm = `<div class="inline ml-2">
                                <button onclick="window.deleteUser('${row.id}')" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 rounded-md transition duration-150 ease-in-out">
                                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                  </svg>
                                  Hapus
                                </button>
                              </div>`;
            return editButton + deleteForm;
          }
          return data;
        },
      },
    ],
    language: {
      search: "Cari:",
      lengthMenu: "Tampilkan _MENU_ data per halaman",
      info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
      infoEmpty: "Tidak ada data yang ditampilkan",
      infoFiltered: "(difilter dari _MAX_ total data)",
      zeroRecords: "Tidak ada data yang cocok",
      paginate: {
        first: "Pertama",
        last: "Terakhir",
        next: "Selanjutnya",
        previous: "Sebelumnya",
      },
    },
    dom:
      '<"flex justify-between items-center mb-4"<"flex items-center"l><"flex items-center"f>>rt<"flex justify-between items-center mt-4"<"flex items-center"i><"flex items-center"p>>',
    pageLength: 10,
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "Semua"],
    ],
    order: [[0, "asc"]],
    responsive: true,
    autoWidth: false,
    drawCallback: function () {
      $(".dataTables_wrapper .dataTables_length select").addClass(
        "border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
      );
      $(".dataTables_wrapper .dataTables_filter input").addClass(
        "border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
      );
      $(".dataTables_wrapper .dataTables_paginate .paginate_button").addClass(
        "px-3 py-1 rounded-md hover:bg-indigo-50"
      );
      $(".dataTables_wrapper .dataTables_paginate .paginate_button.current").addClass(
        "bg-indigo-600 text-white"
      );
    },
  });

  window.deleteUser = deleteUser;
  window.goToEditPage = goToEditPage;
});
</script>

<style scoped>
/* Tambahan styling jika dibutuhkan */
</style>
