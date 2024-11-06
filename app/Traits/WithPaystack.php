<?php
namespace App\Traits;

use App\enums\HttpStatusCode;
use App\Domain\DTOs\ApiResponseDto;
use Unicodeveloper\Paystack\Paystack;

trait WithPaystack
{
    // https://sandbox-api-d.squadco.com/transaction/initiate
    public static function  makePaystackPayment($data){
        try{
            return Paystack::getAuthorizationUrl($data)->redirectNow();
        }catch(\Exception $e) {
            return new ApiResponseDto(false, "The paystack token has expired. Please refresh the page and try again", HttpStatusCode::BAD_REQUEST);
        }    
    }


  public function callbackstack(){
      return Paystack::getPaymentData();

  }


}
