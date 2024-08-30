<template>
  <div>
    <Head :title="form.cart_ref" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-primary hover:text-secondary-400" :href="route('carts', 1)">{{
        __("Bordereaux")
      }}</Link>
    </h1>

    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <div class="bg-white rounded-md shadow overflow-hidden">
        <h1 class="mb-8 text-3xl font-bold -mb-8 -mr-6 p-8">Medici</h1>
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
          <div class="-mb-8 -mr-6 p-8">
            <a
              @click="download({ date: this.form.date, prodotto: 'medici'})"
              type="button"
              class="btn-gol-primary ml-auto m-6"
              target="_blank"
              >Giornaliero</a
            >
            <a
              @click="download({ date: this.form.date, prodotto: 'medici-mensile'})"
              type="button"
              class="btn-gol-primary ml-auto m-6"
              target="_blank"
              >Mensile</a
            >
            <p style="margin-bottom:15px">Per scaricare il borderaux mensile, scegliere un giorno qualsiasi del mese richiesto.<br></p>

          </div>

        </form>
      </div>
    </div>

    <a href="/admin/importo_libero">Importo libero</a>

    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <div class="bg-white rounded-md shadow overflow-hidden">
        <h1 class="mb-8 text-3xl font-bold -mb-8 -mr-6 p-8">Bancari</h1>

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
          <div class="-mb-8 -mr-6 p-8">
            <a
              @click="download({ date: this.form.date, prodotto: 'bancari-ER-Daily'})"
              type="button"
              class="btn-gol-primary ml-auto m-6"
              target="_blank"
              >ER Daily</a
            >
            <a
              @click="download({ date: this.form.date, prodotto: 'bancari-ER-Static'})"
              type="button"
              class="btn-gol-primary ml-auto m-6"
              target="_blank"
              >ER</a
            >
            <a
              @click="download({ date: this.form.date, prodotto: 'bancari-SA'})"
              type="button"
              class="btn-gol-primary ml-auto m-6"
              target="_blank"
              >SA</a
            >
            <a
              @click="download({ date: this.form.date, prodotto: 'bancari-RI'})"
              type="button"
              class="btn-gol-primary ml-auto m-6"
              target="_blank"
              >RI</a
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

      await axios({
        method: "post",
        url: url,
        responseType: "blob", // important
        headers: {
          "Content-Type":
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
          "Content-Disposition": "attachment; filename=export.xlsx",
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((res) => {
          if (res.data.error) {
            throw res.data;
          }
          response = res.data;
          var file = window.URL.createObjectURL(response);
          window.location.assign(file);
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
