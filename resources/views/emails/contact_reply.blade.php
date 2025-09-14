<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Phản hồi từ Shop</title>
</head>
<body>
    <h3>Xin chào {{ $contact->name }},</h3>

    <p>Bạn đã gửi cho shop lời nhắn:</p>
    <blockquote style="background:#f8f9fa;padding:10px;border-left:3px solid #0d6efd;">
        {{ $contact->message }}
    </blockquote>

    <p><strong>Phản hồi từ shop:</strong></p>
    <blockquote style="background:#e9f7ef;padding:10px;border-left:3px solid #28a745;">
        {{ $contact->reply }}
    </blockquote>

    <p>Cảm ơn bạn đã tin tưởng và liên hệ với chúng tôi! 💙</p>
</body>
</html>
