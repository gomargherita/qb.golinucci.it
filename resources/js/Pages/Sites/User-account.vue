<template>
  <Head>
    <title>{{ __("User account") }}</title>
    <meta
      head-key="description"
      name="description"
      content="Golinucci Q&B about us meta description"
    />
  </Head>
  <HOne :title="`User account`" />

  <section id="main-content" class="bg-white container mx-auto p-2">
    <h2>
      {{ __("User data") }}
    </h2>
    
    
    <table style="width: wrap; margin:auto" class="bg-white">
      <tbody>
        <tr>
          <td style="font-weight: bold">Nome:</td>
          <td style="padding-left:10px;">{{ user.first_name }}</td>
        </tr>
        <tr>
          <td style="font-weight: bold">Cognome:</td>
          <td style="padding-left:10px">{{ user.last_name }}</td>
        </tr>
        <tr>
          <td style="font-weight: bold">Email:</td>
          <td style="padding-left:10px">{{ user.email }}</td>
        </tr>
        <tr>
          <td style="font-weight: bold">Codice fiscale:</td>
          <td style="padding-left:10px">{{ user.client_details.fiscal_code }}</td>
        </tr>
      </tbody>
    </table>


    <h2>
      {{ __("My products") }}
    </h2>
        <div v-if="user.noProds == false">
          <div v-for="cart in user.carts">

            <div>
            <p class="text-center text-gray-800" style="font-weight: bold; font-size:17px">{{ cart.nome }}</p>

            <table style="width: wrap; margin:auto">
              <tbody>
                <tr>
                  <td style="font-weight: bold">Limite indennità:</td>
                  <td style="padding-left:10px;">{{ cart.indemnity_limit }}€</td>
                </tr>
                <tr>
                  <td style="font-weight: bold">Limite indennità patrimoniale:</td>
                  <td style="padding-left:10px;">{{ cart.indemnity_limit_patrimonial }}€</td>
                </tr>
                <tr>
                  <td style="font-weight: bold">Retroattività:</td>
                  <td style="padding-left:10px;">{{ cart.retroactivity2 }}</td>
                </tr>
                
                <tr v-if="user.certificates[cart.id][0] !== undefined">
                  <td style="font-weight: bold">Numero di certificato:</td>
                  <td style="padding-left:10px;">{{ user.certificates[cart.id][0].doprint_certificate_no }}</td>
                </tr>
                <tr v-if="user.certificates[cart.id][0] !== undefined">
                  <td style="font-weight: bold">Data di inizio validità:</td>
                  <td style="padding-left:10px;">{{ user.certificates[cart.id][0].start }}</td>
                </tr>
                <tr v-if="user.certificates[cart.id][0] !== undefined">
                  <td style="font-weight: bold">Data di fine validità:</td>
                  <td style="padding-left:10px;">{{ user.certificates[cart.id][0].end }}</td>
                </tr>
                <div v-else>
                  <tr>
                    <td style="font-weight: bold">Numero di certificato:</td>
                    <td style="padding-left:10px;">Non presente.</td>
                  </tr>
                  <tr>
                    <td style="font-weight: bold">Data di inizio validità:</td>
                    <td style="padding-left:10px;">Non presente.</td>
                  </tr>
                  <tr>
                    <td style="font-weight: bold">Data di fine validità:</td>
                    <td style="padding-left:10px;">Non presente.</td>
                  </tr>
                </div>

              </tbody>
            </table>
          </div>
          <br>


          <div class="mt-6 bg-white rounded shadow overflow-x-auto">
        <div class="bg-white rounded-md shadow overflow-hidden text-center">
          <div style="display: inline-block" v-if="!user.documents[cart.id]['declarations']" class="flex flex-wrap p-8">
            Non sono ancora presenti le dichiarazioni.
          </div>
          <div style="display: inline-block" v-if="user.documents[cart.id]['declarations']" class="flex flex-wrap p-8">
            <a :href="user.documents[cart.id]['declarations']" target="_blank" class="btn-gol-primary" style="color:white"
              >Dichiarazioni</a
            >
          </div>
          <div style="display: inline-block" v-if="!user.documents[cart.id]['payment_certificate']" class="flex flex-wrap p-8">
            Non è ancora presente proforma fattura.
          </div>
          <div style="display: inline-block" v-if="user.documents[cart.id]['payment_certificate']" class="flex flex-wrap p-8">
            <a
              :href="user.documents[cart.id]['payment_certificate']"
              target="_blank"
              class="btn-gol-primary" style="color:white"
              >Proforma fattura</a
            >
          </div>
          <div style="display: inline-block" v-if="!user.documents[cart.id]['certificate_final']" class="flex flex-wrap p-8">
            Non è ancora presente la polizza.
          </div>
          <div style="display: inline-block" v-if="user.documents[cart.id]['certificate_final']" class="flex flex-wrap p-8">
            <a
              :href="user.documents[cart.id]['certificate_final']"
              target="_blank"
              class="btn-gol-primary" style="color:white"
              >Polizza</a
            >
          </div>
        </div>
      </div>

          <hr style="border: 1px solid #000">
          <br>
          </div>
        </div>
        <div v-else>
          <p class="text-center">Non ci sono prodotti acquistati.</p>
        </div>

  </section>
</template>

<script>
import HOne from "@/Shared/Front/HOne";

export default {
  components: {
    HOne,
  },
  props: {
    user: Object,
  },
};
</script>