### Using Laravel OTP to generate OTP
```
<?php

include __DIR__.'/vendor/autoload.php';

use Tobexkee\LaravelOtp\Otp;

$otp = Otp::identifier("tobexkee@gmail.com")
    ->type('email')
    ->purpose('forgot-password')
    ->generate();

echo $otp->code;
```

### Using Laravel OTP to verify OTP
```
<?php

include __DIR__.'/vendor/autoload.php';

use Tobexkee\LaravelOtp\Otp;

Otp::verify($otp->code, "tobexkee@gmail.com", "email", "forgot-password")
```
