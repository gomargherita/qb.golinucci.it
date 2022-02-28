<template>
  <svg
    v-if="name === 'cheveron-down'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
  >
    <path
      d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
    />
  </svg>
  <svg
    v-else-if="name === 'cheveron-right'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
  >
    <polygon
      points="12.95 10.707 13.657 10 8 4.343 6.586 5.757 10.828 10 6.586 14.243 8 15.657 12.95 10.707"
    />
  </svg>
  <svg
    v-else-if="name === 'dashboard'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
  >
    <path
      d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm-5.6-4.29a9.95 9.95 0 0 1 11.2 0 8 8 0 1 0-11.2 0zm6.12-7.64l3.02-3.02 1.41 1.41-3.02 3.02a2 2 0 1 1-1.41-1.41z"
    />
  </svg>
  <svg
    v-else-if="name === 'office'"
    xmlns="http://www.w3.org/2000/svg"
    width="100"
    height="100"
    viewBox="0 0 100 100"
  >
    <path
      fill-rule="evenodd"
      d="M7 0h86v100H57.108V88.418H42.892V100H7V0zm9 64h11v15H16V64zm57 0h11v15H73V64zm-19 0h11v15H54V64zm-19 0h11v15H35V64zM16 37h11v15H16V37zm57 0h11v15H73V37zm-19 0h11v15H54V37zm-19 0h11v15H35V37zM16 11h11v15H16V11zm57 0h11v15H73V11zm-19 0h11v15H54V11zm-19 0h11v15H35V11z"
    />
  </svg>
  <svg
    v-else-if="name === 'printer'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
  >
    <path
      d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    />
  </svg>
  <svg
    v-else-if="name === 'trash'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
  >
    <path
      d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z"
    />
  </svg>
  <svg
    v-else-if="name === 'users'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
  >
    <path
      d="M7 8a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0 1c2.15 0 4.2.4 6.1 1.09L12 16h-1.25L10 20H4l-.75-4H2L.9 10.09A17.93 17.93 0 0 1 7 9zm8.31.17c1.32.18 2.59.48 3.8.92L18 16h-1.25L16 20h-3.96l.37-2h1.25l1.65-8.83zM13 0a4 4 0 1 1-1.33 7.76 5.96 5.96 0 0 0 0-7.52C12.1.1 12.53 0 13 0z"
    />
  </svg>
  <svg
    v-else-if="name === 'en'"
    enable-background="new 0 0 512 512"
    version="1.1"
    viewBox="0 0 512 512"
    xml:space="preserve"
    xmlns="http://www.w3.org/2000/svg"
  >
    <circle cx="256" cy="256" r="256" fill="#F0F0F0" />
    <g fill="#0052B4">
      <path
        d="m52.92 100.14c-20.109 26.163-35.272 56.318-44.101 89.077h133.18l-89.077-89.077z"
      />
      <path
        d="m503.18 189.22c-8.829-32.758-23.993-62.913-44.101-89.076l-89.075 89.076h133.18z"
      />
      <path
        d="m8.819 322.78c8.83 32.758 23.993 62.913 44.101 89.075l89.074-89.075h-133.18z"
      />
      <path
        d="m411.86 52.921c-26.163-20.109-56.317-35.272-89.076-44.102v133.18l89.076-89.075z"
      />
      <path
        d="m100.14 459.08c26.163 20.109 56.318 35.272 89.076 44.102v-133.18l-89.076 89.074z"
      />
      <path
        d="M189.217,8.819c-32.758,8.83-62.913,23.993-89.075,44.101l89.075,89.075V8.819z"
      />
      <path
        d="m322.78 503.18c32.758-8.83 62.913-23.993 89.075-44.101l-89.075-89.075v133.18z"
      />
      <path
        d="m370 322.78l89.075 89.076c20.108-26.162 35.272-56.318 44.101-89.076h-133.18z"
      />
    </g>
    <g fill="#D80027">
      <path
        d="m509.83 222.61h-220.44v-220.44c-10.931-1.423-22.075-2.167-33.392-2.167-11.319 0-22.461 0.744-33.391 2.167v220.44h-220.44c-1.423 10.931-2.167 22.075-2.167 33.392 0 11.319 0.744 22.461 2.167 33.391h220.44v220.44c10.931 1.423 22.073 2.167 33.392 2.167 11.317 0 22.461-0.743 33.391-2.167v-220.44h220.44c1.423-10.931 2.167-22.073 2.167-33.392 0-11.317-0.744-22.461-2.167-33.391z"
      />
      <path
        d="m322.78 322.78l114.24 114.24c5.254-5.252 10.266-10.743 15.048-16.435l-97.802-97.802h-31.482v1e-3z"
      />
      <path
        d="m189.22 322.78h-2e-3l-114.24 114.24c5.252 5.254 10.743 10.266 16.435 15.048l97.802-97.804v-31.479z"
      />
      <path
        d="m189.22 189.22v-2e-3l-114.24-114.24c-5.254 5.252-10.266 10.743-15.048 16.435l97.803 97.803h31.481z"
      />
      <path
        d="m322.78 189.22l114.24-114.24c-5.252-5.254-10.743-10.266-16.435-15.047l-97.802 97.803v31.482z"
      />
    </g>
  </svg>
  <svg
    v-else-if="name === 'it'"
    enable-background="new 0 0 512 512"
    version="1.1"
    viewBox="0 0 512 512"
    xml:space="preserve"
    xmlns="http://www.w3.org/2000/svg"
  >
    <mask id="a"><circle cx="256" cy="256" r="256" fill="#fff" /></mask>
    <g mask="url(#a)">
      <path fill="#eee" d="M167 0h178l25.9 252.3L345 512H167l-29.8-253.4z" />
      <path fill="#6da544" d="M0 0h167v512H0z" />
      <path fill="#d80027" d="M345 0h167v512H345z" />
    </g>
    <div xmlns="" id="syno-nsc-ext-comp-1008" class="x-tip-invalid">
      <div xmlns="http://www.w3.org/2000/svg" class="x-tip-invalid-tl">
        <div class="x-tip-invalid-tr">
          <div class="x-tip-invalid-tc">
            <div
              xmlns=""
              class="x-tip-invalid-header x-unselectable"
              id="syno-nsc-ext-gen4"
            >
              <span
                xmlns="http://www.w3.org/2000/svg"
                class="x-tip-invalid-header-text"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="x-tip-invalid-bwrap" id="syno-nsc-ext-gen5">
        <div xmlns="http://www.w3.org/2000/svg" class="x-tip-invalid-ml">
          <div class="x-tip-invalid-mr">
            <div class="x-tip-invalid-mc" id="syno-nsc-ext-gen8">
              <div xmlns="" class="x-tip-invalid-body" id="syno-nsc-ext-gen6" />
            </div>
          </div>
        </div>
        <div
          xmlns="http://www.w3.org/2000/svg"
          class="x-tip-invalid-bl"
          id="syno-nsc-ext-gen7"
        >
          <div class="x-tip-invalid-br"><div class="x-tip-invalid-bc" /></div>
        </div>
      </div>
    </div>
  </svg>
</template>

<script>
export default {
  props: {
    name: String,
  },
};
</script>
