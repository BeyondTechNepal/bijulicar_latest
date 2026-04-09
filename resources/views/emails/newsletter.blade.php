<!DOCTYPE html>
<html>

<head>
    <title>{{ $subjectLine ?? 'Newsletter' }}</title>
</head>

<body style="font-family: sans-serif; background: #f7f7f7; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px;">
        {!! $bodyContent !!}
    </div>
</body>

</html>
