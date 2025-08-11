<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
</head>
<body>
    <div style="background-color: #f4f4f4; padding: 20px;">
        <h2>Hello!</h2>
        <p>You are receiving this email because we received a Verify Email request for your account.</p>
        <p>{{$user->code}}</p>
        <p>If you did not request a Verify Email, no further action is required.</p>
        <p>Regards,</p>
        <p>jinnedu.com</p>
    </div>
</body>
</html>