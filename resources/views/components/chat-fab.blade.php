<!-- Floating Action Button (FAB) for Chat -->

<div class="chat-fab" onclick="openChatModal()" title="Chat dengan Kami">
    💬
</div>

<!-- Chat Modal -->
<div id="chatModal" class="chat-modal">
    <div class="chat-modal-content">
        <!-- Modal Header -->
        <div class="chat-modal-header">
            <h5 class="chat-modal-title">Chat dengan Kami</h5>
            <button type="button" class="chat-modal-close" onclick="closeChatModal()">✕</button>
        </div>

        <!-- Messages Area -->
        <div id="messagesArea" class="chat-messages-area">
            <!-- Greeting Message -->
            <div class="message bot-message">
                <div class="message-bubble">
                    Halo! 👋 Saya Bot Rental AC. Ada yang bisa saya bantu? Silakan pilih salah satu pertanyaan di bawah ini! 😊
                </div>
                <div class="message-time">Sekarang</div>
            </div>
        </div>

        <!-- Quick Questions Buttons -->
        <div class="chat-quick-buttons">
            <button class="quick-btn" onclick="sendQuickQuestion(0)">💰 Harga?</button>
            <button class="quick-btn" onclick="sendQuickQuestion(1)">📋 Order?</button>
            <button class="quick-btn" onclick="sendQuickQuestion(2)">💳 Bayar?</button>
            <button class="quick-btn" onclick="sendQuickQuestion(3)">🚚 Ongkir?</button>
            <button class="quick-btn" onclick="sendQuickQuestion(4)">❓ Perbedaan?</button>
            <button class="quick-btn" onclick="sendQuickQuestion(5)">📏 Ukuran?</button>
        </div>

        <!-- Input Area -->
        <div class="chat-input-area">
            <input type="text" id="userInput" class="chat-input" placeholder="Ketik pesanan..." onkeypress="handleKeyPress(event)">
            <button class="chat-send-btn" onclick="sendMessage()">➤</button>
        </div>
    </div>
</div>

