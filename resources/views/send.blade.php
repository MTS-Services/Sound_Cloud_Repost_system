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
        <button type="submit" class="btn btn-success">Send Notification</button>
    </form>

    <a href="{{ route('say-hi') }}" class="btn btn-primary mt-10">Say Hi</a>

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md mt-5 mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-center">Send Private Message</h1>

        <form action="{{ route('send-private-message.send') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="message" class="block text-gray-700 text-sm font-bold mb-2">Message</label>
                <input type="text" id="message" name="message" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-6">
                <label for="recipient_id" class="block text-gray-700 text-sm font-bold mb-2">Recipient User ID</label>
                <input type="number" id="recipient_id" name="recipient_id" placeholder="e.g., 1, 2, 3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    Send Private Message
                </button>
            </div>
        </form>
    </div>

</body>

</html>
