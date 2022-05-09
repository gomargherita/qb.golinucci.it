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
    v-else-if="name === 'products'"
    xmlns="http://www.w3.org/2000/svg"
    class="h-6 w-6"
    viewBox="0 0 20 20"
    fill="currentColor"
  >
    <path
      fill-rule="evenodd"
      d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
      clip-rule="evenodd"
    />
  </svg>
  <svg
    v-else-if="name === 'dashboard'"
    xmlns="http://www.w3.org/2000/svg"
    class="h-6 w-6"
    viewBox="0 0 20 20"
    fill="currentColor"
  >
    <path
      fill-rule="evenodd"
      d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm9 4a1 1 0 10-2 0v6a1 1 0 102 0V7zm-3 2a1 1 0 10-2 0v4a1 1 0 102 0V9zm-3 3a1 1 0 10-2 0v1a1 1 0 102 0v-1z"
      clip-rule="evenodd"
    />
  </svg>
  <svg
    v-else-if="name === 'office'"
    xmlns="http://www.w3.org/2000/svg"
    class="h-6 w-6"
    viewBox="0 0 20 20"
    fill="currentColor"
  >
    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
    <path
      fill-rule="evenodd"
      d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
      clip-rule="evenodd"
    />
  </svg>
  <svg
    v-else-if="name === 'printer'"
    xmlns="http://www.w3.org/2000/svg"
    class="h-6 w-6"
    viewBox="0 0 20 20"
    fill="currentColor"
  >
    <path
      fill-rule="evenodd"
      d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
      clip-rule="evenodd"
    />
  </svg>
  <svg
    v-else-if="name === 'mail'"
    xmlns="http://www.w3.org/2000/svg"
    class="h-5 w-5"
    viewBox="0 0 20 20"
    fill="currentColor"
  >
    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
  </svg>
  <svg
    v-else-if="name === 'phone'"
    xmlns="http://www.w3.org/2000/svg"
    class="h-5 w-5"
    viewBox="0 0 20 20"
    fill="currentColor"
  >
    <path
      d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"
    />
  </svg>
  <svg
    v-else-if="name === 'external-link'"
    xmlns="http://www.w3.org/2000/svg"
    class="h-6 w-6"
    viewBox="0 0 20 20"
    fill="currentColor"
  >
    <path
      d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"
    />
    <path
      d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"
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
    class="h-6 w-6"
    viewBox="0 0 20 20"
    fill="currentColor"
  >
    <path
      d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"
    />
  </svg>
  <svg
    v-else-if="name === 'roles'"
    xmlns="http://www.w3.org/2000/svg"
    class="h-6 w-6"
    viewBox="0 0 20 20"
    fill="currentColor"
  >
    <path
      d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"
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
  <svg
    v-else-if="name === 'facebook-icon'"
    id="facebook-circular-logo"
    xmlns="http://www.w3.org/2000/svg"
    width="43.046"
    height="42.867"
    viewBox="0 0 43.046 42.867"
  >
    <path
      id="Tracciato_60"
      data-name="Tracciato 60"
      d="M21.523.349a21.511,21.511,0,0,0-3.59,42.723V26.362H12.741V20.349h5.192V15.916c0-5.145,3.142-7.948,7.732-7.948A42.848,42.848,0,0,1,30.3,8.2v5.377l-3.183,0c-2.5,0-2.977,1.186-2.977,2.926v3.838H30.1l-.777,6.013H24.141V43.216A21.513,21.513,0,0,0,21.523.349Z"
      transform="translate(0 -0.349)"
    />
  </svg>
  <svg
    v-else-if="name === 'twitter-icon'"
    xmlns="http://www.w3.org/2000/svg"
    width="44.331"
    height="44.331"
    viewBox="0 0 44.331 44.331"
  >
    <path
      id="twitter"
      d="M22.166,0A22.166,22.166,0,1,0,44.331,22.166,22.168,22.168,0,0,0,22.166,0ZM32.286,17.282q.015.327.015.658A14.377,14.377,0,0,1,17.825,32.416h0a14.4,14.4,0,0,1-7.8-2.285,10.348,10.348,0,0,0,1.214.07,10.208,10.208,0,0,0,6.319-2.178,5.093,5.093,0,0,1-4.753-3.534,5.071,5.071,0,0,0,2.3-.087,5.089,5.089,0,0,1-4.082-4.987c0-.023,0-.044,0-.065a5.053,5.053,0,0,0,2.3.636,5.092,5.092,0,0,1-1.575-6.792A14.444,14.444,0,0,0,22.239,18.51a5.09,5.09,0,0,1,8.67-4.64,10.2,10.2,0,0,0,3.231-1.235A5.107,5.107,0,0,1,31.9,15.449a10.147,10.147,0,0,0,2.922-.8,10.339,10.339,0,0,1-2.538,2.635Zm0,0"
    />
  </svg>
  <svg
    v-else-if="name === 'linkedin-icon'"
    xmlns="http://www.w3.org/2000/svg"
    width="44.605"
    height="44.605"
    viewBox="0 0 44.605 44.605"
  >
    <path
      id="linkedin"
      d="M22.3,0a22.3,22.3,0,1,0,22.3,22.3,22.74,22.74,0,0,0-1.085-6.89A22.227,22.227,0,0,0,22.3,0ZM15.822,33.715H10.39V17.374h5.432ZM13.106,15.142h-.035A2.831,2.831,0,1,1,13.142,9.5a2.832,2.832,0,1,1-.036,5.646Zm22.3,18.573H29.976V24.973c0-2.2-.786-3.7-2.752-3.7a2.973,2.973,0,0,0-2.787,1.986,3.72,3.72,0,0,0-.179,1.326v9.126H18.828s.071-14.808,0-16.341h5.431v2.314a5.392,5.392,0,0,1,4.9-2.7c3.574,0,6.253,2.336,6.253,7.355Zm0,0"
    />
  </svg>
  <svg
    v-else-if="name === 'youtube-icon'"
    xmlns="http://www.w3.org/2000/svg"
    width="44.461"
    height="44.461"
    viewBox="0 0 44.461 44.461"
  >
    <path
      id="Tracciato_61"
      data-name="Tracciato 61"
      d="M224.113,216.369l7.231-4.165-7.231-4.165Zm0,0"
      transform="translate(-204.651 -189.973)"
    />
    <path
      id="Tracciato_62"
      data-name="Tracciato 62"
      d="M22.231,0A22.231,22.231,0,1,0,44.461,22.231,22.233,22.233,0,0,0,22.231,0ZM36.121,22.253a36.691,36.691,0,0,1-.572,6.683A3.481,3.481,0,0,1,33.1,31.384c-2.174.572-10.87.572-10.87.572s-8.673,0-10.87-.595a3.481,3.481,0,0,1-2.449-2.449,36.53,36.53,0,0,1-.572-6.682,36.667,36.667,0,0,1,.572-6.682,3.552,3.552,0,0,1,2.449-2.472c2.174-.572,10.87-.572,10.87-.572s8.7,0,10.87.595a3.481,3.481,0,0,1,2.449,2.449,34.819,34.819,0,0,1,.572,6.7Zm0,0"
    />
  </svg>
  <svg
    v-else-if="name === 'golinucci-logo'"
    xmlns="http://www.w3.org/2000/svg"
    width="311.58"
    height="78.274"
    viewBox="0 0 311.58 78.274"
  >
    <g
      id="Logo_OK"
      data-name="QB Golinucci Logo"
      transform="translate(-191.162 -346.412)"
    >
      <g
        id="Raggruppa_41"
        data-name="Raggruppa 41"
        transform="translate(223.166 346.482)"
      >
        <g id="Raggruppa_38" data-name="Raggruppa 38" transform="translate(0 17.9)">
          <g id="Raggruppa_36" data-name="Raggruppa 36" transform="translate(63.852)">
            <path
              id="Tracciato_83"
              data-name="Tracciato 83"
              d="M665.736,505.383c0-9.2,7.1-15.735,16.814-15.735s16.757,6.532,16.757,15.735-7.044,15.735-16.757,15.735S665.736,514.585,665.736,505.383Zm24.6,0c0-5.283-3.351-8.464-7.782-8.464s-7.839,3.181-7.839,8.464,3.408,8.464,7.839,8.464S690.332,510.665,690.332,505.383Z"
              transform="translate(-665.736 -478.687)"
              fill="#822622"
            />
            <path
              id="Tracciato_84"
              data-name="Tracciato 84"
              d="M854.192,467.245V435.38h8.861v31.354c0,2.613,1.363,4.033,3.806,4.033a5.8,5.8,0,0,0,2.5-.568l.4,6.873a14.433,14.433,0,0,1-4.6.738C858.282,477.81,854.192,474.061,854.192,467.245Z"
              transform="translate(-816.127 -435.38)"
              fill="#822622"
            />
            <path
              id="Tracciato_85"
              data-name="Tracciato 85"
              d="M953.9,489.38h8.861v31.069H953.9Z"
              transform="translate(-895.699 -478.473)"
              fill="#822622"
            />
            <path
              id="Tracciato_86"
              data-name="Tracciato 86"
              d="M1051.6,502.953v17.5h-8.861V504.316c0-4.942-2.329-7.214-5.112-7.214-4.541,0-6.191,2.613-6.191,8.236v15.11H1022.6V489.38h7.857v4.087a11.979,11.979,0,0,1,9.386-4.033C1046.537,489.434,1051.6,493.638,1051.6,502.953Z"
              transform="translate(-950.518 -478.473)"
              fill="#822622"
            />
            <path
              id="Tracciato_87"
              data-name="Tracciato 87"
              d="M1356.2,505.383c0-9.2,7.1-15.735,17.041-15.735,6.419,0,11.474,2.783,13.69,7.782l-6.873,3.692a7.62,7.62,0,0,0-6.873-4.2c-4.488,0-8.009,3.124-8.009,8.464s3.522,8.464,8.009,8.464a7.518,7.518,0,0,0,6.873-4.2l6.873,3.749c-2.215,4.885-7.271,7.726-13.69,7.726C1363.3,521.117,1356.2,514.585,1356.2,505.383Z"
              transform="translate(-1216.737 -478.687)"
              fill="#822622"
            />
            <path
              id="Tracciato_88"
              data-name="Tracciato 88"
              d="M1518.439,505.383c0-9.2,7.1-15.735,17.041-15.735,6.419,0,11.474,2.783,13.69,7.782l-6.873,3.692a7.62,7.62,0,0,0-6.873-4.2c-4.488,0-8.01,3.124-8.01,8.464s3.522,8.464,8.01,8.464a7.518,7.518,0,0,0,6.873-4.2l6.873,3.749c-2.215,4.885-7.271,7.726-13.69,7.726C1525.539,521.117,1518.439,514.585,1518.439,505.383Z"
              transform="translate(-1346.207 -478.687)"
              fill="#822622"
            />
            <path
              id="Tracciato_89"
              data-name="Tracciato 89"
              d="M1689.893,489.38h8.861v31.069h-8.861Z"
              transform="translate(-1483.03 -478.473)"
              fill="#822622"
            />
            <path
              id="Tracciato_90"
              data-name="Tracciato 90"
              d="M1189.76,506.876v-17.5h8.861v16.132c0,4.942,2.33,7.214,5.112,7.214,4.541,0,6.191-2.613,6.191-8.236V489.38h8.837v31.069H1210.9v-4.087a11.979,11.979,0,0,1-9.386,4.033C1194.822,520.395,1189.76,516.191,1189.76,506.876Z"
              transform="translate(-1083.916 -478.473)"
              fill="#822622"
            />
          </g>
          <g id="Raggruppa_37" data-name="Raggruppa 37" transform="translate(0 50.095)">
            <path
              id="Tracciato_91"
              data-name="Tracciato 91"
              d="M350.52,692.784a3.734,3.734,0,0,1-.893-2.443l-.016-.466.016-.452a3.645,3.645,0,0,1,.886-2.414,3.057,3.057,0,0,1,2.4-.932,3.1,3.1,0,0,1,2.373.932v-3.135a.308.308,0,0,1,.108-.247.39.39,0,0,1,.262-.092h1.309a.384.384,0,0,1,.27.092.319.319,0,0,1,.1.247v9.347a.325.325,0,0,1-.1.24.367.367,0,0,1-.27.1h-1.217a.372.372,0,0,1-.262-.1.312.312,0,0,1-.108-.24V692.7a3.1,3.1,0,0,1-2.465,1A3.058,3.058,0,0,1,350.52,692.784Zm4.283-1.115a2.168,2.168,0,0,0,.478-1.285q.015-.169.015-.55t-.015-.536a1.863,1.863,0,0,0-.485-1.207,1.687,1.687,0,0,0-1.317-.5q-1.664,0-1.772,1.878l-.015.423.015.424q.108,1.878,1.772,1.878A1.62,1.62,0,0,0,354.8,691.669Z"
              transform="translate(-349.611 -683.508)"
              fill="#822622"
            />
            <path
              id="Tracciato_92"
              data-name="Tracciato 92"
              d="M396.788,703.453a2.571,2.571,0,0,1-1.032-.784,1.749,1.749,0,0,1-.377-1.087,1.816,1.816,0,0,1,.847-1.553,5.366,5.366,0,0,1,2.327-.8l2.111-.282v-.3a1.2,1.2,0,0,0-.339-.932,1.592,1.592,0,0,0-1.094-.311,1.684,1.684,0,0,0-.824.177,2.688,2.688,0,0,0-.5.346q-.185.184-.247.24a.229.229,0,0,1-.231.17h-1.186a.358.358,0,0,1-.239-.085.281.281,0,0,1-.1-.226,1.463,1.463,0,0,1,.377-.8,2.833,2.833,0,0,1,1.117-.776,4.676,4.676,0,0,1,1.849-.325,3.9,3.9,0,0,1,2.65.749,2.514,2.514,0,0,1,.832,1.962v4.434a.325.325,0,0,1-.1.24.367.367,0,0,1-.27.1h-1.248a.373.373,0,0,1-.262-.1.313.313,0,0,1-.108-.24v-.551a2.473,2.473,0,0,1-.963.741,3.676,3.676,0,0,1-1.549.289A3.524,3.524,0,0,1,396.788,703.453Zm3.351-1.56a1.941,1.941,0,0,0,.539-1.482v-.3l-1.541.226a3.563,3.563,0,0,0-1.348.41.813.813,0,0,0-.454.692.788.788,0,0,0,.416.706,1.882,1.882,0,0,0,1,.254A1.941,1.941,0,0,0,400.139,701.893Z"
              transform="translate(-386.134 -693.548)"
              fill="#822622"
            />
            <path
              id="Tracciato_93"
              data-name="Tracciato 93"
              d="M441.711,693.462a.313.313,0,0,1-.108-.24v-9.347a.308.308,0,0,1,.108-.247.39.39,0,0,1,.262-.092h1.279a.384.384,0,0,1,.27.092.319.319,0,0,1,.1.247v9.347a.324.324,0,0,1-.1.24.367.367,0,0,1-.27.1h-1.279A.373.373,0,0,1,441.711,693.462Z"
              transform="translate(-423.022 -683.508)"
              fill="#822622"
            />
            <path
              id="Tracciato_94"
              data-name="Tracciato 94"
              d="M479.4,694.02a.313.313,0,0,1-.108-.24v-7.314l-2.342,1.652a.345.345,0,0,1-.215.07.378.378,0,0,1-.308-.155l-.647-.763a.379.379,0,0,1-.077-.212.331.331,0,0,1,.169-.282l3.451-2.443a.656.656,0,0,1,.385-.1h1.356a.384.384,0,0,1,.27.092.32.32,0,0,1,.1.247v9.206a.324.324,0,0,1-.1.24.367.367,0,0,1-.27.1h-1.4A.373.373,0,0,1,479.4,694.02Z"
              transform="translate(-450.231 -684.066)"
              fill="#822622"
            />
            <path
              id="Tracciato_95"
              data-name="Tracciato 95"
              d="M514.1,693.469a.3.3,0,0,1-.092-.219.3.3,0,0,1,.062-.183l2.265-2.866a2.507,2.507,0,0,1-.632.056,4.294,4.294,0,0,1-1.88-.466,3.42,3.42,0,0,1-1.333-1.186,2.95,2.95,0,0,1-.485-1.638,3.161,3.161,0,0,1,.478-1.674,3.484,3.484,0,0,1,1.418-1.271,4.866,4.866,0,0,1,2.265-.487,5.222,5.222,0,0,1,2.28.459,3.374,3.374,0,0,1,1.44,1.228,3.1,3.1,0,0,1,.485,1.687,2.9,2.9,0,0,1-.2,1.073,4.5,4.5,0,0,1-.455.882q-.254.374-.716.967l-2.712,3.445a.828.828,0,0,1-.208.205.56.56,0,0,1-.316.078h-1.433A.314.314,0,0,1,514.1,693.469Zm3.505-5.2a1.621,1.621,0,0,0,.578-1.306,1.642,1.642,0,0,0-.578-1.313,2.3,2.3,0,0,0-2.85,0,1.781,1.781,0,0,0-.008,2.619,2.34,2.34,0,0,0,2.858,0Z"
              transform="translate(-479.206 -683.508)"
              fill="#822622"
            />
            <path
              id="Tracciato_96"
              data-name="Tracciato 96"
              d="M561.446,693.879a3.36,3.36,0,0,1-1.4-1.045,2.468,2.468,0,0,1-.516-1.4v-.028a.221.221,0,0,1,.093-.184.37.37,0,0,1,.231-.071h1.433a.442.442,0,0,1,.462.283,1.648,1.648,0,0,0,.716.918,2.271,2.271,0,0,0,1.179.3,2.179,2.179,0,0,0,1.441-.48,1.648,1.648,0,0,0,.578-1.341,1.541,1.541,0,0,0-.578-1.243,2.18,2.18,0,0,0-1.441-.48,2.122,2.122,0,0,0-.755.113,2.593,2.593,0,0,0-.57.31,2.592,2.592,0,0,1-.285.177.534.534,0,0,1-.239.049h-1.417a.341.341,0,0,1-.239-.092.287.287,0,0,1-.1-.219l.539-4.829a.408.408,0,0,1,.131-.276.427.427,0,0,1,.3-.106H566.8a.376.376,0,0,1,.262.1.314.314,0,0,1,.108.24v.946a.314.314,0,0,1-.108.24.375.375,0,0,1-.262.1h-4.453l-.247,2.3a3.724,3.724,0,0,1,2.018-.48,4.44,4.44,0,0,1,1.856.388,3.282,3.282,0,0,1,1.364,1.116,2.87,2.87,0,0,1,.508,1.687,3.121,3.121,0,0,1-.524,1.8,3.365,3.365,0,0,1-1.471,1.186,5.458,5.458,0,0,1-2.211.417A5.523,5.523,0,0,1,561.446,693.879Z"
              transform="translate(-517.128 -684.066)"
              fill="#822622"
            />
            <path
              id="Tracciato_97"
              data-name="Tracciato 97"
              d="M607.387,693.35a.313.313,0,0,1-.108-.24v-.72a.814.814,0,0,1,.385-.664l2.434-2.217a18.148,18.148,0,0,0,2.211-1.786,1.855,1.855,0,0,0,.624-1.264,1.387,1.387,0,0,0-.393-1.045,1.63,1.63,0,0,0-1.179-.382,1.714,1.714,0,0,0-1.217.417,1.827,1.827,0,0,0-.555,1.08.4.4,0,0,1-.169.247.511.511,0,0,1-.277.078h-1.371a.329.329,0,0,1-.231-.085.261.261,0,0,1-.092-.2,3.077,3.077,0,0,1,.493-1.56,3.318,3.318,0,0,1,1.34-1.172,4.515,4.515,0,0,1,2.065-.445,5.035,5.035,0,0,1,2.088.388,2.887,2.887,0,0,1,1.286,1.066,2.805,2.805,0,0,1,.431,1.539,2.76,2.76,0,0,1-.578,1.709,8.693,8.693,0,0,1-1.764,1.638l-2.188,2.033h4.391a.385.385,0,0,1,.27.092.319.319,0,0,1,.1.247v1a.325.325,0,0,1-.1.24.367.367,0,0,1-.27.1h-7.365A.373.373,0,0,1,607.387,693.35Z"
              transform="translate(-555.234 -683.396)"
              fill="#822622"
            />
          </g>
        </g>
        <g id="Raggruppa_39" data-name="Raggruppa 39" transform="translate(0)">
          <path
            id="Tracciato_98"
            data-name="Tracciato 98"
            d="M381.718,357.026a19.875,19.875,0,0,1,16.329,8.527l8.377-5.939a30.171,30.171,0,0,0-54.152,10.747,48.847,48.847,0,0,0,9.8,3.377A19.9,19.9,0,0,1,381.718,357.026Z"
            transform="translate(-351.734 -346.761)"
            fill="#822622"
          />
          <path
            id="Tracciato_99"
            data-name="Tracciato 99"
            d="M409.76,489.67H379.595v10.265h17.77A19.906,19.906,0,0,1,361,498.068a53.307,53.307,0,0,1-11.389-3.791,30.165,30.165,0,0,0,60.149-3.307h0Z"
            transform="translate(-349.611 -460.805)"
            fill="#7a7a7a"
          />
        </g>
        <g id="Raggruppa_40" data-name="Raggruppa 40" transform="translate(63.852 67.64)">
          <path
            id="Tracciato_100"
            data-name="Tracciato 100"
            d="M673.727,697.364v7.044H671.7V703.6a2.879,2.879,0,0,1-2.291.916,3.627,3.627,0,1,1,0-7.253,2.878,2.878,0,0,1,2.2.851v-.746Zm-2.086,3.522a1.876,1.876,0,1,0-1.868,1.951A1.818,1.818,0,0,0,671.641,700.885Z"
            transform="translate(-665.736 -694.104)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_101"
            data-name="Tracciato 101"
            d="M711.509,703.753l.709-1.466a5.179,5.179,0,0,0,2.55.681c.982,0,1.35-.249,1.35-.642,0-1.152-4.432.026-4.432-2.789,0-1.335,1.255-2.278,3.4-2.278a6.238,6.238,0,0,1,2.823.615l-.709,1.453a4.352,4.352,0,0,0-2.114-.524c-.955,0-1.364.288-1.364.655,0,1.2,4.432.039,4.432,2.815,0,1.309-1.268,2.239-3.464,2.239A6.5,6.5,0,0,1,711.509,703.753Z"
            transform="translate(-702.264 -694.104)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_102"
            data-name="Tracciato 102"
            d="M747.357,703.753l.709-1.466a5.177,5.177,0,0,0,2.55.681c.982,0,1.35-.249,1.35-.642,0-1.152-4.432.026-4.432-2.789,0-1.335,1.255-2.278,3.4-2.278a6.238,6.238,0,0,1,2.823.615l-.709,1.453a4.352,4.352,0,0,0-2.114-.524c-.955,0-1.364.288-1.364.655,0,1.2,4.432.039,4.432,2.815,0,1.309-1.268,2.239-3.464,2.239A6.5,6.5,0,0,1,747.357,703.753Z"
            transform="translate(-730.871 -694.104)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_103"
            data-name="Tracciato 103"
            d="M785.433,682.777a1.2,1.2,0,0,1,1.323-1.139,1.177,1.177,0,0,1,1.323,1.1,1.335,1.335,0,0,1-2.646.039Zm.259,2.121h2.127v7.044h-2.127Z"
            transform="translate(-761.256 -681.638)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_104"
            data-name="Tracciato 104"
            d="M804.336,700.885c0-2.121,1.7-3.627,4.091-3.627a3.394,3.394,0,0,1,3.286,1.794l-1.65.851a1.928,1.928,0,1,0-1.65,2.933,1.816,1.816,0,0,0,1.65-.969l1.65.864a3.429,3.429,0,0,1-3.286,1.78C806.041,704.512,804.336,703.007,804.336,700.885Z"
            transform="translate(-776.341 -694.104)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_105"
            data-name="Tracciato 105"
            d="M854.165,697.778v7.044h-2.018v-.838a3.026,3.026,0,0,1-2.277.943,2.864,2.864,0,0,1-3.136-3.168v-3.98h2.127v3.679c0,1.178.545,1.7,1.486,1.7.982,0,1.691-.6,1.691-1.9v-3.483Z"
            transform="translate(-810.175 -694.518)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_106"
            data-name="Tracciato 106"
            d="M897.871,697.259v1.885c-.177-.013-.314-.026-.477-.026a1.765,1.765,0,0,0-1.95,1.964v3.325h-2.127v-7.044h2.032v.93A3.016,3.016,0,0,1,897.871,697.259Z"
            transform="translate(-847.349 -694.104)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_107"
            data-name="Tracciato 107"
            d="M920.861,682.777a1.2,1.2,0,0,1,1.323-1.139,1.177,1.177,0,0,1,1.323,1.1,1.334,1.334,0,0,1-2.645.039Zm.259,2.121h2.127v7.044H921.12Z"
            transform="translate(-869.33 -681.638)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_108"
            data-name="Tracciato 108"
            d="M947.755,697.364v7.044h-2.032V703.6a2.878,2.878,0,0,1-2.291.916,3.627,3.627,0,1,1,0-7.253,2.878,2.878,0,0,1,2.2.851v-.746Zm-2.086,3.522a1.877,1.877,0,1,0-1.868,1.951A1.818,1.818,0,0,0,945.669,700.885Z"
            transform="translate(-884.415 -694.104)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_109"
            data-name="Tracciato 109"
            d="M1001.442,700.375v4.033h-2.127v-3.718c0-1.139-.532-1.663-1.391-1.663-.955,0-1.623.589-1.623,1.846v3.535h-2.127v-3.718c0-1.139-.5-1.663-1.391-1.663-.941,0-1.609.589-1.609,1.846v3.535h-2.127v-7.044h2.032v.812a3.032,3.032,0,0,1,2.3-.917,2.776,2.776,0,0,1,2.414,1.152,3.356,3.356,0,0,1,2.645-1.152A2.781,2.781,0,0,1,1001.442,700.375Z"
            transform="translate(-923.743 -694.104)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_110"
            data-name="Tracciato 110"
            d="M1057.571,700.885c0-2.121,1.7-3.627,4.036-3.627s4.023,1.506,4.023,3.627-1.691,3.627-4.023,3.627S1057.571,703.007,1057.571,700.885Zm5.9,0a1.876,1.876,0,1,0-1.868,1.951A1.81,1.81,0,0,0,1063.476,700.885Z"
            transform="translate(-978.427 -694.104)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_111"
            data-name="Tracciato 111"
            d="M1123.6,691.939v-7.384h2.127v7.266a.831.831,0,0,0,.913.93,1.437,1.437,0,0,0,.6-.131l.1,1.584a3.593,3.593,0,0,1-1.1.17A2.31,2.31,0,0,1,1123.6,691.939Z"
            transform="translate(-1031.117 -683.966)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_112"
            data-name="Tracciato 112"
            d="M1151.91,697.364v7.044h-2.032V703.6a2.879,2.879,0,0,1-2.291.916,3.627,3.627,0,1,1,0-7.253,2.878,2.878,0,0,1,2.2.851v-.746Zm-2.086,3.522a1.876,1.876,0,1,0-1.868,1.951A1.818,1.818,0,0,0,1149.823,700.885Z"
            transform="translate(-1047.334 -694.104)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_113"
            data-name="Tracciato 113"
            d="M1215.278,697.778l-3.1,7.044h-2.2l-3.082-7.044h2.2l2.032,4.792,2.1-4.792Z"
            transform="translate(-1097.598 -694.518)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_114"
            data-name="Tracciato 114"
            d="M1248.965,700.885c0-2.121,1.7-3.627,4.036-3.627s4.023,1.506,4.023,3.627-1.691,3.627-4.023,3.627S1248.965,703.007,1248.965,700.885Zm5.9,0a1.876,1.876,0,1,0-1.868,1.951A1.81,1.81,0,0,0,1254.87,700.885Z"
            transform="translate(-1131.162 -694.104)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_115"
            data-name="Tracciato 115"
            d="M1292.375,703.753l.709-1.466a5.179,5.179,0,0,0,2.55.681c.982,0,1.35-.249,1.35-.642,0-1.152-4.432.026-4.432-2.789,0-1.335,1.254-2.278,3.4-2.278a6.238,6.238,0,0,1,2.823.615l-.709,1.453a4.355,4.355,0,0,0-2.114-.524c-.954,0-1.364.288-1.364.655,0,1.2,4.432.039,4.432,2.815,0,1.309-1.268,2.239-3.464,2.239A6.5,6.5,0,0,1,1292.375,703.753Z"
            transform="translate(-1165.804 -694.104)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_116"
            data-name="Tracciato 116"
            d="M1335.991,698.326a3.121,3.121,0,0,1-1.718.445c-1.745,0-2.741-.851-2.741-2.527v-6.179h2.127v1.715h1.814v1.571h-1.814v2.867a.828.828,0,0,0,.9.93,1.549,1.549,0,0,0,.873-.262Z"
            transform="translate(-1197.052 -688.363)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_117"
            data-name="Tracciato 117"
            d="M1364.1,697.259v1.885c-.177-.013-.314-.026-.477-.026a1.766,1.766,0,0,0-1.95,1.964v3.325h-2.127v-7.044h2.032v.93A3.016,3.016,0,0,1,1364.1,697.259Z"
            transform="translate(-1219.409 -694.104)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_118"
            data-name="Tracciato 118"
            d="M1393.33,697.364v7.044H1391.3V703.6a2.878,2.878,0,0,1-2.291.916,3.627,3.627,0,1,1,0-7.253,2.878,2.878,0,0,1,2.2.851v-.746Zm-2.086,3.522a1.876,1.876,0,1,0-1.868,1.951A1.818,1.818,0,0,0,1391.244,700.885Z"
            transform="translate(-1239.991 -694.104)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_119"
            data-name="Tracciato 119"
            d="M1450.217,703.753l.709-1.466a5.179,5.179,0,0,0,2.55.681c.982,0,1.35-.249,1.35-.642,0-1.152-4.432.026-4.432-2.789,0-1.335,1.255-2.278,3.4-2.278a6.239,6.239,0,0,1,2.823.615l-.709,1.453a4.355,4.355,0,0,0-2.114-.524c-.954,0-1.364.288-1.364.655,0,1.2,4.432.039,4.432,2.815,0,1.309-1.268,2.239-3.464,2.239A6.5,6.5,0,0,1,1450.217,703.753Z"
            transform="translate(-1291.765 -694.104)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_120"
            data-name="Tracciato 120"
            d="M1492.957,702.195l1.132,1.178a3.925,3.925,0,0,1-3.027,1.139c-2.536,0-4.186-1.532-4.186-3.627a3.664,3.664,0,0,1,3.927-3.627,3.528,3.528,0,0,1,3.791,3.561l-5.427,1.008a1.948,1.948,0,0,0,1.95,1.048A2.544,2.544,0,0,0,1492.957,702.195Zm-4-1.519,3.573-.668a1.693,1.693,0,0,0-1.732-1.2A1.761,1.761,0,0,0,1488.961,700.676Z"
            transform="translate(-1321.018 -694.104)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_121"
            data-name="Tracciato 121"
            d="M1536.73,697.259v1.885c-.177-.013-.314-.026-.477-.026a1.765,1.765,0,0,0-1.95,1.964v3.325h-2.127v-7.044h2.032v.93A3.015,3.015,0,0,1,1536.73,697.259Z"
            transform="translate(-1357.169 -694.104)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_122"
            data-name="Tracciato 122"
            d="M1564.047,702.195l1.132,1.178a3.926,3.926,0,0,1-3.027,1.139c-2.536,0-4.187-1.532-4.187-3.627a3.665,3.665,0,0,1,3.928-3.627,3.528,3.528,0,0,1,3.791,3.561l-5.427,1.008a1.947,1.947,0,0,0,1.95,1.048A2.543,2.543,0,0,0,1564.047,702.195Zm-4-1.519,3.573-.668a1.693,1.693,0,0,0-1.732-1.2A1.761,1.761,0,0,0,1560.051,700.676Z"
            transform="translate(-1377.749 -694.104)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_123"
            data-name="Tracciato 123"
            d="M1610.766,700.375v4.033h-2.127v-3.718c0-1.139-.546-1.663-1.486-1.663a1.67,1.67,0,0,0-1.759,1.9v3.483h-2.127v-7.044h2.032v.825a3.282,3.282,0,0,1,2.427-.93A2.816,2.816,0,0,1,1610.766,700.375Z"
            transform="translate(-1413.9 -694.104)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_124"
            data-name="Tracciato 124"
            d="M1648.633,682.777a1.2,1.2,0,0,1,1.323-1.139,1.177,1.177,0,0,1,1.323,1.1,1.334,1.334,0,0,1-2.645.039Zm.259,2.121h2.127v7.044h-2.127Z"
            transform="translate(-1450.104 -681.638)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_125"
            data-name="Tracciato 125"
            d="M1674.493,698.326a3.12,3.12,0,0,1-1.718.445c-1.746,0-2.741-.851-2.741-2.527v-6.179h2.127v1.715h1.814v1.571h-1.814v2.867a.828.828,0,0,0,.9.93,1.55,1.55,0,0,0,.873-.262Z"
            transform="translate(-1467.182 -688.363)"
            fill="#7a7a7a"
          />
          <path
            id="Tracciato_126"
            data-name="Tracciato 126"
            d="M1702.193,686.761V693.8h-2.032v-.812a2.878,2.878,0,0,1-2.291.916,3.627,3.627,0,1,1,0-7.253,2.878,2.878,0,0,1,2.2.851v-.746Zm-4.091-.9-2.468-1.885h2.291l1.841,1.885Zm2,4.425a1.876,1.876,0,1,0-1.868,1.951A1.817,1.817,0,0,0,1700.106,690.282Z"
            transform="translate(-1486.469 -683.501)"
            fill="#7a7a7a"
          />
        </g>
      </g>
      <g
        id="Raggruppa_43"
        data-name="Raggruppa 43"
        transform="translate(191.162 346.412)"
      >
        <rect
          id="Rettangolo_101"
          data-name="Rettangolo 101"
          width="25.991"
          height="78.203"
          rx="12"
          transform="translate(0)"
          fill="#822622"
        />
        <g id="Raggruppa_42" data-name="Raggruppa 42" transform="translate(3.904 8.816)">
          <path
            id="Tracciato_127"
            data-name="Tracciato 127"
            d="M221.5,407.08v3.086h-3.869V407.08a8.436,8.436,0,0,1-7.146-8.428c0-4.938,3.846-8.594,9.092-8.594,5.223,0,9.092,3.632,9.092,8.594A8.438,8.438,0,0,1,221.5,407.08Zm3.276-8.428a5.2,5.2,0,1,0-7,5.056v-3.418h3.561v3.418A4.97,4.97,0,0,0,224.779,398.653Z"
            transform="translate(-210.488 -390.059)"
            fill="#fff"
          />
          <path
            id="Tracciato_128"
            data-name="Tracciato 128"
            d="M230.017,523.862l-1.994-1.852a8.893,8.893,0,0,1-5.555,1.852c-3.656,0-6.338-1.9-6.338-4.558,0-2.089,1.116-3.537,3.656-4.961a4.952,4.952,0,0,1-1.709-3.513c0-2.421,2.041-4.083,5.175-4.083,2.872,0,4.771,1.472,4.771,3.822,0,1.709-1,2.967-3.252,4.2l3.038,2.825a10.675,10.675,0,0,0,.831-2.588l2.967.926a10.961,10.961,0,0,1-1.519,3.774l1.947,1.8Zm-4.178-3.869-4.012-3.751c-1.519.878-2.065,1.638-2.065,2.659,0,1.211,1.139,2.065,2.967,2.065A5.315,5.315,0,0,0,225.839,519.992Zm-4.392-9.211c0,.641.261,1.116,1.282,2.113,1.638-.878,2.16-1.5,2.16-2.255,0-.807-.57-1.377-1.638-1.377C222.112,509.262,221.447,509.9,221.447,510.782Z"
            transform="translate(-214.99 -483.177)"
            fill="#fff"
          />
          <path
            id="Tracciato_129"
            data-name="Tracciato 129"
            d="M233.015,619.753c0,2.873-2.279,4.534-6.647,4.534h-8.594V607.67h8.119c4.154,0,6.291,1.733,6.291,4.32a4,4,0,0,1-2.208,3.656A4.1,4.1,0,0,1,233.015,619.753ZM221.6,610.566v3.917h3.822c1.875,0,2.9-.665,2.9-1.97s-1.021-1.947-2.9-1.947Zm7.549,8.784c0-1.4-1.068-2.065-3.062-2.065H221.6v4.107h4.487C228.078,621.391,229.146,620.774,229.146,619.35Z"
            transform="translate(-216.303 -563.716)"
            fill="#fff"
          />
        </g>
      </g>
    </g>
  </svg>
  <svg
    v-else-if="name === 'golinucci-logo-legacy'"
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    width="119.041"
    height="55.191"
    viewBox="0 0 119.041 55.191"
  >
    <image
      id="golinucci68"
      width="119.041"
      height="55.192"
      xlink:href="data:image/jpeg;base64,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"
    />
  </svg>
  <svg
    v-else-if="name === 'medici'"
    id="cardiogram"
    xmlns="http://www.w3.org/2000/svg"
    width="71.821"
    height="64.806"
    viewBox="0 0 71.821 64.806"
  >
    <path
      id="Tracciato_65"
      data-name="Tracciato 65"
      d="M35.89,30.966C20.235,17.273.331,29.3,0,45.613a21.177,21.177,0,0,0,3.5,11.938h15.48l2.753-4.589a2.1,2.1,0,0,1,3.579-.047l5.809,9.129L39.6,44.167a2.106,2.106,0,0,1,3.754-.1l7.259,13.481H68.272C81.353,37.106,55.5,13.815,35.89,30.966Z"
      transform="translate(0 -25.006)"
      fill="#fff"
    />
    <path
      id="Tracciato_66"
      data-name="Tracciato 66"
      d="M89.412,212.187,83.53,201.263l-8.318,17.56a2.105,2.105,0,0,1-3.676.229l-6.027-9.471-1.615,2.691a2.1,2.1,0,0,1-1.8,1.021H48.746c.418.438-1.811-1.787,27.569,27.441a2.1,2.1,0,0,0,2.968,0c28.928-28.778,27.151-27,27.569-27.441H91.264a2.1,2.1,0,0,1-1.852-1.106Z"
      transform="translate(-41.909 -176.54)"
      fill="#fff"
    />
  </svg>
  <svg
    v-else-if="name === 'bancari'"
    id="bank"
    xmlns="http://www.w3.org/2000/svg"
    width="81.999"
    height="81.999"
    viewBox="0 0 81.999 81.999"
  >
    <path
      id="Tracciato_134"
      data-name="Tracciato 134"
      d="M79.757,447H2.562A3.266,3.266,0,0,0,0,449.883v5.125a2.4,2.4,0,0,0,2.4,2.4H79.6a2.4,2.4,0,0,0,2.4-2.4v-5.125A3.356,3.356,0,0,0,79.757,447Z"
      transform="translate(0 -375.411)"
      fill="#fff"
    />
    <path
      id="Tracciato_135"
      data-name="Tracciato 135"
      d="M80.518,16.2,41.92.184a2.4,2.4,0,0,0-1.841,0L1.482,16.2A2.4,2.4,0,0,0,0,18.418v4.8a2.4,2.4,0,0,0,2.4,2.4H79.6a2.4,2.4,0,0,0,2.4-2.4v-4.8A2.4,2.4,0,0,0,80.518,16.2Zm-25.1,2.219H26.586a2.4,2.4,0,0,1,0-4.8H55.414a2.4,2.4,0,1,1,0,4.8Z"
      transform="translate(0 -0.001)"
      fill="#fff"
    />
    <path
      id="Tracciato_136"
      data-name="Tracciato 136"
      d="M28.5,377.4v4.8H46.918v-4.8a2.4,2.4,0,0,0-2.4-2.4H30.9A2.4,2.4,0,0,0,28.5,377.4Z"
      transform="translate(-23.936 -314.942)"
      fill="#fff"
    />
    <path
      id="Tracciato_137"
      data-name="Tracciato 137"
      d="M48.5,190H60.512v24.824H48.5Z"
      transform="translate(-40.732 -159.571)"
      fill="#fff"
    />
    <path
      id="Tracciato_138"
      data-name="Tracciato 138"
      d="M388.5,190h12.012v24.824H388.5Z"
      transform="translate(-326.28 -159.571)"
      fill="#fff"
    />
    <path
      id="Tracciato_139"
      data-name="Tracciato 139"
      d="M368.5,377.4v4.8h18.418v-4.8a2.4,2.4,0,0,0-2.4-2.4H370.9A2.4,2.4,0,0,0,368.5,377.4Z"
      transform="translate(-309.483 -314.942)"
      fill="#fff"
    />
    <path
      id="Tracciato_140"
      data-name="Tracciato 140"
      d="M218.207,232.422a2.4,2.4,0,0,1-2.4-2.4,2.4,2.4,0,0,0-4.8,0,7.218,7.218,0,0,0,4.8,6.794v1.214a2.4,2.4,0,1,0,4.8,0v-1.214a7.206,7.206,0,0,0-2.4-14,2.4,2.4,0,1,1,2.4-2.4,2.4,2.4,0,0,0,4.8,0,7.218,7.218,0,0,0-4.8-6.794V212.4a2.4,2.4,0,0,0-4.8,0v1.214a7.206,7.206,0,0,0,2.4,14,2.4,2.4,0,1,1,0,4.8Z"
      transform="translate(-177.207 -176.368)"
      fill="#fff"
    />
  </svg>
  <svg
    v-else-if="name === 'paypal'"
    xmlns="http://www.w3.org/2000/svg"
    width="340.435"
    height="87"
    viewBox="0 0 340.435 87"
  >
    <g id="paypal_1_" data-name="paypal (1)" transform="translate(0 -187.6)">
      <path
        id="Tracciato_67"
        data-name="Tracciato 67"
        d="M375.84,221.2H357.467c-1.081,0-2.161,1.081-2.7,2.161L347.2,270.914a1.433,1.433,0,0,0,1.621,1.621h9.727a1.433,1.433,0,0,0,1.621-1.621L362.33,257.4a2.472,2.472,0,0,1,2.7-2.161h5.944q18.643,0,21.075-17.832c1.081-4.863,0-9.186-2.161-11.888-3.242-2.7-8.106-4.323-14.05-4.323M378,239.032c-1.081,6.484-5.944,6.484-10.807,6.484h-3.242l2.161-12.429c0-.54.54-1.081,1.621-1.081h1.081c3.242,0,6.484,0,8.106,2.161,1.081.54,1.081,2.161,1.081,4.863"
        transform="translate(-112.678 -10.904)"
        fill="#139ad6"
      />
      <g
        id="Raggruppa_24"
        data-name="Raggruppa 24"
        transform="translate(102.131 210.296)"
      >
        <path
          id="Tracciato_68"
          data-name="Tracciato 68"
          d="M179.84,221.2H161.467c-1.081,0-2.161,1.081-2.7,2.161L151.2,270.914a1.433,1.433,0,0,0,1.621,1.621h8.646c1.081,0,2.161-1.081,2.7-2.161L166.33,257.4a2.472,2.472,0,0,1,2.7-2.161h5.944q18.643,0,21.075-17.832c1.081-4.863,0-9.186-2.161-11.888-3.242-2.7-7.565-4.323-14.05-4.323M182,239.032c-1.081,6.484-5.944,6.484-10.807,6.484h-2.7l2.161-12.429c0-.54.54-1.081,1.621-1.081h1.081c3.242,0,6.484,0,8.106,2.161.54.54,1.081,2.161.54,4.863"
          transform="translate(-151.2 -221.2)"
          fill="#263b80"
        />
        <path
          id="Tracciato_69"
          data-name="Tracciato 69"
          d="M255.846,246.281H247.2c-.54,0-1.621.54-1.621,1.081l-.54,2.7-.54-1.081c-2.161-2.7-5.944-3.783-10.267-3.783-9.727,0-18.373,7.565-19.994,17.832a16.106,16.106,0,0,0,3.242,13.509c2.7,3.242,6.484,4.323,11.348,4.323A17.272,17.272,0,0,0,241.256,276l-.54,2.7a1.433,1.433,0,0,0,1.621,1.621h8.106c1.081,0,2.161-1.081,2.7-2.161l4.863-30.261c-.54-.54-1.621-1.621-2.162-1.621m-12.429,17.292c-1.081,4.863-4.863,8.646-10.267,8.646a8.641,8.641,0,0,1-5.944-2.161,11.524,11.524,0,0,1-1.621-6.484,9.638,9.638,0,0,1,15.671-6.484c1.621,1.621,2.161,4.323,2.161,6.484"
          transform="translate(-171.548 -228.989)"
          fill="#263b80"
        />
      </g>
      <path
        id="Tracciato_70"
        data-name="Tracciato 70"
        d="M451.046,246.281H442.4c-.54,0-1.621.54-1.621,1.081l-.54,2.7-.54-1.081c-2.161-2.7-5.944-3.783-10.267-3.783-9.727,0-18.373,7.565-19.994,17.832a16.106,16.106,0,0,0,3.242,13.509c2.7,3.242,6.484,4.323,11.348,4.323A17.272,17.272,0,0,0,436.456,276l-.54,2.7a1.433,1.433,0,0,0,1.621,1.621h8.106c1.081,0,2.161-1.081,2.7-2.161l4.863-30.261c-.54-.54-1.081-1.621-2.161-1.621m-12.429,17.292c-1.081,4.863-4.863,8.646-10.267,8.646a8.641,8.641,0,0,1-5.944-2.161,11.524,11.524,0,0,1-1.621-6.484,9.638,9.638,0,0,1,15.671-6.484,6.2,6.2,0,0,1,2.162,6.484"
        transform="translate(-132.766 -18.693)"
        fill="#139ad6"
      />
      <path
        id="Tracciato_71"
        data-name="Tracciato 71"
        d="M325.609,246.8h-9.186a2.807,2.807,0,0,0-2.161,1.081l-11.888,18.373-5.4-17.292a2.5,2.5,0,0,0-2.7-1.621h-8.646c-1.081,0-1.621,1.081-1.621,2.161l9.727,28.64-9.186,12.969c-.54,1.081,0,2.7,1.081,2.7h8.646a2.807,2.807,0,0,0,2.161-1.081l29.721-42.689c1.621-1.621.54-3.242-.54-3.242"
        transform="translate(-92.168 -19.212)"
        fill="#263b80"
      />
      <path
        id="Tracciato_72"
        data-name="Tracciato 72"
        d="M482.765,222.979,475.2,271.612a1.433,1.433,0,0,0,1.621,1.621h7.565c1.081,0,2.161-1.081,2.7-2.162l7.565-47.553a1.433,1.433,0,0,0-1.621-1.621h-8.646c-.54-.54-1.081,0-1.621,1.081"
        transform="translate(-154.218 -11.062)"
        fill="#139ad6"
      />
      <path
        id="Tracciato_73"
        data-name="Tracciato 73"
        d="M62.143,194.084c-3.783-4.323-10.807-6.484-20.534-6.484H14.59c-1.621,0-3.242,1.621-3.783,3.242L0,261.631a2.472,2.472,0,0,0,2.161,2.7H18.913l4.323-26.478v1.081c.54-1.621,2.161-3.242,3.783-3.242h8.106c15.671,0,27.559-6.484,31.342-24.317v-1.621h0c.54-7.025-.54-11.348-4.323-15.671"
        fill="#263b80"
      />
      <path
        id="Tracciato_74"
        data-name="Tracciato 74"
        d="M74.234,220.4h0v1.621c-3.783,18.373-15.671,24.317-31.342,24.317H34.786c-1.621,0-3.242,1.621-3.783,3.242l-5.4,32.963a1.979,1.979,0,0,0,2.161,2.161h14.05c1.621,0,3.242-1.081,3.242-2.7v-.54l2.7-16.752V263.63c0-1.621,1.621-2.7,3.242-2.7h2.161c13.509,0,24.317-5.4,27.019-21.615,1.081-6.484.54-12.429-2.7-16.211a5.534,5.534,0,0,0-3.242-2.7"
        transform="translate(-8.308 -10.645)"
        fill="#139ad6"
      />
      <path
        id="Tracciato_75"
        data-name="Tracciato 75"
        d="M73.307,217.221c-.54,0-1.081-.54-1.621-.54a1.985,1.985,0,0,1-1.621-.54,28.547,28.547,0,0,0-7.025-.54H41.965a1.985,1.985,0,0,0-1.621.54,2.972,2.972,0,0,0-1.621,2.7l-4.323,28.1v1.081c.54-1.621,2.161-3.242,3.783-3.242h8.106c15.671,0,27.559-6.484,31.342-24.317a1.985,1.985,0,0,1,.54-1.621c-1.081-.54-1.621-1.081-2.7-1.081-1.621-.54-1.621-.54-2.161-.54"
        transform="translate(-11.164 -9.087)"
        fill="#232c65"
      />
    </g>
  </svg>
</template>

<script>
export default {
  props: {
    name: String,
  },
};
</script>
