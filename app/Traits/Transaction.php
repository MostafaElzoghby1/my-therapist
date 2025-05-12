<?php
namespace App\Traits;

use App\Models\PaymentTransaction;
use Stripe\PaymentIntent;
use Stripe\Transfer;
use Stripe\Payout;

trait Transaction
{
    /**
     * حفظ العملية بعد نجاح الدفع.
     *
     * @param PaymentIntent $paymentIntent
     * @param int $patientId
     * @param int $doctorId
     * @param int $platformFee
     * @return Transaction
     */
    public function storeTransactionFromPaymentIntent(PaymentIntent $paymentIntent, $patientId, $doctorId, $platformFee)
    {
        return Transaction::create([
            'patient_id'        => $patientId,
            'doctor_id'         => $doctorId,
            'stripe_payment_id' => $paymentIntent->id,
            'status'            => 'paid',
            'amount'            => $paymentIntent->amount,
            'platform_fee'      => $platformFee,
            'doctor_earnings'   => $paymentIntent->amount - $platformFee,
            'payment_time'      => now(),
        ]);
    }

    /**
     * تحديث المعاملة بعد عمل transfer للطبيب.
     *
     * @param string $stripePaymentId
     * @param string $transferId
     * @return bool
     */
    public function updateTransferInfo($stripePaymentId, $transferId)
    {
        return Transaction::where('stripe_payment_id', $stripePaymentId)
            ->update([
                'stripe_transfer_id' => $transferId,
                'transfer_time' => now(),
            ]);
    }

    /**
     * تحديث حالة العملية إلى refunded.
     *
     * @param string $stripePaymentId
     * @return bool
     */
    public function markAsRefunded($stripePaymentId)
    {
        return Transaction::where('stripe_payment_id', $stripePaymentId)
            ->update(['status' => 'refunded']);
    }

    /**
     * جلب تقرير العمليات للمستخدم.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
 
}
