<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat Tư Vấn</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/css/chat.css">
</head>
<body>
    <!-- Nút tròn để mở chat -->
    <div class="chat-toggle">
        <i class="fas fa-comments"></i>
    </div>
    
    <!-- Khung chat -->
    <div class="chat-widget">
        <div class="chat-header">
            <h3>Chat Tư Vấn</h3>
            <button class="close-button">&times;</button>
        </div>
        <div id="chat-container" class="chat-container"></div>
        <div class="chat-input">
            <input type="text" id="message-input" placeholder="Nhập tin nhắn của bạn...">
            <button id="send-button">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
    <script>
        var baseUrl = "{{ url('/') }}";
    </script>
    <script src="/js/chat.js"></script>

    <script>
        // Kiểm tra xem script đã được tải chưa
        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOM loaded');
            if (typeof ChatBot === 'undefined') {
                console.error('ChatBot class not loaded');
            } else {
                console.log('ChatBot class loaded');
            }
        });
    </script>
</body>
</html> 