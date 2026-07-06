<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redirecting to PaySo</title>
</head>
<body>
    <form id="payso_redirect_form" action="{{ $paymentUrl }}" method="post">
        @foreach ($payload as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <noscript>
            <button type="submit">ชำระเงินผ่าน PaySo</button>
        </noscript>
    </form>
    <script>
        document.getElementById('payso_redirect_form').submit();
    </script>
</body>
</html>
