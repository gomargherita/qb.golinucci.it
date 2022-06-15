<template>
  <div>
    <Head :title="form.cart_ref" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-primary hover:text-secondary-400" :href="route('carts')">{{
        __("Bordereaux")
      }}</Link>
    </h1>

    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <div class="bg-white rounded-md shadow overflow-hidden">
        <form>
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input
              v-model="form.date"
              :error="form.errors.date"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="Payment date"
              type="date"
              pattern="\d{4}-\d{2}-\d{2}"
            />
          </div>
          <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
            <a
              @click="download({ date: this.form.date })"
              type="button"
              class="btn-gol-primary ml-auto"
              target="_blank"
              >{{ __("Download") }}</a
            >
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layouts/Layout";
import TextInput from "@/Shared/TextInput";
import LoadingButton from "@/Shared/LoadingButton";
import axios from "axios";

export default {
  components: {
    LoadingButton,
    TextInput,
  },
  layout: Layout,
  data() {
    return {
      form: this.$inertia.form({
        date: null,
      }),
    };
  },
  methods: {
    download: async (params) => {
      let response = {};
      let url = route("bordereauxs.download", params);

      await axios
        .post(url)
        .then((res) => {
          if (res.data.error) {
            throw res.data;
          }
          response = res.data;
        })
        .catch((err) => {
          throw err;
        });

      return response;
      //   return fetch(`/admin/bordereauxs`, {
      //     method: "post",
      //     body: JSON.stringify({ date: this.form.date }),
      //     headers: {
      //     //   "Content-Type":
      //     //     "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
      //         "Content-Type": "text/csv",
      //         "Content-Disposition": "attachment; filename='test.csv'",
      //       "X-Requested-With": "XMLHttpRequest",
      //       "X-CSRF-TOKEN": document
      //         .querySelector('meta[name="csrf-token"]')
      //         .getAttribute("content"),
      //     },
      //   })
      //     .then((response) => response.blob())
      //     .then((blob) => {
      //       var file = window.URL.createObjectURL(blob);
      //       window.location.assign(file);
      //     });
    },
  },
};
</script>
