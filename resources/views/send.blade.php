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

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md mt-5 mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-center">Send Notifications Message</h1>

        <form action="{{ route('send-notification') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="message" class="block text-gray-700 text-sm font-bold mb-2">Message</label>
                <input type="text" id="message" name="message"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
            </div>

            <div class="mb-6">
                <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Receiver User ID
                    <small>(Optional - if not provided, all users will receive the message)</small></label>
                <input type="number" id="user_id" name="user_id" placeholder="Optional"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    Send Message
                </button>
            </div>
        </form>
    </div>

</body>

</html>
