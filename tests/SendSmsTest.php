<?php

namespace AKSoftware\MrSenderWrapper\Tests;

use AKSoftware\MrSenderWrapper\Sms;
use PHPUnit\Framework\TestCase;

class SendSmsTest extends TestCase
{
    public $aksms = null;

    public function setUp():void
    {
        $this->aksms = new Sms(USER_NAME, USER_PASS);
    }

    public function testSendTextSms()
    {
        $sendSms = $this->aksms->sendTextSms("example", SMS_NUMBER);
        $this->assertJson($sendSms, "success");
    }
}
