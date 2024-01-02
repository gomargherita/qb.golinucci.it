<template>
  <div>
    <Head :title="form.cart_ref" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-primary hover:text-secondary-400" :href="route('carts', cart.product.id)">{{
        __("Carts")
      }}</Link>
      <span class="text-gray font-medium">/</span>
      <span>{{ __("Cart") }} {{ form.cart_ref }} </span>
    </h1>
    <trashed-message v-if="cart.deleted_at" class="mb-6" @restore="restore"
      >{{ __("This cart detail has been deleted") }}.</trashed-message
    >

    <h2 class="mt-12 text-2xl font-bold">{{ __("Related User") }}</h2>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Last Name</th>
          <th class="pb-4 pt-6 px-6">First Name</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Email</th>
        </tr>
        <tr v-if="user" :key="user.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4 focus:text-primary"
              :href="route('users.edit', user.id)"
            >
              {{ user.last_name }}
              <icon
                v-if="user.deleted_at"
                name="trash"
                class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
              />
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="route('users.edit', user.id)"
              tabindex="-1"
            >
              {{ user.first_name }}
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="route('users.edit', user.id)"
              tabindex="-1"
            >
              {{ user.email }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link
              class="flex items-center px-4"
              :href="route('users.edit', user.id)"
              tabindex="-1"
            >
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="user.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No users found.</td>
        </tr>
      </table>
    </div><br>

    <div class="bg-white rounded-md shadow overflow-hidden">
      <div class="flex flex-wrap -mb-8 -mr-6 p-8">
        <div>
          <label class="font-bold">{{ __("Product") }}</label
          ><br />
          <div v-text="cart.product.name" class="pb-8 pr-6 w-full"></div>
        </div>
        <div>
          <label class="font-bold">{{ __("Product Offer") }}</label
          ><br />
          <div
            v-text="
              this.cart.product_offer
                ? this.cart.product_offer?.name
                : 'Offerta Prodotto non ancora scelta'
            "
            class="pb-8 pr-6 w-full"
          ></div>
        </div>
        <div>
          <div class="pb-8 pr-6 w-full">
            <a :href="cartUrl" target="_blank" class="btn-gol-primary">Cart Url</a>
          </div>
        </div>
      </div>
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <select-input
            v-model="form.product_step_id"
            :error="form.errors.product_step_id"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Step"
          >
            <option
              v-for="product_step in cart.product_steps"
              :value="product_step.step_number"
              :selected="product_step.id == form.product_step_id"
            >
              {{ product_step.step_number }}. {{ product_step.step_name }}
            </option></select-input
          >
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button
            v-if="!cart.deleted_at"
            class="text-red-600 hover:underline"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            {{ __("Delete") }} {{ __("Cart") }}
          </button>
          <loading-button
            :loading="form.processing"
            class="btn-gol-primary ml-auto"
            type="submit"
            >{{ __("Update") }} {{ __("Cart") }}</loading-button
          >
        </div>
      </form>
    </div>
    <h2 class="mt-12 text-2xl font-bold">{{ __("Related Payment") }}</h2>

    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <div class="bg-white rounded-md shadow overflow-hidden">
        <div v-if="!cart.payment" class="flex flex-wrap p-8">No payment yet.</div>
        <form v-if="cart.payment" @submit.prevent="update_payment">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <select-input
              v-model="form_payment.payment_gateway_id"
              :error="form_payment.errors.payment_gateway_id"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="Payment Gateway"
            >
              <option
                v-for="payment_gateway in payment_gateways"
                :value="payment_gateway.id"
                :selected="payment_gateway.id == form_payment.payment_gateway_id"
              >
                {{ payment_gateway.name }}
              </option></select-input
            >

            <text-input
              v-model="form_payment.transaction_ref"
              :error="form_payment.errors.transaction_ref"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="Transaction ref"
            />
            <checkbox-input
              v-model="form_payment.paid"
              :error="form_payment.errors.paid"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="Paid"
              :checked="form_payment.paid"
            />
            <text-input
              v-model="form_payment.paid_at"
              :error="form_payment.errors.paid_at"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="Paid at"
              type="date"
              pattern="\d{4}-\d{2}-\d{2}"
            />

            <div class="pb-8 pr-6 w-full lg:w-full">
              <p class="mb-2">
                <b>Importo pagamento</b>: {{ this.cart.payment?.total_payable }}<br />
                <b>Numero certificato di pagamento assegnato:
                  
                  {{ this.cart.payment?.payment_no }}
                </b>
              <br><br>
              <template v-if="cart.product.product_type_id == 1">
                <text-input
                  v-model="form_payment.payment_no"
                  :error="form_payment.errors.payment_no"
                  class="pb-8 pr-6 w-full lg:w-1/2"
                  label="Modifica numero pagamento"
                  disabled/>
              </template>
              <template v-if="cart.product.product_type_id == 2">
                <text-input
                  v-model="form_payment.payment_no"
                  :error="form_payment.errors.payment_no"
                  class="pb-8 pr-6 w-full lg:w-1/2"
                  label="Modifica numero pagamento"
                  />
              </template>
              </p>
              <p class="mb-2">
                <b class="text-red-500">Attenzione!</b>: prima di segnare un pagamento
                come pagato controllare che il
                <b>metodo di pagamento sia corretto</b> perché non sarà più possibile
                correggere a mano il numero di certificato di pagamento.<br />
                Ricordare che con il metodo di pagamento con bonifico bancario, una volta
                segnato il pagamento come pagato, la procedura invia al cliente una mail
                che conferma il pagamento e fa procedere il cliente dallo step 5.<br />
                In caso di problemi e di prenotazione di un numero di certificato
                sbagliato è possibile ricorrere alla cancellazione del record di pagamento
                (da usare con parsimonia, poiché serve poi anche cambiare lo step del
                carrello per ricreare il record di pagamento SE E SOLO SE il cliente non
                ha ancora proceduto alla generazione del certificato Lloyds).
              </p>
            </div>
          </div>
          <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
            <button
              class="text-red-600 hover:underline"
              tabindex="-1"
              type="button"
              @click="destroy_payment"
            >
              {{ __("Delete") }} {{ __("Payment") }}
            </button>
            <loading-button
              :loading="form_payment.processing"
              class="btn-gol-primary ml-auto"
              type="submit"
              >{{ __("Update") }} {{ __("Payment") }}</loading-button
            >
          </div>
        </form>
        <br><br>
        <form  @submit.prevent="update_decorrenza" >

          <p class="mt-6 bg-white rounded shadow overflow-x-auto px-8 py-4" v-if="cart.product.id == 1">Attenzione: modificare queste date solo se il carrello è allo step 4.</p>
          <div class="mt-6 bg-white rounded shadow overflow-x-auto px-8 py-4">
            <div class="flex flex-wrap ">
              <text-input
                v-model="form_update_decorrenza.startDate"
                :error="form_update_decorrenza.errors.startDate"
                class="pb-8 pr-6 w-full lg:w-1/2"
                label="Data di inizio decorrenza"
                type="date"
                pattern="\d{4}-\d{2}-\d{2}"
                :customDate=start_date
              />
            </div>
            
            <div class="flex flex-wrap ">
              <text-input
                v-model="form_update_decorrenza.endDate"
                :error="form_update_decorrenza.errors.endDate"
                class="pb-8 pr-6 w-full lg:w-1/2"
                label="Data di fine decorrenza"
                type="date"
                pattern="\d{4}-\d{2}-\d{2}"
                :customDate=end_date
              />
            </div>
            <div v-if="cart.product.id == 1" class="flex flex-wrap ">
              <text-input
                v-model="form_update_decorrenza.retroactivity"
                :error="form_update_decorrenza.errors.retroactivity"
                class="pb-8 pr-6 w-full lg:w-1/2"
                label="Retroattività"
                type="date"
                pattern="\d{4}-\d{2}-\d{2}"
                :customDate=retroactivity
              />
            </div>
            <div>
              <div class="flex items-center ">
                
                <a @click="update_decorrenza" target="_blank" class="btn-gol-primary">Aggiorna date di decorrenza</a>
              </div>
          </div>
          </div>

          <div v-if="cart.product.product_type_id == 2">
              <div class="flex items-center px-8 py-4 border-t border-gray-100 ">
                <a @click="send_email" target="_blank" class="btn-gol-primary  ml-auto">Invia email con il certificato</a>
              </div>
          </div>
        <div v-if="cart.product.product_type_id == 2">
          <div class="flex items-center px-8 py-4 border-t border-gray-100 ">
                <a @click="certificate_update" target="_blank" class="btn-gol-primary  ml-auto">Aggiorna certificato</a>
          </div>
          <div v-if="cart.product.product_type_id == 2">
          <div class="flex items-center px-8 py-4 border-t border-gray-100 ">
                <a @click="send_first_email(2)" target="_blank" class="btn-gol-primary  ml-auto">ER - Manda email con credenziali rinnovo</a>
          </div>
          <div class="flex items-center px-8 py-4 border-t border-gray-100 ">
                <a @click="send_first_email(4)" target="_blank" class="btn-gol-primary  ml-auto">RI - Manda email con credenziali rinnovo</a>
          </div>
          </div>
        </div>

        </form>
      </div>
    </div>

    <h2 class="mt-12 text-2xl font-bold">{{ __("Certificate") }}</h2>

    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <div class="bg-white rounded-md shadow overflow-hidden">
        <div v-if="!cart.certificate" class="flex flex-wrap p-8">No certificate yet.</div>
        <div v-if="cart.certificate" class="flex flex-wrap p-8">Certificate data</div>
      </div>
    </div>

    <h2 class="mt-12 text-2xl font-bold">{{ __("Documents") }}</h2>

    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <div class="bg-white rounded-md shadow overflow-hidden">
        <div v-if="!cart.documents.declarations" class="flex flex-wrap p-8">
          No declaration yet.
        </div>
        <div v-if="cart.documents.declarations" class="flex flex-wrap p-8">
          <a :href="cart.documents.declarations" target="_blank" class="btn-gol-primary"
            >Declarations</a
          >
        </div>
        <div v-if="!cart.documents.payment_certificate" class="flex flex-wrap p-8">
          No payment certificate yet.
        </div>
        <div v-if="cart.documents.payment_certificate" class="flex flex-wrap p-8">
          <a
            :href="cart.documents.payment_certificate"
            target="_blank"
            class="btn-gol-primary"
            >Payment Certificate</a
          >
        </div>
        <div v-if="!cart.documents.certificate_final" class="flex flex-wrap p-8">
          No certificate yet.
        </div>
        <div v-if="cart.documents.certificate_final" class="flex flex-wrap p-8">
          <a
            :href="cart.documents.certificate_final"
            target="_blank"
            class="btn-gol-primary"
            >Certificate</a
          >
        </div>
      </div>
    </div>

    
  </div>
</template>

<script>
import Layout from "@/Shared/Layouts/Layout";
import TextInput from "@/Shared/TextInput";
import CheckboxInput from "@/Shared/CheckboxInput";
import SelectInput from "@/Shared/SelectInput";
import TextareaInput from "@/Shared/TextareaInput";
import LoadingButton from "@/Shared/LoadingButton";
import TrashedMessage from "@/Shared/TrashedMessage";

export default {
  components: {
    LoadingButton,
    SelectInput,
    CheckboxInput,
    TextareaInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    cart: Object,
    start_date: String,
    end_date: String,
    user: Object,
    payment_gateways: Array,
    can: Object,
  },
  data() {
    return {
      form: this.$inertia.form({
        cart_ref: this.cart.cart_ref,
        product_step_id: this.cart.product_step_id,
      }),
      form_payment: this.$inertia.form({
        cart_id: this.cart.id,
        payment_gateway_id: this.cart.payment?.payment_gateway_id,
        paid: this.cart.payment?.paid,
        transaction_ref: this.cart.payment?.transaction_ref,
        paid_at: this.cart.payment?.paid_at,
        total_payable: this.cart.payment?.total_payable,
        payment_no: this.cart.payment?.payment_no
      }),
      form_update_decorrenza: this.$inertia.form({
        cart_id: this.cart.id,
        startDate: this.startDate,
        endDate: this.endDate,
        retroactivity: this.retroactivity
      })
    };
  },
  computed: {
    cartUrl() {
      return "/products/" + this.cart.product.id + "/buy/" + this.cart.cart_ref;
    },
  },
  methods: {
    update() {
      this.form.put(`/admin/carts/${this.cart.id}`);
    },
    update_payment() {
      this.form_payment.put(`/admin/payments/${this.cart.payment?.id}`);
    },
    destroy_payment() {
      if (confirm("Are you sure you want to delete this payment?")) {
        this.$inertia.delete(`/admin/payments/${this.cart.payment?.id}`);
      }
    },
    destroy() {
      if (confirm("Are you sure you want to delete this cart?")) {
        this.$inertia.delete(`/admin/carts/${this.cart.id}`);
      }
    },
    restore() {
      if (confirm("Are you sure you want to restore this cart?")) {
        this.$inertia.put(`/admin/carts/${this.cart.id}/restore`);
      }
    },
    send_email() {
      this.$inertia.get(`/admin/carts/${this.cart.id}/sendemail`)
    },
    send_first_email(product_id) {
      this.$inertia.get(`/admin/carts/${this.cart.id}/${product_id}/sendfirstemail`)
    },
    update_decorrenza(){
      this.form_update_decorrenza.put(`/admin/carts/${this.cart.id}/updatedecorrenza`);
    },
    certificate_update(){
      this.form_update_decorrenza.put(`/admin/carts/${this.cart.id}/certificateupdate`);
    },
    updateDates(){
      this.form_update_decorrenza.startDate = this.start_date;
      this.form_update_decorrenza.endDate = this.end_date;
    }
  },
  mounted() {
    //this.intervalId = setInterval(this.updateDates, 500);
    //console.log("non aggiorno le date");
    this.updateDates()
  },
};


</script>
