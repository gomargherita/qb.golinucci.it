<template>
  <Head>
    <title>{{ __("About us") }}</title>
    <meta
      head-key="description"
      name="description"
      content="Golinucci Q&B about us meta description"
    />
  </Head>
  <HOne :title="`About us`" />

  <section id="main-content" class="container mx-auto p-2">
    <div id="logos" class="my-8">
      <div class="flex w-full md:max-w-2xl mx-auto flex-wrap md:flex-nowrap px-3 pb-8">
        <div class="flex w-full mx-auto md:w-1/3">
          <div class="mx-auto md:mx-0 md:px-0">
            <p class="py-4">
              <icon
                name="golinucci-logo"
                class="mx-auto object-scale-down h-16 w-full sm:w-56"
              />
            </p>
          </div>
        </div>
        <div class="hidden md:flex w-full mx-auto md:w-1/3">
          <div class="mx-auto md:mx-0 md:px-0">
            <p class="py-12">
              <icon
                name="arrow-right"
                class="mx-auto object-scale-down h-8 w-full sm:w-56"
              />
            </p>
          </div>
        </div>
        <div class="flex w-full md:w-1/3 md:justify-end md:text-right">
          <div class="mx-auto md:mx-0 md:px-0">
            <p class="py-4">
              <icon
                name="golinucci-logo-legacy"
                class="mx-auto object-scale-down h-24 w-full sm:w-64"
              />
            </p>
          </div>
        </div>
      </div>
      <div>
        <p class="text-primary text-center mx-0 md:mx-2 text-xl font-bold">
          Q&amp;B Golinucci è un prodotto di Golinucci srl
        </p>
      </div>
    </div>
    <div class="flex flex-col my-8">
      <div class="bg-gray-100 md:min-h-96 min-h-max shadow">
        <div class="md:flex">
          <div class="w-full md:w-3/5">
            <img src="/img/accordo.jpg" class="w-full min-h-96 h-full object-cover" />
          </div>
          <div class="w-full md:w-2/5">
            <div
              class="flex items-center border-t-8 md:border-t-0 border-primary p-6 md:px-12 md:py-8 min-h-96 h-full md:border-l-8"
            >
              <div>
                <h2 class="text-2xl font-bold mt-4 color-primary">
                  Il Broker d'Assicurazione
                </h2>
                <div class="mt-4">
                  <p>
                    Il broker d'assicurazione è una attività, professionalmente
                    esercitata, che consiste nel mettere in relazione i soggetti
                    interessati alla copertura di determinati rischi con imprese di
                    assicurazione o di riassicurazione.
                  </p>

                  <p>
                    Il Broker di Assicurazione deve assistere tali soggetti nella
                    formazione dei contratti e, in caso, collaborare alla gestione dei
                    medesimi, ma non può essere vincolato da impegni di sorta alle imprese
                    predette.
                  </p>

                  <p>
                    Il servizio offerto dal Broker: consulente ed intermediario
                    indipendente
                  </p>

                  <p>Di fatto il Broker di Assicurazione è</p>

                  <p>
                    <b>CONSULENTE</b><br />
                    Nel momento in cui aiuta il proprio cliente a progettare la copertura
                    assicurativa ritenuta più idonea a tutelare le esigenze dello stesso
                  </p>

                  <p>
                    <b>MEDIATORE INDIPENDENTE</b><br />
                    Nel momento in cui ricerca sul mercato l'assicuratore ritenuto più
                    idoneo a soddisfare il bisogno di sicurezza, messo a punto di concerto
                    con il proprio cliente.<br />
                    Durante la gestione ed esecuzione del contratto, il Broker diventa
                    garante fra le parti affinché vengano rispettati i diritti ed i doveri
                    dei contraenti e, in particolare, aiuta il proprio cliente a percepire
                    il giusto indennizzo in caso di sinistro.
                  </p>

                  <p>
                    L'indipendenza del broker è garantita da particolari norme di legge.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import HOne from "@/Shared/Front/HOne";

export default {
  components: {
    HOne,
  },
};
</script>
