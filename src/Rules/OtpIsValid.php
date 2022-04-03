<?php
namespace Tobexkee\LaravelOtp\Rules;


use Illuminate\Contracts\Validation\Rule;
use Tobexkee\LaravelOtp\Interfaces\OtpInterface;
use Tobexkee\LaravelOtp\Otp;

class OtpIsValid implements Rule
{

    public $identifier;

    public $type;

    public $purpose;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($identifier, $type, $purpose)
    {
        $this->purpose = $purpose;
        $this->type = $type;
        $this->identifier = $identifier;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return Otp::verify(request()->get('token'), $this->identifier, $this->type, $this->purpose);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Invalid OTP.';
    }
}
