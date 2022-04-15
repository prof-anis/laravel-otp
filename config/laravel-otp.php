<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OTP VALIDITY DURATION
    |--------------------------------------------------------------------------
    | This is the time in seconds from when an OTP would expire and cannot be used again unless another is
    | generated.
    */
    'otp_validity_duration' => 10,
    /*
   |--------------------------------------------------------------------------
   | Number of OTP Characters
   |--------------------------------------------------------------------------
   | This is the number of characters the OTP would contain
   */
    'number_of_otp_characters' => 5,
    /*
   |--------------------------------------------------------------------------
   | OTP Generation Algo
   |--------------------------------------------------------------------------
   | This is the algorithm that would be used in generating the OTP. The package supports different
   |  options which are string and integer. You can also use custom classes
   */
    'algo' => 'string'
];
