<template>
  <div id="dropdown" />
  <div class="">
    <div id="gol-header-bg" class="">
      <div class="container mx-auto">
        <div class="flex items-center justify-between px-8 md:px-0 py-6 md:flex-shrink-0">
          <Link href="/">
            <logo class="hidden md:block fill-white" width="240" height="56" />
            <logo class="md:hidden fill-white" width="120" height="28" />
          </Link>
          <main-menu class="hidden md:block flex-shrink-0 p-4" />
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
              <div class="mt-2 px-8 py-4 bg-primary rounded shadow-lg">
                <main-menu />
              </div>
            </template>
          </dropdown>
          <div
            v-if="current_user"
            class="hidden md:inline-block md:text-md flex items-center justify-between p-4 text-sm"
          >
            <dropdown class="bg-primary" placement="bottom-end">
              <template #default>
                <div class="group flex items-center cursor-pointer select-none">
                  <div
                    class="mr-1 text-white group-hover:text-primary focus:text-primary whitespace-nowrap"
                  >
                    <span>{{ current_user.first_name }}</span>
                    <span class="hidden md:inline"
                      >&nbsp;{{ current_user.last_name }}</span
                    >
                  </div>
                  <icon
                    class="w-5 h-5 fill-white group-hover:fill-secondary focus:fill-secondary"
                    name="cheveron-down"
                  />
                </div>
              </template>
              <template #dropdown>
                <div class="mt-2 py-2 text-sm bg-white rounded shadow-xl">
                  <Link
                    class="block px-6 py-2 hover:text-white hover:bg-primary"
                    :href="`/admin/current_users/${current_user.id}/edit`"
                    >{{ __("My Profile") }}</Link
                  >

                  <Link
                    class="block px-6 py-2 w-full text-left hover:text-white hover:bg-primary"
                    href="/logout"
                    method="delete"
                    as="button"
                    >{{ __("Logout") }}</Link
                  >
                </div>
              </template>
            </dropdown>
            <!--language-selector /-->
          </div>
          <div
            v-if="!current_user"
            class="hidden md:inline-block md:text-md flex items-center justify-between p-4 text-sm"
          >
            <Link class="block px-6 py-2 text-white hover:underline" :href="`/login`">{{
              __("Login")
            }}</Link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Icon from "@/Shared/Icon";
import Logo from "@/Shared/Logo";
import Dropdown from "@/Shared/Dropdown";
import MainMenu from "@/Shared/MainMenu";
import UserMenu from "@/Shared/UserMenu";
import LanguageSelector from "@/Shared/LanguageSelector";

export default {
  components: {
    Icon,
    Logo,
    Dropdown,
    MainMenu,
    UserMenu,
    LanguageSelector,
  },
  props: {
    current_user: Object,
  },
};
</script>
