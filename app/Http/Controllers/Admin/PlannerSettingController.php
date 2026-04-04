<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlannerSetting;
use Illuminate\Http\Request;

class PlannerSettingController extends Controller
{
    private array $steps = [
        'intro' => 'Intro (Step 0)',
        'destinations' => 'Destinations (Step 1)',
        'travel_time' => 'Travel Time (Step 2)',
        'travel_group' => 'Travel Group (Step 3)',
        'interests' => 'Interests (Step 4)',
        'budget' => 'Budget (Step 5)',
        'contact' => 'Contact Details (Step 6)',
    ];

    public function edit()
    {
        $settings = PlannerSetting::all()->keyBy('step_key');
        $steps = $this->steps;

        return view('admin.planner-settings.edit', compact('settings', 'steps'));
    }

    public function update(Request $request)
    {
        foreach ($this->steps as $key => $label) {
            $title = $request->input("steps.{$key}.title");
            $description = $request->input("steps.{$key}.description");
            $options = $request->input("steps.{$key}.options");

            if (filled($title) || filled($description)) {
                PlannerSetting::updateOrCreate(
                    ['step_key' => $key],
                    [
                        'title' => $title ?? '',
                        'description' => $description,
                        'options' => $options ? array_values(array_filter(array_map('trim', explode("\n", $options)))) : null,
                    ]
                );
            }
        }

        return redirect()->route('admin.planner-settings.edit')
            ->with('success', 'Planner settings updated successfully.');
    }
}
