<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Sent Notifications</title>
</head>

<body>


    <h1>Laravel Pusher Notifications</h1>
    <form action="{{ route('send-notification') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <input type="text" class="form-control" id="message" name="message">
            <input type="text" class="form-control" id="user_id" name="user_id" placeholder="Optional">
        </div>
        <button type="submit" class="btn btn-primary">Send Notification</button>
    </form>

</body>

</html>
