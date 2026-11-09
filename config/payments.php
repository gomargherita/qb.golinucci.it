<?php

return [
    // Importi oltre questa soglia: solo bonifico (niente PayPal/Stripe).
    'electronic_max_eur' => (float) env('PAYMENT_ELECTRONIC_MAX_EUR', 1000),
];
