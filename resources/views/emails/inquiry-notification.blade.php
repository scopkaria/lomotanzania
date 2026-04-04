@component('mail::message')
# {{ $inquiry->inquiry_type === 'custom_tour' ? 'New Custom Tour Request' : 'New Safari Inquiry' }}

You have received a new {{ $inquiry->inquiry_type === 'custom_tour' ? 'custom tour request' : 'inquiry' }} from **{{ $inquiry->name }}**.

@component('mail::table')
| Detail | Info |
|:-------|:-----|
| **Name** | {{ $inquiry->name }} |
| **Email** | {{ $inquiry->email }} |
| **Phone** | {{ $inquiry->phone ?? '—' }} |
| **Country** | {{ $inquiry->country ?? '—' }} |
| **Safari** | {{ $inquiry->safariPackage?->title ?? 'General Inquiry' }} |
| **Travel Date** | {{ $inquiry->travel_date?->format('M d, Y') ?? '—' }} |
| **People** | {{ $inquiry->number_of_people ?? '—' }} |
@endcomponent

@if($inquiry->message)
**Message:**

{{ $inquiry->message }}
@endif

@component('mail::button', ['url' => url('/admin/inquiries/' . $inquiry->id)])
View Inquiry
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
