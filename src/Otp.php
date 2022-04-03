<?php

namespace Tobexkee\LaravelOtp;

use Illuminate\Support\Facades\Facade;
use Tobexkee\LaravelOtp\Interfaces\OtpInterface;

class Otp extends Facade
{
   protected static function getFacadeAccessor(): string
   {
       return OtpInterface::class;
   }
}
