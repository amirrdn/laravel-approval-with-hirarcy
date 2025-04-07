<template>
  <Head title="Project" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-white leading-tight">Edit User</h2>
    </template>
    <div class="p-4">
      <div>
        <div class="mb-4">
          <Link
            :href="route('projects.create')"
            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 inline-flex items-center space-x-1 transition duration-150 ease-in-out"
          >
            <PlusIcon class="h-5 w-5" />
            <span>Tambah Project</span>
          </Link>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg py-4 px-4">
          <table id="projects-table" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Assigned To</th>
                <th>Creator</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { defineProps, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import $ from "jquery";
import "datatables.net";
import "datatables.net-dt/css/dataTables.dataTables.css";
import { Link, router, Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
  projects: {
    type: Array,
    required: true,
  },
  creator: Number,
});
onMounted(() => {
  initDataTable();
});
const form = useForm();
const confirmDelete = (projectId) => {
  if (confirm("Are you sure you want to delete this project?")) {
    form.delete(route("projects.destroy", projectId), {
      preserveScroll: true,
      onSuccess: () => {
        alert("Project berhasil dihapus!");
      },
      onError: () => {
        alert("Gagal menghapus project.");
      },
    });
  }
};
const goToEditPage = (id) => {
  router.visit(route("projects.edit", id));
};
const goToshow = (id) => {
  router.visit(route("projects.show", id));
};
const initDataTable = () => {
  $("#projects-table").DataTable({
    processing: true,
    serverSide: true,
    ajax: "/projects/datatable",
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
    columns: [
      {
        data: "DT_RowIndex",
        name: "DT_RowIndex",
        orderable: false,
        searchable: false,
      },
      { data: "name", name: "name" },
      { data: "status_badge", name: "status" },
      { data: "start_date", name: "start_date" },
      { data: "end_date", name: "end_date" },
      { data: "assigned_to", name: "assigned_to" },
      { data: "creator", name: "creator" },
      {
        data: "action",
        name: "action",
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          if (type === "display") {
            const editButton = `
  <button onclick="goToshow('${row.id}')"
    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 rounded-md transition duration-150 ease-in-out">
    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M12 3C7 3 3 7 3 12s4 9 9 9 9-4 9-9-4-9-9-9zm0 16c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6zm-1-9h2v4h-2z"/>
    </svg>
    View
  </button>
`;

            const showButton = `
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
                                <button onclick="confirmDelete('${row.id}')" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 rounded-md transition duration-150 ease-in-out">
                                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                  </svg>
                                  Hapus
                                </button>
                              </div>`;
            if (props.creator == row.project_user.user_id) {
              return editButton + deleteForm + showButton;
            } else {
              return editButton;
            }
          }
          return data;
        },
      },
    ],
  });
  window.goToEditPage = goToEditPage;
  window.goToshow = goToshow;
  window.confirmDelete = confirmDelete;
};
</script>

<style scoped>
/* Gaya CSS untuk komponen ini */
</style>
