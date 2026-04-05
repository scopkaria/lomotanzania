<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_combines_country_code_and_phone_number(): void
    {
        $response = $this->post('/en/contact', [
            'name' => 'Jane Traveler',
            'email' => 'jane@example.com',
            'country_code' => '+255',
            'phone' => '712345678',
            'subject' => 'Safari planning',
            'message' => 'Please help me plan a safari.',
            'form_started_at' => now()->subSeconds(10)->timestamp,
            'website' => '',
        ]);

        $response->assertRedirect(route('contact', ['locale' => 'en']));

        $this->assertDatabaseHas('inquiries', [
            'inquiry_type' => 'contact',
            'email' => 'jane@example.com',
            'phone' => '+255 712345678',
        ]);
    }

    public function test_contact_form_blocks_honeypot_spam_submissions(): void
    {
        $response = $this->from('/en/contact')->post('/en/contact', [
            'name' => 'Spam Bot',
            'email' => 'spam@example.com',
            'country_code' => '+1',
            'phone' => '5551234',
            'subject' => 'Spam',
            'message' => 'Spam message',
            'form_started_at' => now()->subSeconds(10)->timestamp,
            'website' => 'https://spam.test',
        ]);

        $response->assertRedirect('/en/contact');
        $response->assertSessionHasErrors('spam');

        $this->assertDatabaseMissing('inquiries', [
            'email' => 'spam@example.com',
        ]);
    }
}
