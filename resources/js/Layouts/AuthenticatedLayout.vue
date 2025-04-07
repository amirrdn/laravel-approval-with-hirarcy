<script setup>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link } from "@inertiajs/vue3";

const showingNavigationDropdown = ref(false);
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <nav class="bg-white shadow-sm">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
          <div class="flex items-center">
            <!-- Logo -->
            <Link :href="route('dashboard')" class="flex items-center">
              <ApplicationLogo class="h-8 w-auto text-gray-800" />
            </Link>

            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:ml-10 sm:flex">
              <NavLink
                :href="route('dashboard')"
                :active="route().current('dashboard')"
                class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900 transition-colors duration-200 hover:text-indigo-600"
              >
                Dashboard
              </NavLink>
              <NavLink
                v-if="$page.props.auth?.can?.manageUsers"
                :href="route('users.index')"
                :active="route().current('users')"
                class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900 transition-colors duration-200 hover:text-indigo-600"
              >
                Users
              </NavLink>
              <NavLink
                v-if="$page.props.auth?.can?.manageRoles"
                :href="route('roles.index')"
                :active="route().current('roles')"
                class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900 transition-colors duration-200 hover:text-indigo-600"
              >
                Role
              </NavLink>
              <NavLink
                v-if="$page.props.auth?.can?.manageProjects"
                :href="route('projects.index')"
                :active="route().current('projects')"
                class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900 transition-colors duration-200 hover:text-indigo-600"
              >
                Project
              </NavLink>
            </div>
          </div>

          <!-- Settings Dropdown -->
          <div class="hidden sm:ml-6 sm:flex sm:items-center">
            <Dropdown align="right" width="48">
              <template #trigger>
                <button
                  type="button"
                  class="flex items-center rounded-full bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-200 transition-all duration-200 hover:bg-gray-50 hover:ring-gray-300 focus:outline-none"
                >
                  <span class="mr-2">{{ $page.props.auth.user.name }}</span>
                  <svg
                    class="h-4 w-4 text-gray-500"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </button>
              </template>

              <template #content>
                <DropdownLink
                  :href="route('profile.edit')"
                  class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100"
                >
                  Profile
                </DropdownLink>
                <DropdownLink
                  :href="route('logout')"
                  method="post"
                  as="button"
                  class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100"
                >
                  Log Out
                </DropdownLink>
              </template>
            </Dropdown>
          </div>

          <!-- Mobile menu button -->
          <div class="flex items-center sm:hidden">
            <button
              @click="showingNavigationDropdown = !showingNavigationDropdown"
              class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none"
            >
              <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path
                  :class="{
                    hidden: showingNavigationDropdown,
                    'inline-flex': !showingNavigationDropdown,
                  }"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                  :class="{
                    hidden: !showingNavigationDropdown,
                    'inline-flex': showingNavigationDropdown,
                  }"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile menu -->
      <div
        :class="{
          block: showingNavigationDropdown,
          hidden: !showingNavigationDropdown,
        }"
        class="sm:hidden"
      >
        <div class="space-y-1 px-2 pb-3 pt-2">
          <ResponsiveNavLink
            :href="route('dashboard')"
            :active="route().current('dashboard')"
            class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900"
          >
            Dashboard
          </ResponsiveNavLink>
        </div>

        <div class="border-t border-gray-200 pb-3 pt-4">
          <div class="px-4">
            <div class="text-base font-medium text-gray-800">
              {{ $page.props.auth.user.name }}
            </div>
            <div class="text-sm font-medium text-gray-500">
              {{ $page.props.auth.user.email }}
            </div>
          </div>

          <div class="mt-3 space-y-1">
            <ResponsiveNavLink
              :href="route('profile.edit')"
              class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900"
            >
              Profile
            </ResponsiveNavLink>
            <ResponsiveNavLink
              :href="route('logout')"
              method="post"
              as="button"
              class="block w-full rounded-md px-3 py-2 text-left text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900"
            >
              Log Out
            </ResponsiveNavLink>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page Heading -->
    <header class="bg-white shadow-sm" v-if="$slots.header">
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <slot name="header" />
      </div>
    </header>

    <!-- Page Content -->
    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <slot />
    </main>
  </div>
</template>
