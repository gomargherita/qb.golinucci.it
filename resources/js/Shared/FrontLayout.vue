<template>
  <div>
    <div id="pre-header" class="hidden md:block bg-gray-600">
      <div class="container mx-auto">
        <div class="text-white text-right">
          <Link
            class="group flex inline-flex items-center p-3"
            href="mailto:asdsd@sds.lds"
          >
            <icon name="mail" class="mr-2 w-6 h-6" />
            <div>asdsd@sds.lds</div>
          </Link>
          <Link class="group flex inline-flex items-center p-3" href="tel:+39333333333">
            <icon name="phone" class="mr-2 w-6 h-6" />
            <div>+39 333333333</div>
          </Link>
        </div>
      </div>
    </div>

    <div id="dropdown" />
    <div class="">
      <div id="gol-header-bg" class="">
        <div class="container mx-auto">
          <div
            class="flex items-center justify-between px-8 md:px-0 py-6 md:flex-shrink-0"
          >
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
              v-if="$page.props.auth.user"
              class="hidden md:inline-block md:text-md flex items-center justify-between p-4 text-sm"
            >
              <dropdown class="bg-primary" placement="bottom-end">
                <template #default>
                  <div class="group flex items-center cursor-pointer select-none">
                    <div
                      class="mr-1 text-white group-hover:text-primary focus:text-primary whitespace-nowrap"
                    >
                      <span>{{ $page.props.auth.user?.first_name }}</span>
                      <span class="hidden md:inline"
                        >&nbsp;{{ $page.props.auth.user?.last_name }}</span
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
                      :href="`/admin/users/${$page.props.auth.user?.id}/edit`"
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
              v-if="!$page.props.auth.user"
              class="hidden md:inline-block md:text-md flex items-center justify-between p-4 text-sm"
            >
              <Link class="block px-6 py-2 hover:text-secondary" :href="`/login`">{{
                __("Login")
              }}</Link>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-white" scroll-region>
      <flash-messages />
      <slot />
      <footer class="w-full mx-auto bg-white my-8 border-t-8 border-gray-400">
        <div class="container mx-auto flex px-3 py-8">
          <div class="w-full mx-auto flex flex-wrap">
            <div class="flex w-full lg:w-1/2">
              <div class="px-3 md:px-0">
                <p class="py-4">
                  <logo class="hidden md:block fill-white" width="240" height="56" />
                  <logo class="md:hidden fill-white" width="120" height="28" />
                </p>
                <p class="bg-gray-400 text-white p-1 mx-0 md:mx-2 text-xs">
                  Q&amp;B Golinucci è un prodotto Golinucci srl
                </p>
              </div>
            </div>
            <div class="flex w-full lg:w-1/2 lg:justify-end lg:text-right">
              <div class="px-3 md:px-0">
                <p class="py-4">
                  <logo class="hidden md:block fill-white" width="240" height="56" />
                  <logo class="md:hidden fill-white" width="120" height="28" />
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="flex py-8 bg-gray-400">
          <div class="container mx-auto">
            <div class="px-6">
              <p>asda</p>
            </div>
          </div>
        </div>
        <div class="flex py-8 bg-white">
          <div class="container mx-auto">
            <div class="px-6">
              <p>asda</p>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";
import Logo from "@/Shared/Logo";
import Dropdown from "@/Shared/Dropdown";
import MainMenu from "@/Shared/MainMenu";
import UserMenu from "@/Shared/UserMenu";
import FlashMessages from "@/Shared/FlashMessages";
import LanguageSelector from "@/Shared/LanguageSelector";

export default {
  components: {
    Dropdown,
    FlashMessages,
    Icon,
    Link,
    Logo,
    MainMenu,
    UserMenu,
    LanguageSelector,
  },
};
</script>
