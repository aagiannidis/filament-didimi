<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Action Denied</title>
</head>
<body>
    <h1>Action Denied</h1>
    <p>{{ $message }}</p>
    <a href="{{ url()->previous() }}">Go Back</a>
</body>
</html>
