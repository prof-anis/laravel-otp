<?php
namespace Tobexkee\LaravelOtp\Tests;

use Illuminate\Support\Facades\Event;
use Tobexkee\LaravelOtp\Enums\OtpStatus;
use Tobexkee\LaravelOtp\Events\OtpGeneratedEvent;
use Tobexkee\LaravelOtp\Models\LaravelOtp;
use Tobexkee\LaravelOtp\Otp;
use Tobexkee\LaravelOtp\Tests\TestCase;

class OtpTest extends TestCase
{
    public function test_will_generate_otp()
    {
        Event::fake();

        $otp = Otp::identifier("tobexkee@gmail.com")
            ->type('email')
            ->purpose('forgot-password')
            ->generate();

        $this->assertTrue($otp instanceof LaravelOtp);

        $this->assertDatabaseHas('laravel_otps', [
            'type' => 'email',
            'purpose' => 'forgot-password',
            'identifier' => 'tobexkee@gmail.com',
            'code' => $otp->code
        ]);

        $this->assertDatabaseCount('laravel_otps', 1);

        Event::assertDispatched(OtpGeneratedEvent::class);
    }

    public function test_will_not_generate_otp_if_current_otp_has_not_expired()
    {
        $otp = Otp::identifier("tobexkee@gmail.com")
            ->type('email')
            ->expireAt(now()->addMinutes(500))
            ->purpose('forgot-password')
            ->generate();

        $this->assertTrue($otp instanceof LaravelOtp);

        $newOtp = Otp::identifier("tobexkee@gmail.com")
            ->type('email')
            ->expireAt(now()->addSeconds(500))
            ->purpose('forgot-password')
            ->generate();

        $this->assertFalse($newOtp);
    }

    public function test_will_generate_new_otp_if_previous_otp_expired()
    {
        $otp = Otp::identifier("tobexkee@gmail.com")
            ->type('email')
            ->expireAt(now()->addSeconds(500))
            ->purpose('forgot-password')
            ->generate();

        $this->assertTrue($otp instanceof LaravelOtp);

        $this->travelTo(now()->addSeconds(500));

        $newOtp = Otp::identifier("tobexkee@gmail.com")
            ->type('email')
            ->expireAt(now()->addSeconds(500))
            ->purpose('forgot-password')
            ->generate();

        $this->assertTrue($newOtp instanceof LaravelOtp);
    }

    public function test_it_will_verify_generated_otp()
    {
        $otp = Otp::identifier("tobexkee@gmail.com")
            ->type('email')
            ->expireAt(now()->addSeconds(500))
            ->purpose('forgot-password')
            ->generate();

        $this->assertTrue(Otp::verify($otp->code, "tobexkee@gmail.com", "email", "forgot-password"));
    }

    public function test_it_will_generate_number_of_characters_specified_in_config()
    {
        config(['laravel-otp.number_of_otp_characters' => 10]);

        $otp = Otp::identifier("tobexkee@gmail.com")
            ->type('email')
            ->expireAt(now()->addSeconds(500))
            ->purpose('forgot-password')
            ->generate();

        $this->assertSame(strlen($otp->code), 10);
    }

    public function test_it_will_generate_otp_using_custom_otp_generation_class()
    {
        $customClass = new class {
          public static function generate() {
              return "anis";
          }

        };

        config(['laravel-otp.algo' =>  get_class($customClass)]);

        $otp = Otp::identifier("tobexkee@gmail.com")
            ->type('email')
            ->expireAt(now()->addSeconds(500))
            ->purpose('forgot-password')
            ->generate();

        $this->assertSame($otp->code, "anis");
    }
}
