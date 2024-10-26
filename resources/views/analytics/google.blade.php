@php
	$host = request()->getHttpHost();
@endphp

@if (!str_contains($host, 'localhost') && !str_contains($host, 'staging') && (1===2))
	<!-- Google tag (gtag.js) -->
 @endif
