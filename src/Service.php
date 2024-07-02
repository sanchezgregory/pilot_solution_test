<?php

namespace Src;

use Faker\ORM\Spot\ColumnTypeGuesser;

class Service
{
    const LIMIT = 100;
    const  MAX_INSTALLMENTS = 6;
    const ONE_INSTALLMENT = 1;
    const FEES_INCREASE = 3;
    private $primos = [];
    public function  getPrimoNumber()
    {
        for ($i = 1; $i <= self::LIMIT; $i++) {
            if ($this->isPrimo($i)) {
                $this->primos[] = $i;
            }
        }
        return $this->primos;
    }

    private function isPrimo($num)
    {
        if ($num <= 1 || $num % 2 == 0) {
            return false;
        }

        if ($num == 2) {
            return true;
        }

        for ($i = 3; $i < $num; $i++) {
            if ($num % $i == 0) {
                return false;
            }
        }

        return true;

    }

    public function storePayment($request)
    {
        $isValidCard = $this->validateCard($request->input('card_number'));
        if ($isValidCard) {
            return true;
        }

        return false;
    }

    public function validateCard($card) {
        if (preg_match('/^4\d{15}$/', $card)) {
            return true;
        }
        if (preg_match('/^3[47]\d{13}$/', $card)) {
            return true;
        }
        return false;
    }

    public function processPayment($request)
    {
        $installments = $request->input('installments');
        $amount = $request->input('amount');

        $fees = 0;

        if (!$this->validateInstallments($request->input('installments'))) {
            throw new \Exception('Invalid installments');
        }
        if ($installments > self::ONE_INSTALLMENT) {
            $fees = $this->calculateFees($request->input('amount'), $installments);
        }

        // TODO refactor for decimals
        $amount = $amount + $fees;

        $installments_amount = $this->calculateAmountPerInstallment($amount, $installments);

        $amount = $amount + $fees;

        if (!$this->validateEnoughAmountToPay($amount, $request->input('available_limit'))) {
            throw new \Exception('Limit card not enough to pay');
        }

        return [
            'customer' => $request->input('customer_name') . ' ' . $request->input('customer_surname'),
            'total_amount' => $amount,
            'amount_installments' => $installments_amount,
        ];
    }

    private function validateEnoughAmountToPay(float $total_amount, float $available_limit)
    {
        if ($total_amount > $available_limit) {
            return false;
        }
        return true;
    }

    private function calculateFees($amount, $installmentSelected)
    {
        $fees = 3 * ($installmentSelected - 1);
        $fees = $amount * ($fees / 100);
        return $fees;
    }

    private function validateInstallments($installments)
    {
        if ($installments > 0 && $installments <= self::MAX_INSTALLMENTS) {
            return true;
        }
        return false;
    }

    private function calculateAmountPerInstallment(mixed $amount, mixed $installments)
    {
        $total = $amount / $installments;
        return round($total, 2);
    }
}
