<template>
  <div>
    <Head title="Email templates" />
    <h1 class="mb-8 text-3xl font-bold">Email templates</h1>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6">Name</th>
            <th class="pb-4 pt-6 px-6">Key</th>
            <th class="pb-4 pt-6 px-6">Active</th>
            <th class="pb-4 pt-6 px-6">Updated</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="t in templates"
            :key="t.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4 focus:text-primary"
                :href="route('emailTemplates.edit', t.id)"
              >
                {{ t.name }}
              </Link>
            </td>
            <td class="border-t px-6 py-4">{{ t.key }}</td>
            <td class="border-t px-6 py-4">{{ t.is_active ? "Yes" : "No" }}</td>
            <td class="border-t px-6 py-4">{{ t.updated_at }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Layout from "@/Shared/Layouts/Layout";

export default {
  components: { Head, Link },
  layout: Layout,
  props: {
    templates: Array,
  },
};
</script>
