<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Chat Bot Thông Minh</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        .test-section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .test-button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
        }
        .test-button:hover {
            background: #45a049;
        }
        .response {
            margin-top: 10px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
            white-space: pre-wrap;
            max-height: 400px;
            overflow-y: auto;
        }
        .conversation-flow {
            background: #e8f5e8;
            border-left: 4px solid #4CAF50;
            padding: 10px;
            margin: 10px 0;
        }
        .chat-history {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <h1>Test Chat Bot Thông Minh - Tư Vấn Lịch Khám</h1>
    
    <div class="test-section">
        <h3>🎯 Test Cuộc Hội Thoại Thông Minh</h3>
        <div class="conversation-flow">
            <strong>Luồng hội thoại:</strong><br>
            1. Bot hỏi dịch vụ → 2. Bot hỏi khoa → 3. Bot gợi ý bác sĩ → 4. Bot tìm lịch khám
        </div>
        <button class="test-button" onclick="testConversation('Tôi muốn đặt lịch khám')">Bắt đầu đặt lịch</button>
        <button class="test-button" onclick="testConversation('Tôi muốn khám')">Muốn khám</button>
        <button class="test-button" onclick="testConversation('Đặt lịch')">Đặt lịch</button>
    </div>

    <div class="test-section">
        <h3>📋 Test Câu Hỏi Chi Tiết</h3>
        <button class="test-button" onclick="testConversation('Tôi muốn đặt lịch khám bác sĩ Nguyễn Văn A vào ngày mai')">Đặt lịch với bác sĩ cụ thể</button>
        <button class="test-button" onclick="testConversation('Tôi muốn khám vào ngày mai')">Khám ngày mai (không có bác sĩ)</button>
        <button class="test-button" onclick="testConversation('Tôi muốn tìm bác sĩ khám dịch vụ niềng răng vào ngày mai')">Tìm bác sĩ theo dịch vụ</button>
        <button class="test-button" onclick="testConversation('Tôi muốn tìm lịch khám còn trống dịch vụ nha khoa vào ngày mai')">Tìm lịch trống theo dịch vụ</button>
    </div>

    <div class="test-section">
        <h3>👨‍⚕️ Test Lịch Làm Việc</h3>
        <button class="test-button" onclick="testConversation('Bác sĩ Nguyễn Văn A có làm việc tuần này không')">Bác sĩ có làm việc tuần này</button>
        <button class="test-button" onclick="testConversation('Mai khoa nha khoa có các bác sĩ nào làm việc')">Bác sĩ làm việc theo khoa</button>
    </div>

    <div class="test-section">
        <h3>💰 Test Giá Khám</h3>
        <button class="test-button" onclick="testConversation('Giá khám bệnh của bác sĩ Nguyễn Văn A trên một lần khám là bao nhiêu')">Giá khám bác sĩ cụ thể</button>
        <button class="test-button" onclick="testConversation('Giá khám bệnh dịch vụ niềng răng trên một lần khám là bao nhiêu')">Giá khám dịch vụ</button>
    </div>

    <div class="test-section">
        <h3>🏥 Test Tư Vấn Dịch Vụ</h3>
        <button class="test-button" onclick="testConversation('Tôi cần tư vấn về dịch vụ')">Tư vấn dịch vụ</button>
        <button class="test-button" onclick="testConversation('Thông tin về niềng răng')">Thông tin niềng răng</button>
        <button class="test-button" onclick="testConversation('Tư vấn về implant')">Tư vấn implant</button>
    </div>

    <div class="test-section">
        <h3>📅 Test Đặt Lịch Chi Tiết</h3>
        <button class="test-button" onclick="testConversation('Tôi muốn đặt lịch khám bác sĩ Nguyễn Văn A vào ngày mai')">Đặt lịch đầy đủ thông tin</button>
        <button class="test-button" onclick="testConversation('Lịch khám còn trống của bác sĩ Nguyễn Văn A vào ngày mai')">Lịch còn trống</button>
        <button class="test-button" onclick="testConversation('Tìm lịch khám còn trống dịch vụ nha khoa vào ngày mai')">Lịch theo dịch vụ</button>
    </div>

    <div class="test-section">
        <h3>❓ Test Câu Hỏi Chung</h3>
        <button class="test-button" onclick="testConversation('Phòng khám có làm việc vào chủ nhật không')">Giờ làm việc</button>
        <button class="test-button" onclick="testConversation('Địa chỉ phòng khám ở đâu')">Địa chỉ</button>
        <button class="test-button" onclick="testConversation('Số điện thoại liên hệ')">Số điện thoại</button>
    </div>

    <div class="test-section">
        <h3>✏️ Test Tùy Chỉnh</h3>
        <input type="text" id="custom-message" placeholder="Nhập câu hỏi tùy chỉnh..." style="width: 400px; padding: 8px;">
        <button class="test-button" onclick="testCustomChat()">Gửi</button>
    </div>

    <div class="test-section">
        <h3>🔄 Lịch Sử Chat</h3>
        <div id="chat-history" class="chat-history"></div>
        <button class="test-button" onclick="clearHistory()">Xóa lịch sử</button>
    </div>

    <div id="response" class="response" style="display: none;"></div>

    <script>
        let conversationHistory = [];
        let currentUserId = 'test-user-' + Date.now();

        function testConversation(message) {
            addToHistory('User', message);
            document.getElementById('response').style.display = 'block';
            document.getElementById('response').textContent = 'Đang xử lý...';
            
            fetch('/api/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: message,
                    user_id: currentUserId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    addToHistory('Bot', data.message.message);
                    document.getElementById('response').textContent = 
                        'Câu hỏi: ' + message + '\n\n' +
                        'Trả lời: ' + data.message.message + '\n\n' +
                        'Độ tin cậy: ' + Math.round(data.confidence * 100) + '%' +
                        (data.context ? '\n\nContext: ' + JSON.stringify(data.context, null, 2) : '');
                } else {
                    document.getElementById('response').textContent = 'Lỗi: ' + data.message;
                }
            })
            .catch(error => {
                document.getElementById('response').textContent = 'Lỗi: ' + error.message;
            });
        }

        function testCustomChat() {
            const message = document.getElementById('custom-message').value;
            if (message.trim()) {
                testConversation(message);
                document.getElementById('custom-message').value = '';
            }
        }

        function addToHistory(sender, message) {
            conversationHistory.push({
                sender: sender,
                message: message,
                time: new Date().toLocaleTimeString()
            });
            updateHistoryDisplay();
        }

        function updateHistoryDisplay() {
            const historyDiv = document.getElementById('chat-history');
            historyDiv.innerHTML = conversationHistory.map(item => 
                `<strong>${item.sender} (${item.time}):</strong> ${item.message}`
            ).join('<br><br>');
            historyDiv.scrollTop = historyDiv.scrollHeight;
        }

        function clearHistory() {
            conversationHistory = [];
            updateHistoryDisplay();
            currentUserId = 'test-user-' + Date.now();
        }

        // Enter key support
        document.getElementById('custom-message').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                testCustomChat();
            }
        });

        // Auto-scroll response
        document.getElementById('response').addEventListener('scroll', function() {
            this.scrollTop = this.scrollHeight;
        });
    </script>
</body>
</html> 