<style>
    /* Floating Action Button (FAB) */
    .chat-fab {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
        z-index: 999;
    }

    .chat-fab:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }

    .chat-fab:active {
        transform: scale(0.95);
    }

    /* Chat Modal */
    .chat-modal {
        position: fixed;
        bottom: 100px;
        right: 30px;
        width: 400px;
        height: 600px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 40px rgba(0, 0, 0, 0.16);
        display: none;
        flex-direction: column;
        z-index: 998;
        animation: slideUp 0.3s ease-out;
        overflow: hidden;
    }

    .chat-modal-content {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .chat-modal.active {
        display: flex;
    }

    /* Modal Header */
    .chat-modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 15px 15px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-modal-title {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .chat-modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s;
    }

    .chat-modal-close:hover {
        transform: scale(1.2);
    }

    /* Messages Area */
    .chat-messages-area {
        flex: 1;
        overflow-y: auto;
        padding: 15px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        background: #f5f5f5;
    }

    /* Quick Buttons in Modal */
    .chat-quick-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        padding: 10px;
        background: white;
        border-top: 1px solid #e0e0e0;
    }

    /* Input Area */
    .chat-input-area {
        display: flex;
        gap: 8px;
        padding: 12px;
        background: white;
        border-top: 1px solid #e0e0e0;
    }

    .chat-input {
        flex: 1;
        border: 1px solid #ddd;
        border-radius: 20px;
        padding: 10px 15px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
    }

    .chat-input:focus {
        border-color: #667eea;
    }

    .chat-send-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        cursor: pointer;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .chat-send-btn:hover {
        transform: scale(1.05);
    }

    .chat-send-btn:active {
        transform: scale(0.95);
    }

    /* Chat Message Styles */
    .message {
        display: flex;
        flex-direction: column;
        margin-bottom: 8px;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message.user-message {
        align-items: flex-end;
    }

    .message.bot-message {
        align-items: flex-start;
    }

    .message-bubble {
        max-width: 85%;
        padding: 10px 14px;
        border-radius: 14px;
        word-wrap: break-word;
        line-height: 1.4;
        font-size: 0.9rem;
    }

    .user-message .message-bubble {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom-right-radius: 4px;
    }

    .bot-message .message-bubble {
        background: #e8e8e8;
        color: #333;
        border-bottom-left-radius: 4px;
    }

    .message-bubble strong {
        color: inherit;
        font-weight: 700;
    }

    .message-bubble em {
        color: inherit;
        font-style: italic;
    }

    .message-bubble ul,
    .message-bubble ol {
        margin: 8px 0 0 15px;
        padding: 0;
    }

    .message-bubble li {
        margin: 4px 0;
        list-style-position: inside;
    }

    .message-time {
        font-size: 0.7rem;
        color: #999;
        margin-top: 3px;
        padding: 0 5px;
    }

    .quick-btn {
        background: white;
        border: 2px solid rgba(102, 126, 234, 0.3);
        padding: 8px 10px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #667eea;
    }

    .quick-btn:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    }

    .quick-btn:active {
        transform: translateY(0);
    }

    .chat-messages-area::-webkit-scrollbar {
        width: 6px;
    }

    .chat-messages-area::-webkit-scrollbar-track {
        background: transparent;
    }

    .chat-messages-area::-webkit-scrollbar-thumb {
        background: rgba(102, 126, 234, 0.3);
        border-radius: 3px;
    }

    .chat-messages-area::-webkit-scrollbar-thumb:hover {
        background: rgba(102, 126, 234, 0.5);
    }

    .typing-indicator {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .typing-indicator span {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #999;
        animation: typing 1.4s infinite;
    }

    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {
        0%, 60%, 100% {
            opacity: 0.5;
            transform: translateY(0);
        }
        30% {
            opacity: 1;
            transform: translateY(-10px);
        }
    }

    /* Responsive */
    @media (max-width: 500px) {
        .chat-modal {
            width: calc(100% - 20px);
            height: 70vh;
            bottom: 80px;
            right: 10px;
        }

        .chat-fab {
            width: 50px;
            height: 50px;
            font-size: 24px;
            bottom: 20px;
            right: 20px;
        }
    }
</style>

<script>
    // Open/Close Chat Modal Functions
    function openChatModal() {
        const modal = document.getElementById('chatModal');
        modal.classList.add('active');
        document.getElementById('userInput').focus();
    }

    function closeChatModal() {
        const modal = document.getElementById('chatModal');
        modal.classList.remove('active');
    }

    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('chatModal');
            const fab = document.querySelector('.chat-fab');
            
            if (modal && fab && !modal.contains(event.target) && !fab.contains(event.target)) {
                closeChatModal();
            }
        });
    });

    const faqData = {
        0: {
            question: '💰 Berapa harganya?',
            answer: '<strong>Harga Sewa AC kami:</strong><ul><li><strong>Misty Fan:</strong> Rp 250.000/hari</li><li><strong>AC Standing:</strong> Rp 500.000/hari</li></ul><p><em>Harga sudah termasuk instalasi dan deinstalasi gratis!</em></p>'
        },
        1: {
            question: '📋 Caranya order gimana?',
            answer: '<strong>Gampang kok, ikuti langkah ini:</strong><ol><li>Lihat AC pilihan di halaman utama</li><li>Klik tombol "Sewa Sekarang"</li><li>Isi biodata dan alamat Anda</li><li>Pilih tanggal mulai dan selesai sewa</li><li>Kirim pesanan</li><li>Admin bakal hubungi untuk konfirmasi (biasanya dalam 1x24 jam)</li><li>Setelah disetujui, lakukan pembayaran</li><li>AC dikirim sesuai jadwal yang disepakati</li></ol>'
        },
        2: {
            question: '💳 Metode bayar apa aja?',
            answer: '<strong>Banyak pilihan pembayaran:</strong><ul><li>🏦 Transfer Bank (BCA, Mandiri, BNI)</li><li>💳 Kartu Kredit (Visa, Mastercard)</li><li>📱 E-wallet (GoPay, OVO, Dana)</li><li>🏪 Cicilan di Indomaret/7-Eleven</li><li>💰 Cicilan 0% pake kartu kredit</li></ul><p><em>Admin bakal kasih detail rekening atau link bayar setelah pesanan dikonfirmasi.</em></p>'
        },
        3: {
            question: '🚚 Berapa ongkir?',
            answer: '<strong>Ongkir tergantung lokasi:</strong><ul><li>📍 Area Semarang: <strong>Gratis </strong></li><li>📍 Area jauh/luar kota: <strong>Hubungi admin untuk penawaran</strong></li></ul>'
        },
        4: {
            question: '❓ Perbedaan Misty Fan & AC Standing?',
            answer: '<strong>Perbandingan Misty Fan vs AC Standing:</strong><table style="width:100%; border-collapse: collapse; margin: 10px 0;"><tr style="border-bottom: 1px solid #ddd;"><th style="text-align: left; padding: 8px;"><strong>Misty Fan</strong></th><th style="text-align: left; padding: 8px;"><strong>AC Standing</strong></th></tr><tr style="border-bottom: 1px solid #ddd;"><td style="padding: 8px;">💨 Pendingin ruangan dengan kipas</td><td style="padding: 8px;">❄️ AC penuh dengan kompresor</td></tr><tr style="border-bottom: 1px solid #ddd;"><td style="padding: 8px;">💰 Harga: Rp 250.000/hari</td><td style="padding: 8px;">💰 Harga: Rp 500.000/hari</td></tr><tr style="border-bottom: 1px solid #ddd;"><td style="padding: 8px;">📏 Cocok untuk ruangan kecil</td><td style="padding: 8px;">📏 Cocok untuk ruangan besar</td></tr><tr style="border-bottom: 1px solid #ddd;"><td style="padding: 8px;">⚡ Hemat listrik</td><td style="padding: 8px;">❄️ Sangat dingin & nyaman</td></tr><tr><td style="padding: 8px;">🔧 Mudah dipindahkan</td><td style="padding: 8px;">🏠 Perlu instalasi profesional</td></tr></table>'
        },
        5: {
            question: '📏 Untuk ruangan ukuran berapa?',
            answer: '<strong>Rekomendasi AC berdasarkan ukuran ruangan:</strong><ul><li><strong>Misty Fan:</strong> Cocok untuk ruangan outdoor </li><li><strong>AC Standing:</strong> Cocok untuk ruangan indoor</li><li>💡 Untuk ruangan yang lebih besar, bisa menggunakan <strong>2+ unit AC</strong></li></ul><p><em>Hubungi admin jika tidak yakin ukuran ruangan Anda!</em></p>'
        },
        6: {
            question: '⚡ Butuh daya listrik berapa?',
            answer: '<strong>Kebutuhan daya listrik:</strong><ul><li><strong>Misty Fan:</strong> Sekitar <strong>500 Watt</strong> (bisa dari stop kontak biasa)</li><li><strong>AC Standing:</strong> Sekitar <strong>1.5 - 2 Kilowatt (1500-2000 Watt)</strong></li></ul><p><strong>Tips:</strong></p><ul><li>✓ Pastikan daya listrik rumah Anda cukup</li><li>✓ Untuk AC Standing, sebaiknya pakai stop kontak khusus</li><li>✓ Jangan sambung dengan beban listrik lain yang berat</li><li>✓ Admin siap membantu konsultasi saat pengambilan barang</li></ul>'
        },
        7: {
            question: '🎉 Rekomendasi AC untuk acara?',
            answer: '<strong>Rekomendasi AC untuk berbagai jenis acara:</strong><ul><li>💒 <strong>Wedding/Pernikahan:</strong> Gunakan Misty Fan - menciptakan suasana elegant & hemat biaya</li><li>📊 <strong>Seminar/Pameran:</strong> Gunakan AC Standing - untuk pengunjung banyak & ruangan besar</li></ul><p><strong>Keuntungan menyewa AC untuk acara:</strong></p><ul><li>✓ Hemat biaya dibanding beli</li><li>✓ Instalasi gratis oleh teknisi profesional</li><li>✓ Bisa dicicil tanpa bunga</li><li>✓ Fleksibel - durasi sesuai kebutuhan</li><li>✓ Jaminan AC dalam kondisi baik</li></ul><p><em>Hubungi admin sekarang untuk booking acara spesial Anda! 📞</em></p>'
        }
    };

    function getCurrentTime() {
        const now = new Date();
        return now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }

    function addMessage(text, isUser) {
        const messagesArea = document.getElementById('messagesArea');
        if (!messagesArea) return;

        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isUser ? 'user-message' : 'bot-message'}`;

        const bubble = document.createElement('div');
        bubble.className = 'message-bubble';
        bubble.innerHTML = text;

        const timeDiv = document.createElement('div');
        timeDiv.className = 'message-time';
        timeDiv.textContent = getCurrentTime();

        messageDiv.appendChild(bubble);
        messageDiv.appendChild(timeDiv);
        messagesArea.appendChild(messageDiv);

        // Auto scroll to bottom
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }

    function showTypingIndicator() {
        const messagesArea = document.getElementById('messagesArea');
        if (!messagesArea) return;

        const typingDiv = document.createElement('div');
        typingDiv.className = 'message bot-message';
        typingDiv.innerHTML = '<div class="message-bubble"><div class="typing-indicator"><span></span><span></span><span></span></div></div>';
        typingDiv.id = 'typingIndicator';
        messagesArea.appendChild(typingDiv);
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }

    function removeTypingIndicator() {
        const typingDiv = document.getElementById('typingIndicator');
        if (typingDiv) {
            typingDiv.remove();
        }
    }

    function findAnswer(userInput) {
        const input = userInput.toLowerCase();
        
        // Search keywords in FAQ
        for (let i = 0; i < Object.keys(faqData).length; i++) {
            const faq = faqData[i];
            const keywords = faq.question.toLowerCase();
            
            // Check if the question matches
            if (keywords === input || 
                (input.includes('harga') && keywords.includes('harga')) || 
                (input.includes('order') && keywords.includes('order')) ||
                (input.includes('bayar') && keywords.includes('bayar')) ||
                (input.includes('ongkir') && keywords.includes('ongkir')) ||
                (input.includes('kembalikan') && keywords.includes('kembalikan')) ||
                (input.includes('minimal') && keywords.includes('minimal')) ||
                (input.includes('bedanya') && keywords.includes('bedanya')) ||
                (input.includes('perbedaan') && keywords.includes('bedanya')) ||
                (input.includes('misty') && keywords.includes('misty')) ||
                (input.includes('ruangan') && keywords.includes('ruangan')) ||
                (input.includes('ukuran') && keywords.includes('ukuran')) ||
                (input.includes('listrik') && keywords.includes('listrik')) ||
                (input.includes('daya') && keywords.includes('daya')) ||
                (input.includes('watt') && keywords.includes('listrik')) ||
                (input.includes('acara') && keywords.includes('acara')) ||
                (input.includes('rekomendasi') && keywords.includes('rekomendasi')) ||
                (input.includes('event') && keywords.includes('acara'))) {
                return faq.answer;
            }
        }

        return null;
    }

    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            sendMessage();
        }
    }

    function sendMessage() {
        const userInput = document.getElementById('userInput');
        const message = userInput.value.trim();

        if (!message) return;

        // Add user message
        addMessage(message, true);
        userInput.value = '';
        userInput.focus();

        // Show typing indicator
        showTypingIndicator();

        // Simulate bot response delay
        setTimeout(() => {
            removeTypingIndicator();

            const answer = findAnswer(message);
            if (answer) {
                addMessage(answer, false);
            } else {
                addMessage('Maaf, saya belum bisa menjawab pertanyaan tersebut. 😅 Coba tanya tentang harga, cara order, pembayaran, atau pengiriman. Atau hubungi admin melalui WhatsApp untuk pertanyaan lainnya!', false);
            }
        }, 1500);
    }

    function sendQuickQuestion(index) {
        const faq = faqData[index];
        
        // Show user message
        addMessage(faq.question, true);
        
        // Show typing indicator
        showTypingIndicator();
        
        // Simulate bot response delay
        setTimeout(() => {
            removeTypingIndicator();
            // Langsung tampilkan jawaban berdasarkan index
            addMessage(faq.answer, false);
        }, 1500);
    }
</script>

