<x-mail::message>
    <div style="background-color: #1a1a1a; padding: 40px 20px; text-align: center; border-radius: 8px;">
        <img src="https://placehold.co/200x50/F26724/ffffff?text=REPOSTEXCHANGE" alt="RepostChain Logo" style="max-width: 200px; margin-bottom: 30px;">
        <h1 style="color: #ffffff; font-size: 28px; font-weight: bold; margin-bottom: 20px;">
            {{ $name ?? 'User' }}, please confirm your email address
        </h1>

        <a href="{{ $verificationUrl }}" style="display: inline-block; background-color: #F26724; color: #ffffff; padding: 15px 30px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 16px; margin-bottom: 20px;">
            Confirm Email and Opt In to Newsletter
        </a>

        <p style="color: #aaaaaa; font-size: 14px; margin-top: 10px; margin-bottom: 20px;">Or just <a href="{{ $verificationUrl }}" style="color: #F26724; text-decoration: none;">confirm email address</a></p>

        <p style="color: #aaaaaa; font-size: 14px; line-height: 1.5; margin-top: 30px;">
            If the link above isn't working, please copy and paste the following into your browser:
        </p>
        <p style="word-break: break-all; color: #F26724; font-size: 12px; background-color: #333333; padding: 10px; border-radius: 5px; margin-top: 10px;">
            {{ $verificationUrl }}
        </p>

        <p style="color: #aaaaaa; font-size: 12px; margin-top: 30px;">
            You can <a href="#" style="color: #F26724; text-decoration: none;">change</a> your preferences at any time in your
            <a href="#" style="color: #F26724; text-decoration: none;">Settings</a>
        </p>
    </div>
</x-mail::message>