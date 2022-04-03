<?php


namespace Tobexkee\LaravelOtp;


class CodeGenerator
{
    private ?string $code;

    public function __construct(?string $code = null)
    {
        $this->code = $code;
    }

    public function generate()
    {
        if ($this->code) {
            return $this->code;
        }

        return rand(456, 999);
    }
}
