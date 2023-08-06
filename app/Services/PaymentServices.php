<?php
namespace App\Services;

interface PaymentServices
{
    public function __construct();
    public function createPayment($data);
    public function cancel();
    public function success($data);
}
