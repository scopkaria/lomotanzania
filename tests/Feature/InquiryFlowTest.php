<?php

namespace Tests\Feature;

use App\Mail\NewInquiryNotification;
use App\Models\Inquiry;
use App\Models\SafariPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InquiryFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_inquiry_form_submits_and_saves(): void
    {
        Mail::fake();

        $safari = SafariPackage::create([
            'title'    => 'Test Safari',
            'slug'     => 'test-safari',
            'status'   => 'published',
            'currency' => 'USD',
        ]);

        $response = $this->post(route('inquiries.store'), [
            'safari_package_id' => $safari->id,
            'name'              => 'Jane Doe',
            'email'             => 'jane@example.com',
            'phone'             => '+1234567890',
            'country'           => 'Kenya',
            'travel_date'       => now()->addMonth()->format('Y-m-d'),
            'number_of_people'  => 4,
            'message'           => 'Interested in a family safari.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('inquiry_sent', true);

        $this->assertDatabaseHas('inquiries', [
            'name'              => 'Jane Doe',
            'email'             => 'jane@example.com',
            'safari_package_id' => $safari->id,
            'status'            => 'new',
        ]);

        Mail::assertSent(NewInquiryNotification::class);
    }

    public function test_inquiry_validates_required_fields(): void
    {
        $response = $this->post(route('inquiries.store'), []);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function test_inquiry_works_without_safari_package(): void
    {
        Mail::fake();

        $response = $this->post(route('inquiries.store'), [
            'name'  => 'John',
            'email' => 'john@example.com',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('inquiries', [
            'name'              => 'John',
            'safari_package_id' => null,
        ]);
    }

    public function test_admin_can_view_inquiries(): void
    {
        $admin = \App\Models\User::factory()->create(['role' => 'admin']);

        Inquiry::create([
            'name'   => 'Test Lead',
            'email'  => 'lead@example.com',
            'status' => 'new',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.inquiries.index'));

        $response->assertOk();
        $response->assertSee('Test Lead');
    }

    public function test_admin_can_update_inquiry_status(): void
    {
        $admin = \App\Models\User::factory()->create(['role' => 'admin']);

        $inquiry = Inquiry::create([
            'name'   => 'Status Test',
            'email'  => 'status@example.com',
            'status' => 'new',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.inquiries.update', $inquiry), [
            'status' => 'contacted',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('inquiries', [
            'id'     => $inquiry->id,
            'status' => 'contacted',
        ]);
    }
}
