@component('mail::message')
    # {{ __('Appointment Confirmation') }}

    {{ __('Your appointment has been successfully booked!') }}

    @component('mail::panel')
        **{{ __('Service') }}:** {{ $service->name }}
        **{{ __('Health Professional') }}:** {{ $professional->name }}
        **{{ __('Date') }}:** {{ \Carbon\Carbon::parse($schedule)->format('Y-m-d H:i') }}
    @endcomponent

    @component('mail::button', ['url' => config('app.url')])
        {{ __('View Appointment') }}
    @endcomponent

    {{ __('Thank you for choosing us!') }}
    {{ config('app.name') }}
@endcomponent
