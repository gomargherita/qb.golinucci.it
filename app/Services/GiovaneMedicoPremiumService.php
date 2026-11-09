<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\ProductOffer;
use InvalidArgumentException;

/**
 * KAN-34: premio Giovane medico (prodotto 5) da anno iscrizione albo × massimale.
 * Tabella: docs/calcolo premio giovane medico 2025.xlsx
 * Riga 0 = iscritti nell'anno corrente; riga 10 = massimo.
 */
class GiovaneMedicoPremiumService
{
    public const PRODUCT_ID = 5;

    private const FEE = 25.0;
    private const ACCESSORY_RATE = 0.10;
    private const TAX_RATE = 0.2225;
    private const MAX_ROW = 10;
    /** Premio di riga 0 fino al 5° anno dopo l'iscrizione albo (2026 → fermo fino al 2031). */
    private const FIRST_YEAR_FREEZE_THROUGH_ROW = 5;

    /** @var array<int, array<int, float>> net premium by limit => row */
    private const NET = [
        1000000 => [180, 186, 192, 198, 204, 210, 216, 222, 228, 234, 240],
        2000000 => [225, 232.5, 240, 247.5, 255, 262.5, 270, 277.5, 285, 292.5, 300],
    ];

    public function isGiovaneMedico($product): bool
    {
        return $product && (int) $product->id === self::PRODUCT_ID;
    }

    public function rowForAlboYear(int $alboYear, ?int $nowYear = null): int
    {
        $nowYear = $nowYear ?: (int) date('Y');
        if ($alboYear > $nowYear) {
            throw new InvalidArgumentException('albo_year_future');
        }

        $row = $nowYear - $alboYear;
        if ($row < 0) {
            $row = 0;
        }
        if ($row > self::MAX_ROW) {
            $row = self::MAX_ROW;
        }

        return $row;
    }

    public function quote(int $alboYear, $limit, ?int $nowYear = null): array
    {
        $limit = (int) $limit;
        if (!isset(self::NET[$limit])) {
            throw new InvalidArgumentException('unknown_limit');
        }

        $row = $this->rowForAlboYear($alboYear, $nowYear);
        $tariffRow = $row <= self::FIRST_YEAR_FREEZE_THROUGH_ROW ? 0 : $row;
        $net = round((float) self::NET[$limit][$tariffRow], 2);
        $accessory = round($net * self::ACCESSORY_RATE, 2);
        $tax = round(($net + $accessory) * self::TAX_RATE, 2);
        $gross = round($net + $accessory + $tax, 2);
        $payable = round($gross + self::FEE, 2);

        return [
            'albo_enrollment_year' => $alboYear,
            'row' => $row,
            'tariff_row' => $tariffRow,
            'indemnity_limit' => $limit,
            'net_premium' => $net,
            'accessory' => $accessory,
            'tax' => $tax,
            'gross_premium' => $gross,
            'admin_fee' => self::FEE,
            'net_plus_access_premium' => round($net + $accessory, 2),
            'total_payable' => $payable,
        ];
    }

    public function applyToCart(Cart $cart, int $alboYear, ProductOffer $offer): void
    {
        $quote = $this->quote($alboYear, $offer->indemnity_limit);
        $cart->albo_enrollment_year = $alboYear;
        $cart->total_payable = $quote['total_payable'];
        $cart->admin_fee = $quote['admin_fee'];
        $cart->gross_premium = $quote['gross_premium'];
        $cart->tax = $quote['tax'];
        $cart->net_premium = $quote['net_premium'];
        $cart->accessory = $quote['accessory'];
        $cart->net_plus_access_premium = $quote['net_plus_access_premium'];
        $cart->indemnity_limit = $offer->indemnity_limit;
        $cart->indemnity_limit_patrimonial = $offer->indemnity_limit_patrimonial;
    }

    public function inertiaTariffs(?int $nowYear = null): array
    {
        $nowYear = $nowYear ?: (int) date('Y');
        $limits = [];
        foreach (array_keys(self::NET) as $limit) {
            $rows = [];
            for ($row = 0; $row <= self::MAX_ROW; $row++) {
                $alboYear = $nowYear - $row;
                $rows[$row] = $this->quote($alboYear, $limit, $nowYear);
            }
            $limits[(string) $limit] = $rows;
        }

        return [
            'current_year' => $nowYear,
            'min_year' => $nowYear - self::MAX_ROW,
            'limits' => $limits,
        ];
    }

    /**
     * Nuove polizze: solo retro 10 anni; nasconde il filtro 2/10.
     */
    public function presentNewBusiness($product): array
    {
        $fields = $product->conditionalFields
            ->filter(function ($field) {
                return (int) $field->id !== 11;
            })
            ->values();

        $offers = $product->productOffers
            ->filter(function ($offer) {
                return (int) $offer->indemnity_limit_patrimonial === 10;
            })
            ->map(function ($offer) {
                $offer->setRelation(
                    'conditionalFields',
                    $offer->conditionalFields->filter(function ($field) {
                        return (int) $field->id !== 11;
                    })->values()
                );

                return $offer;
            })
            ->values();

        return [$fields, $offers];
    }
}
