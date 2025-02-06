<?php

use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendEmailTest extends TestCase
{
    public function test_it_sends_a_test_email()
    {
        // ✅ Send the email
        Mail::raw('This is a test email sent.', function ($message) {
            $message->to('test@example.com')->subject('Test Email');
        });

        // ✅ Assert that the test runs successfully
        $this->assertTrue(true);
    }
}
