<?php

namespace Tobexkee\LaravelOtp\Enums;

use Tobexkee\LaravelOtp\Enums\BaseEnum;

class OtpStatus extends BaseEnum
{
    public const GENERATED = 'generated';

    public const CONFIRMED = 'confirmed';

    public const EXPIRED = 'expired';
}
