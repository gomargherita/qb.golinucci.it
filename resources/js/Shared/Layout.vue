<template>
  <div>
    <div id="dropdown" />
    <div class="md:flex md:flex-col">
      <div class="md:flex md:flex-col md:h-screen">
        <div class="md:flex md:flex-shrink-0">
          <div
            class="flex items-center justify-between px-6 py-4 bg-secondary-400 md:flex-shrink-0 md:justify-center md:w-56"
          >
            <Link class="mt-1" :href="route('admin')">
              <icon name="golinucci-logo" class="fill-white" width="120" height="28" />
            </Link>
            <dropdown class="md:hidden" placement="bottom-end">
              <template #default>
                <svg
                  class="w-6 h-6 fill-white"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20"
                >
                  <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                </svg>
              </template>
              <template #dropdown>
                <div class="mt-2 px-8 py-4 bg-gray-100 rounded shadow-lg">
                  <admin-menu />
                </div>
              </template>
            </dropdown>
          </div>
          <div
            class="md:text-md flex items-center justify-between p-4 w-full text-sm bg-white border-b md:px-12 md:py-0"
          >
            <div class="mr-4 mt-1">{{ auth.user.account.name }}</div>
            <dropdown class="mt-1" placement="bottom-end">
              <template #default>
                <div class="group flex items-center cursor-pointer select-none">
                  <div
                    class="mr-1 text-gray-700 group-hover:text-primary focus:text-primary whitespace-nowrap"
                  >
                    <span>{{ auth.user.first_name }}</span>
                    <span class="hidden md:inline">&nbsp;{{ auth.user.last_name }}</span>
                  </div>
                  <icon
                    class="w-5 h-5 fill-gray-700 group-hover:fill-primary focus:fill-primary"
                    name="cheveron-down"
                  />
                </div>
              </template>
              <template #dropdown>
                <div class="mt-2 py-2 text-sm bg-white rounded shadow-xl">
                  <Link
                    class="block px-6 py-2 hover:text-white hover:bg-primary"
                    :href="route('users.edit', auth.user.id)"
                    >{{ __("My Profile") }}</Link
                  >

                  <Link
                    class="block px-6 py-2 w-full text-left hover:text-white hover:bg-primary"
                    :href="route('logout')"
                    method="delete"
                    as="button"
                    >{{ __("Logout") }}</Link
                  >
                </div>
              </template>
            </dropdown>
            <!--language-selector /-->
          </div>
        </div>
        <div class="md:flex md:flex-grow md:overflow-hidden">
          <admin-menu
            class="hidden flex-shrink-0 p-12 w-56 bg-white overflow-y-auto md:block"
          />
          <div class="px-4 py-8 md:flex-1 md:p-12 md:overflow-y-auto" scroll-region>
            <flash-messages />
            <slot />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Dropdown from "@/Shared/Dropdown";
import AdminMenu from "@/Shared/AdminMenu";
import FlashMessages from "@/Shared/FlashMessages";
import LanguageSelector from "@/Shared/LanguageSelector";

export default {
  components: {
    Dropdown,
    FlashMessages,
    AdminMenu,
    LanguageSelector,
  },
  props: {
    auth: Object,
  },
};
</script>
