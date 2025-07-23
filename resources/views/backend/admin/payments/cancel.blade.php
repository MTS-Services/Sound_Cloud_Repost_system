<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; text-align: center; }
        .cancel { color: #dc3545; }
    </style>
</head>
<body>
    <h1 class="cancel">❌ Payment Cancelled</h1>
    <p>Your payment was cancelled. No charges were made to your card.</p>
    
    <a href="{{ route('f.payment.form') }}" style="display: inline-block; background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; margin-top: 20px;">Try Again</a>
</body>
</html>