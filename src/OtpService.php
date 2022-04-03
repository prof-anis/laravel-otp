<?php

namespace Tobexkee\LaravelOtp;

use Illuminate\Support\Carbon;
use Tobexkee\LaravelOtp\Enums\OtpStatus;
use Tobexkee\LaravelOtp\Events\OtpGeneratedEvent;
use Tobexkee\LaravelOtp\Interfaces\OtpInterface;
use Tobexkee\LaravelOtp\Models\LaravelOtp;

class OtpService implements OtpInterface
{
    private string $identifier;

    private string $purpose;

    private string|array $type;

    private string $code = "";

    private int $delay;

    private string $status = "";

    private LaravelOtp $otp;

    public function type(string|array $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function purpose(string $purpose): static
    {
        $this->purpose = $purpose;

        return $this;
    }

    public function identifier(string $identifier): static
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function code(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function delay(int $delay): static
    {
        $this->delay = $delay;

        return $this;
    }

    public function getDelay(): int
    {
        return $this->delay;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getPurpose(): string
    {
        return $this->purpose;
    }

    public function getType(): array|string
    {
        return $this->type;
    }

    public function expireAt(int|Carbon $expire)
    {
        $this->expire = $expire;

        return $this;
    }

    public function generate(
        ?string $identifier = null,
        ?string $type = null,
        ?string $purpose = null,
        ?string $code = null
    ): bool | LaravelOtp
    {

        $identifier = $identifier ?: $this->identifier;
        $type = $type ?: $this->type;
        $purpose = $purpose ?: $this->purpose;

        if (! $this->authorize($identifier, $type, $purpose)) {
            return false;
        }

        $this->removeExisitingOtp($type, $identifier, $purpose);

        $code = (new CodeGenerator($code ?: $this->code))->generate();

        $this->dispatchOtpEvents($otp = $this->createNewOtp($identifier, $type, $code, $purpose));

        return $otp;
    }

    public function status(): string
    {
        return $this->status;
    }

    private function removeExisitingOtp($type, $identifier, $purpose): void
    {
        LaravelOtp::where('type', $type)
            ->where('identifier', $identifier)
            ->where('status', OtpStatus::GENERATED)
            ->where('purpose', $purpose)
            ->delete();
    }

    public function authorize(string $identifier, string $type, string $purpose): bool
    {
        $otp = LaravelOtp::where('type', $type)
            ->where('identifier', $identifier)
            ->where('status', OtpStatus::GENERATED)
            ->where('purpose', $purpose)
            ->latest()
            ->first();

        if ($otp && $otp->expire_at->gt(now())) {

            $this->status = 'Please wait before retrying.';

            return false;
        }

        return true;
    }

    private function dispatchOtpEvents(LaravelOtp $otp)
    {
        OtpGeneratedEvent::dispatch($otp);
    }

    private function createNewOtp(string $identifier, string $type, string $code, string $purpose): LaravelOtp
    {
        return $this->otp = LaravelOtp::create([
            'identifier' => $identifier,
            'type' => $type,
            'code' => $code,
            'status' => OtpStatus::GENERATED,
            'purpose' => $purpose,
            'expire_at' => $this->getExpiryTime()
        ]);
    }

    protected function getExpiryTime()
    {
        if (!$this->expire) {
            return now()->addSeconds(config('laravel-otp::otp_validity_duration'));
        }

        return $this->expire instanceof Carbon
            ? $this->expire
            : Carbon::now()->addSeconds($this->expire);
    }

    public function verify(string $code, string $identifier, string $type, string $purpose): bool
    {
        $otp = LaravelOtp::where('type', $type)
            ->where('identifier', $identifier)
            ->where('status', OtpStatus::GENERATED)
            ->where('code', $code)
            ->where('purpose', $purpose)
            ->first();

        if ($otp && $this->resolveOtpStatus($otp)) {
            $otp->delete();

            return true;
        }

        return false;
    }

    public function resolveOtpStatus(LaravelOtp $otp): bool
    {
        if ($otp->expire_at->lt(now())) {
            $this->status = 'The OTP is expired';
            $otp->delete();

            return false;
        }

        return true;
    }

    public function generateCodeUsing(callable $callable)
    {
        return call_user_func($callable);
    }


}
