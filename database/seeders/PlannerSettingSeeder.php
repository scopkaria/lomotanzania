<?php

namespace Database\Seeders;

use App\Models\PlannerSetting;
use Illuminate\Database\Seeder;

class PlannerSettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            [
                'step_key' => 'intro',
                'title' => 'Your dream African safari starts here',
                'description' => 'Answer a few quick questions and our safari experts will craft a personalised itinerary tailored to your pace, interests, and budget.',
            ],
            [
                'step_key' => 'destinations',
                'title' => 'Where would you like to travel?',
                'description' => 'Select one or more destinations that interest you.',
            ],
            [
                'step_key' => 'travel_time',
                'title' => 'When would you like to travel?',
                'description' => 'Select your preferred travel months.',
            ],
            [
                'step_key' => 'travel_group',
                'title' => 'Who are you traveling with?',
                'description' => 'This helps us tailor your experience.',
            ],
            [
                'step_key' => 'interests',
                'title' => 'What experiences excite you most?',
                'description' => 'Select all that appeal to you.',
            ],
            [
                'step_key' => 'budget',
                'title' => 'What is your budget range?',
                'description' => 'Per person, approximate range.',
                'options' => ['$2,000 – $5,000', '$5,000 – $10,000', '$10,000 – $20,000', '$20,000+'],
            ],
            [
                'step_key' => 'contact',
                'title' => 'How should we contact you?',
                'description' => 'Share your details so our team can reach out.',
            ],
        ];

        foreach ($defaults as $data) {
            PlannerSetting::firstOrCreate(
                ['step_key' => $data['step_key']],
                $data,
            );
        }
    }
}
