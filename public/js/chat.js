if (typeof window.ChatBot === 'undefined') {
    class ChatBot {
        constructor() {
            this.userId = 'guest-' + Math.random().toString(36).substr(2, 9);
            this.chatContainer = document.getElementById('chat-container');
            this.messageInput = document.getElementById('message-input');
            this.sendButton = document.getElementById('send-button');
            this.chatWidget = document.querySelector('.chat-widget');
            this.chatHeader = document.querySelector('.chat-header');
            this.closeButton = document.querySelector('.close-button');
            this.chatToggle = document.querySelector('.chat-toggle');
            this.isOpen = false;

            this.initialize();
            console.log('ChatBot initialized');
        }

        initialize() {
            // Xử lý gửi tin nhắn
            this.sendButton.addEventListener('click', () => this.sendMessage());
            this.messageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.sendMessage();
                }
            });

            // Xử lý đóng mở chat
            this.closeButton.addEventListener('click', (e) => {
                e.stopPropagation();
                this.hideChat();
            });

            this.chatToggle.addEventListener('click', () => {
                console.log('Chat toggle clicked');
                this.toggleChat();
            });

            // Tải lịch sử chat khi mở lần đầu
            this.chatToggle.addEventListener('click', () => {
                if (!this.isOpen) {
                    // this.loadHistory();
                    this.isOpen = true;
                }
            });

            console.log('Event listeners added');
        }

        toggleChat() {
            console.log('Toggling chat');
            if (this.chatWidget.classList.contains('active')) {
                this.hideChat();
            } else {
                this.showChat();
            }
        }

        showChat() {
            console.log('Showing chat');
            this.chatWidget.style.display = 'flex';
            // Đợi một frame để display: flex được áp dụng
            requestAnimationFrame(() => {
                this.chatWidget.classList.add('active');
                if (this.chatContainer.children.length === 0) {
                    this.addMessage('Xin chào! Tôi có thể giúp gì cho bạn?', true);
                }
            });
        }

        hideChat() {
            console.log('Hiding chat');
            this.chatWidget.classList.remove('active');
            // Đợi animation kết thúc rồi mới ẩn hoàn toàn
            setTimeout(() => {
                if (!this.chatWidget.classList.contains('active')) {
                    this.chatWidget.style.display = 'none';
                }
            }, 300);
        }

        async sendMessage() {
            const message = this.messageInput.value.trim();
            if (!message) return;

            this.addMessage(message, false);
            this.messageInput.value = '';

            // Kiểm tra xem có phải là lệnh dạy bot không
            if (message.startsWith('dạy bot:')) {
                await this.teachBot(message);
                return;
            }

            try {
                const response = await fetch(baseUrl + '/api/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message: message,
                        user_id: this.userId
                    })
                });

                const data = await response.json();
                if (data.success) {
                    this.addMessage(data.message.message, true);

                    // Hiển thị độ tin cậy của câu trả lời
                }
            } catch (error) {
                console.error('Error sending message:', error);
                this.addMessage('Có lỗi xảy ra, vui lòng thử lại sau', true);
            }
        }

        async teachBot(message) {
            try {
                // Phân tích cú pháp lệnh dạy bot
                const parts = message.split(':')[1].split('-');
                if (parts.length !== 2) {
                    this.addMessage('Cú pháp không đúng. Vui lòng sử dụng: dạy bot: [câu hỏi] - [câu trả lời]', true);
                    return;
                }

                const question = parts[0].trim();
                const answer = parts[1].trim();

                const response = await fetch(baseUrl + '/api/chat/teach', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        question: question,
                        answer: answer
                    })
                });

                const data = await response.json();
                this.addMessage(data.message, true);
            } catch (error) {
                console.error('Error teaching bot:', error);
                this.addMessage('Có lỗi xảy ra khi dạy bot', true);
            }
        }

        async loadHistory() {
            try {
                const response = await fetch(baseUrl + `/api/chat/history?user_id=${this.userId}`);
                const data = await response.json();

                if (data.success) {
                    data.messages.forEach(msg => {
                        this.addMessage(msg.message, msg.is_bot);
                    });
                }
            } catch (error) {
                console.error('Error loading history:', error);
            }
        }

        addMessage(message, isBot) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isBot ? 'bot' : 'user'}`;
            // Sử dụng innerHTML thay vì textContent để hỗ trợ HTML
            messageDiv.innerHTML = message;

            this.chatContainer.appendChild(messageDiv);
            this.chatContainer.scrollTop = this.chatContainer.scrollHeight;
        }
    }
    window.ChatBot = ChatBot;
}

// Khởi tạo chatbot khi trang web được tải
document.addEventListener('DOMContentLoaded', () => {
    console.log('Initializing ChatBot');
    new ChatBot();
}); 