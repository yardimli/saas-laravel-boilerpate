<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<title>Pay USD.100</title>
{{--	<script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_SANDBOX_CLIENT_ID') }}"></script>--}}
</head>
<body>
<a class="btn btn-primary m-3" href="{{ route('processTransaction') }}">Pay USD.100</a>
</body>
</html>
