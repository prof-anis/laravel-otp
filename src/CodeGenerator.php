<?php


namespace Tobexkee\LaravelOtp;


use Illuminate\Support\Str;

class CodeGenerator
{
    public function generate(?string $code): ?string
    {
        if ($code) {
            return  $code;
        }

        $algo = config("laravel-otp.algo");

        if (method_exists($this, sprintf("%sCodeGenerator", $algo))) {
            return  call_user_func([$this, sprintf("%sCodeGenerator", $algo)]);
        }

        if (class_exists($algo)) {
            return call_user_func([$algo, 'generate']);
        }

        throw new \Exception("Invalid algorithm exception");
    }

    public function integerCodeGenerator(): int
    {
        $length = config('laravel-otp::number_of_otp_characters');

        return mt_rand(
            (int) sprintf("1%s", str_repeat("0", $length - 1)),
            (int) str_repeat("9", $length)
        );
    }

    public function stringCodeGenerator(): string
    {
        return Str::random(config('laravel-otp.number_of_otp_characters'));
    }

}
