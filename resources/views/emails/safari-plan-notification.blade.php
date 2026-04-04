@component('mail::message')
# New Safari Plan Submission

A visitor has submitted a safari planning request.

@component('mail::table')
| Detail | Info |
|:-------|:-----|
| **Name** | {{ $plan->first_name }} {{ $plan->last_name }} |
| **Email** | {{ $plan->email }} |
| **Phone** | {{ $plan->country_code ? $plan->country_code . ' ' : '' }}{{ $plan->phone ?? '—' }} |
| **Destinations** | {{ is_array($plan->destinations) ? implode(', ', $plan->destinations) : '—' }} |
| **Travel Months** | {{ is_array($plan->months) ? implode(', ', $plan->months) : '—' }} |
| **Travel Group** | {{ $plan->travel_group ?? '—' }} |
| **Interests** | {{ is_array($plan->interests) ? implode(', ', $plan->interests) : '—' }} |
| **Budget** | {{ $plan->budget_range ?? '—' }} |
| **Contact Methods** | {{ is_array($plan->contact_methods) ? implode(', ', $plan->contact_methods) : '—' }} |
| **Wants Updates** | {{ $plan->wants_updates ? 'Yes' : 'No' }} |
@if($plan->safariPackage)
| **From Safari** | {{ $plan->safariPackage->title }} |
@endif
@endcomponent

@component('mail::button', ['url' => url('/admin/safari-plans/' . $plan->id)])
View Plan Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
