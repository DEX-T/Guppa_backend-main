<?php

namespace App\Domain\Interfaces\Transactions;;

use Illuminate\Http\Request;

interface ITransactionService
{
    public function getAllPayments();
    public function getAllFreelancerPayments();
    public function getAllClientPayments();
    public function getPaymentById(int $Id);
    public function getEarnings(int $userId);

    public function buyBid(Request $request);
    public function payForApprovedJob(Request $request);
    public function getWayCallback(Request $request);
    public function verifyPayment(Request $request);
    public function verifyGuppaPayment(Request $request);
    public function pendingPayment();


}
