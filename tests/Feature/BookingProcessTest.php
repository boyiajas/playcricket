<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Booking;
use Carbon\Carbon;

class BookingProcessTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_reservation_without_paynow_confirms_automatically(): void
    {
        $response = $this->postJson('/api/bookings', [
            'facility' => 'Pitch 1',
            'date' => Carbon::today()->toDateString(),
            'startTime' => '10:00',
            'durationHours' => 2,
            'payNow' => false,
            'memberId' => 'm1',
            'ageGroup' => 'Under 13',
            'playerName' => 'Young Star'
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'booking' => [
                    'facility' => 'Pitch 1',
                    'status' => 'CONFIRMED',
                    'payment_status' => 'UNPAID',
                    'lane' => 'Lane 1',
                    'age_group' => 'Under 13',
                    'player_name' => 'Young Star'
                ]
            ]);

        $bookingId = $response->json('booking.id');
        $this->assertDatabaseHas('bookings', [
            'id' => $bookingId,
            'lane' => 'Lane 1',
            'status' => 'CONFIRMED'
        ]);
    }

    public function test_lane_assignment_for_above_13(): void
    {
        $response = $this->postJson('/api/bookings', [
            'facility' => 'Indoor Nets',
            'date' => Carbon::tomorrow()->toDateString(),
            'startTime' => '14:00',
            'durationHours' => 1,
            'payNow' => false,
            'memberId' => 'm2',
            'ageGroup' => 'Above 13',
            'playerName' => 'Senior Pro'
        ]);

        $response->assertStatus(201);
        $this->assertEquals('Lane 2', $response->json('booking.lane'));
    }

    public function test_update_booking(): void
    {
        $booking = Booking::create([
            'facility' => 'Pitch 1',
            'date' => Carbon::today()->toDateString(),
            'start_time' => '09:00',
            'duration_hours' => 2,
            'status' => 'PENDING',
            'payment_status' => 'UNPAID',
            'member_id' => 'm1'
        ]);

        $updateResponse = $this->putJson("/api/bookings/{$booking->id}", [
            'facility' => 'Bowling Machine',
            'startTime' => '11:00',
            'durationHours' => 3,
            'status' => 'CONFIRMED',
            'ageGroup' => 'Under 13',
            'playerName' => 'Updated Player'
        ]);

        $updateResponse->assertStatus(200)
            ->assertJson([
                'booking' => [
                    'facility' => 'Bowling Machine',
                    'start_time' => '11:00',
                    'duration_hours' => 3,
                    'status' => 'CONFIRMED',
                    'lane' => 'Lane 1',
                    'player_name' => 'Updated Player'
                ]
            ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'facility' => 'Bowling Machine',
            'start_time' => '11:00'
        ]);
    }

    public function test_delete_booking(): void
    {
        $booking = Booking::create([
            'facility' => 'Pitch 2',
            'date' => Carbon::today()->toDateString(),
            'start_time' => '10:00',
            'duration_hours' => 1,
            'status' => 'PENDING',
            'member_id' => 'm1'
        ]);

        $response = $this->deleteJson("/api/bookings/{$booking->id}");
        $response->assertStatus(200);

        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    public function test_reports_endpoint_metrics(): void
    {
        // Create 2 bookings today (3 hrs and 2 hrs at R350 = R1050 + R700 = R1750)
        Booking::create([
            'facility' => 'Pitch 1',
            'date' => Carbon::today()->toDateString(),
            'start_time' => '09:00',
            'duration_hours' => 3,
            'status' => 'CONFIRMED',
            'payment_status' => 'PAID',
            'member_id' => 'm1'
        ]);

        Booking::create([
            'facility' => 'Indoor Nets',
            'date' => Carbon::today()->toDateString(),
            'start_time' => '14:00',
            'duration_hours' => 2,
            'status' => 'CONFIRMED',
            'payment_status' => 'PAID',
            'member_id' => 'm2'
        ]);

        $response = $this->getJson('/api/reports');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'daily' => ['bookings', 'revenue'],
                'weekly' => ['bookings', 'revenue'],
                'monthly' => ['bookings', 'revenue'],
                'total_bookings',
                'active_pitches'
            ]);

        $this->assertEquals(2, $response->json('daily.bookings'));
        $this->assertEquals(1750, $response->json('daily.revenue'));
        $this->assertEquals(2, $response->json('active_pitches'));
    }

    public function test_pricing_structure_lane_hire_junior_and_senior_coaching(): void
    {
        // 1. Lane Hire: R350/hr
        $laneResponse = $this->postJson('/api/bookings', [
            'facility' => 'Lane 1 Hire',
            'date' => Carbon::today()->toDateString(),
            'startTime' => '09:00',
            'durationHours' => 1,
            'payNow' => false,
            'memberId' => 'm1'
        ]);
        $laneResponse->assertStatus(201);
        $this->assertEquals(350.00, $laneResponse->json('booking.rate'));
        $this->assertEquals(350.00, $laneResponse->json('booking.amount'));

        // 2. Junior Coaching: R450/hr
        $juniorResponse = $this->postJson('/api/bookings', [
            'facility' => 'Junior Coaching',
            'date' => Carbon::today()->toDateString(),
            'startTime' => '10:00',
            'durationHours' => 2,
            'payNow' => false,
            'memberId' => 'm1',
            'ageGroup' => 'Under 13'
        ]);
        $juniorResponse->assertStatus(201);
        $this->assertEquals(450.00, $juniorResponse->json('booking.rate'));
        $this->assertEquals(900.00, $juniorResponse->json('booking.amount'));

        // 3. Senior Coaching: R600/hr
        $seniorResponse = $this->postJson('/api/bookings', [
            'facility' => 'Senior Coaching',
            'date' => Carbon::today()->toDateString(),
            'startTime' => '14:00',
            'durationHours' => 1.5,
            'payNow' => false,
            'memberId' => 'm2',
            'ageGroup' => 'Above 13'
        ]);
        $seniorResponse->assertStatus(201);
        $this->assertEquals(600.00, $seniorResponse->json('booking.rate'));
        $this->assertEquals(900.00, $seniorResponse->json('booking.amount'));
    }

    public function test_edward_seo_verification_and_diagnostics(): void
    {
        $ping = $this->getJson('/edward/verify');
        $ping->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'connected' => true,
                'site' => 'playcricket.co.za',
                'site_id' => '2563d695-bf11-43cd-861c-13ce2cd6b015'
            ]);

        $diag = $this->getJson('/edward/diagnostics');
        $diag->assertStatus(200)
            ->assertJson([
                'status' => 'healthy',
                'edward_script_enabled' => true
            ]);
    }

    public function test_edward_article_publishing_webhook(): void
    {
        $payload = [
            'title' => 'Advanced Spin Bowling Strategies for Cricket Lanes',
            'content' => '<p>Spin bowling in modern cricket requires subtle variations in pace and seam orientation.</p>',
            'excerpt' => 'A guide to advanced spin bowling techniques and practice routines.',
            'category' => 'Spin Bowling',
            'tags' => ['spin', 'drills', 'training'],
            'focus_keyword' => 'spin bowling cricket',
            'author' => 'PlayCricket Coaching Staff'
        ];

        $response = $this->postJson('/webhooks/edward', $payload, [
            'X-Edward-Key' => config('services.edward.api_key', 'pc_edward_key_2026')
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Article published successfully'
            ]);

        $this->assertDatabaseHas('articles', [
            'title' => 'Advanced Spin Bowling Strategies for Cricket Lanes',
            'category' => 'Spin Bowling',
            'focus_keyword' => 'spin bowling cricket',
            'status' => 'published'
        ]);
    }

    public function test_dynamic_sitemap_and_blog_view(): void
    {
        // Check sitemap.xml returns XML with playcricket urls
        $sitemapResponse = $this->get('/sitemap.xml');
        $sitemapResponse->assertStatus(200);
        $this->assertStringContainsString('application/xml', $sitemapResponse->headers->get('Content-Type'));
        $this->assertStringContainsString('<urlset', $sitemapResponse->getContent());
        $this->assertStringContainsString('/blog', $sitemapResponse->getContent());

        // Check blog index page
        $blogResponse = $this->get('/blog');
        $blogResponse->assertStatus(200);
        $blogResponse->assertSeeText('PlayCricket Journal');
    }

    public function test_edward_webhook_ping_event_with_hmac_signature(): void
    {
        $timestamp = (string) time();
        $payload = [
            'event' => 'ping',
            'id' => 'evt_' . uniqid(),
            'created_at' => now()->toIso8601String(),
        ];
        $rawPayload = json_encode($payload);
        $signingSecret = config('services.edward.signing_secret');
        $signature = 'sha256=' . hash_hmac('sha256', "{$timestamp}.{$rawPayload}", $signingSecret);

        $response = $this->call(
            'POST',
            '/webhooks/edward',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_EDWARD_EVENT' => 'ping',
                'HTTP_X_EDWARD_TIMESTAMP' => $timestamp,
                'HTTP_X_EDWARD_SIGNATURE' => $signature,
            ],
            $rawPayload
        );

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'external_id' => 'ping',
                'duplicate' => false,
            ]);
    }

    public function test_edward_webhook_article_published_event_with_nested_data_and_hmac(): void
    {
        $timestamp = (string) time();
        $payload = [
            'event' => 'article.published',
            'id' => 'evt_art_12345',
            'data' => [
                'edward_article_id' => 'edw_art_9999',
                'title' => 'Top 5 Batting Drills for Consistency and Power',
                'slug' => 'top-5-batting-drills-consistency-power',
                'content' => '<p>Mastering the back-foot punch and front-foot cover drive with regular lane drills.</p>',
                'meta_description' => 'Discover the top 5 cricket batting drills to improve consistency and power.',
                'featured_image_url' => 'https://playcricket.co.za/images/batting-drills.jpg',
                'category' => 'Batting Masterclass',
                'tags' => ['batting', 'drills', 'coaching'],
                'author' => 'Edward AI Coach',
            ],
        ];
        $rawPayload = json_encode($payload);
        $signingSecret = config('services.edward.signing_secret');
        $signature = 'sha256=' . hash_hmac('sha256', "{$timestamp}.{$rawPayload}", $signingSecret);

        $response = $this->call(
            'POST',
            '/webhooks/edward',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_EDWARD_EVENT' => 'article.published',
                'HTTP_X_EDWARD_TIMESTAMP' => $timestamp,
                'HTTP_X_EDWARD_SIGNATURE' => $signature,
            ],
            $rawPayload
        );

        $response->assertStatus(201)
            ->assertJson([
                'duplicate' => false,
                'status' => 'success',
            ]);

        $this->assertDatabaseHas('articles', [
            'external_id' => 'edw_art_9999',
            'slug' => 'top-5-batting-drills-consistency-power',
            'title' => 'Top 5 Batting Drills for Consistency and Power',
            'category' => 'Batting Masterclass',
        ]);
    }
}
