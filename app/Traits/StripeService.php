<?php

namespace App\Traits;
use App\Models\User;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentMethod;
use Stripe\PaymentIntent;
use Stripe\Transfer;
use Stripe\Refund;
use Stripe\Payout;
use \Stripe\Balance;
use Stripe\Account;
trait StripeService
{

    //config
    public function configre()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    //(patient || customer) account
    public function addPatientAccount($email)
    {
        $this->configre();

        return Customer::create([
            'email' => $email,
        ]);
    }

    public function addDoctorAccount($email)
    {

        $this->configre();

        $account = Account::create([
            'type' => 'express',
            'country' => 'egp',
            'email' => $email,
            'capabilities' => [
                'transfers' => ['requested' => true],
            ],
            'business_type' => 'individual',
        ]);

        return $account;
    }


    public function attachPaymentMethod($paymentMethodId, $PatientAccountId)
    {
        $this->configre();
        $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
        $paymentMethod->attach(['customer' => $PatientAccountId]);

        // تعيينها كـ default
        Customer::update($PatientAccountId, [
            'invoice_settings' => ['default_payment_method' => $paymentMethodId],
        ]);

        return $paymentMethod;
    }

    // create intent 
    public function createPaymentIntent($amount, $currency, $customerId, $paymentMethodId, $capture = false)
    {
        $this->configre();
        return PaymentIntent::create([
            'amount' => $amount,
            'currency' => $currency,
            'customer' => $customerId,
            'payment_method' => $paymentMethodId,
            'confirmation_method' => 'automatic',
            'confirm' => true,
            'capture_method' => 'manual',//for cancellation if occured
        ]);
    }

    //complete intent
    public function capturePaymentIntent($paymentIntentId)
    {
        $this->configre();
        $intent = PaymentIntent::retrieve($paymentIntentId);
        return $intent->capture();
    }


    //cancel before capture.
    public function cancelPaymentIntent($paymentIntentId)
    {
        $this->configre();
        $intent = PaymentIntent::retrieve($paymentIntentId);

        if (in_array($intent->status, ['requires_payment_method', 'requires_confirmation'])) {
            return $intent->cancel();
        }

        return response()->json(['error' => 'Cannot cancel this PaymentIntent.'], 400);
    }

    //refund after capture only
    public function refundPaymentIntent($paymentIntentId)
    {
        $this->configre();
        // استرجاع الـ PaymentIntent
        $intent = PaymentIntent::retrieve($paymentIntentId);

        // الحصول على الـ charge من الـ paymentIntent
        $chargeId = $intent->charges->data[0]->id;

        // إنشاء الاسترداد
        $refund = Refund::create([
            'charge' => $chargeId,
        ]);

        return $refund;
    }

    //transfer to doctor account
    public function createTransfer($amount, $currency, $destinationAccountId)
    {
        $this->configre();
        return Transfer::create([
            'amount' => $amount,
            'currency' => $currency,
            'destination' => $destinationAccountId,
        ]);
    }

    //at necessary case only.
    //stripe payout auto.

    public function manualPayoutAll($destinationAccountId)
    {
        $this->configre();
        // تهيئة Stripe

        // 1. جلب الرصيد من حساب الطبيب
        $balance = Balance::retrieve([
            'stripe_account' => $destinationAccountId,
        ]);

        // 2. استخراج الرصيد المتاح بعملة معينة (مثلاً EGP)
        $available = collect($balance->available);

        if (!$available || $available->amount < 1) {
            return "has no available mony";
        }

        // 3. إنشاء payout بكامل المبلغ المتاح
        $payout = Payout::create([
            'amount' => $available->amount,
            'currency' => 'egp',
        ], [
            'stripe_account' => $destinationAccountId,
        ]);

        return $payout;
    }
}