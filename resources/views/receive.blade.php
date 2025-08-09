<!DOCTYPE html>
<html>

<head>
    <title>Laravel Pusher Notifications</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        const content_image_upload_url = '{{ route('file.ci_upload') }}';
    </script>
</head>

<body>

    <h1>Laravel Pusher Notifications</h1>


    <div id="notifications"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Listen to public channel
            window.Echo.channel('notifications')
                .listen('.notification.sent', (e) => {
                    console.log('Notification received:', e);
                    showNotification(e.message);
                    // alert(e.message);
                });

            // Listen to private channel (for authenticated users)
            @auth
            window.Echo.private('user.{{ auth()->id() }}')
                .listen('.notification.sent', (e) => {
                    console.log('Private notification received:', e);
                    showNotification(e.message);
                });
            @endauth

            function showNotification(message) {
                const notificationDiv = document.getElementById('notifications');
                const notification = document.createElement('div');
                notification.className = 'alert alert-info';
                notification.textContent = message;
                notificationDiv.appendChild(notification);


                // Auto-hide after 5 seconds
                setTimeout(() => {
                    notification.remove();
                }, 5000);
            }
        });
    </script>
</body>

</html>
