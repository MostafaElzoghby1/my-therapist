<?php
namespace App\Services;

use App\Models\PaymentMethod;
use App\Models\User;
use App\Traits\StripeService;

interface PaymentMethodStrategy
{
    public function add(array $data);
}
class CreditCard implements PaymentMethodStrategy
{
    use StripeService;

    public function add(array $data)
    {
        //read data
        $userId = $data['user_id'];
        $methodName = $data['method_name'];
        $paymentMethodId = $data['payment_method_id'];
        $ownerName = $data['owner_name'];
        $cardType = $data['card_type'];
        $userEmail = User::find($userId)->email;
        $stripeAccountId = User::find($userId)->stripe_account_id;

        $paymentMethods = PaymentMethod::where('user_id', $userId)
            ->where("type", "credit_card")->get();

        //if first -> create stripe account
        if ($paymentMethods) {
            $stripeAccountId = User::find($userId)->stripe_account_id;
            $paymentMethod = $this->attachPaymentMethod($paymentMethodId, $stripeAccountId);


            //add to account

        } else {

            //create customer account

            $patientAccount = $this->addPatientAccount($userEmail);

        }
        return $paymentMethod;
    }
}