<!DOCTYPE html>
<head>
    <title>Password Reset</title>
</head>
<body>

<p>Dear {{ $admin->vendor->name }},</p>
<p>Your password has been reset successfully. Find your new password below</p>

<hr/>
<br>

<p>Password: <b>{{ $password }}</b></p>

</body>
