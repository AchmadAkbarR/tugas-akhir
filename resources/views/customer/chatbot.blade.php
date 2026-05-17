@extends('layouts.customer')

@section('title', 'Chatbot - FAQ')

@section('content')
<style>
    .quick-btn {
        background: white;
        border: 2px solid rgba(102, 126, 234, 0.3);
        padding: 12px 16px;
        border-radius: 25px;
        font-weight: 600;
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
</style>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-10 mx-auto text-center">
            <h1 class="mb-2" style="font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                💬 Asisten Bot Rental AC
            </h1>
            <p class="text-muted" style="font-size: 1.1rem;">Tanya apapun tentang rental AC kami - Bot siap membantu! 🤖</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 mx-auto">
            <!-- Quick Questions Buttons -->
            <div style="margin-top: 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                <button class="quick-btn" onclick="sendQuickQuestion(0)">💰 Berapa harganya?</button>
                <button class="quick-btn" onclick="sendQuickQuestion(1)">📋 Cara order gimana?</button>
                <button class="quick-btn" onclick="sendQuickQuestion(2)">💳 Metode bayar apa aja?</button>
                <button class="quick-btn" onclick="sendQuickQuestion(3)">🚚 Berapa ongkir?</button>
                <button class="quick-btn" onclick="sendQuickQuestion(4)">↩️ Cara kembaliin AC?</button>
                <button class="quick-btn" onclick="sendQuickQuestion(5)">⏱️ Minimal sewa hari?</button>
            </div>

            <!-- FAQ Data (Hidden) -->
            <div id="faqData" style="display: none;">
                <div class="faq-item" data-question="💰 Berapa harganya?" data-keywords="harga,berapa,price">
                    <strong>Harga Sewa AC kami:</strong>
                    <ul>
                        <li><strong>Misty Fan:</strong> Rp 250.000/hari</li>
                        <li><strong>AC Standing:</strong> Rp 500.000/hari</li>
                    </ul>
                    <p style="margin-top: 10px;"><em>Harga sudah termasuk instalasi dan deinstalasi gratis!</em></p>
                </div>

                <div class="faq-item" data-question="⏱️ Minimal sewa berapa hari?" data-keywords="minimal,durasi,hari,jam">
                    <strong>Durasi Sewa:</strong>
                    <ul>
                        <li>Minimal: <strong>1 hari</strong> (bisa mulai kapan saja!)</li>
                        <li>Maksimal: <strong>sampai 1 tahun</strong></li>
                        <li>Bisa juga per jam untuk keperluan khusus (hubungi admin aja)</li>
                    </ul>
                </div>

                <div class="faq-item" data-question="📋 Caranya order gimana sih?" data-keywords="order,pesan,pemesanan,cara">
                    <strong>Gampang kok, ikuti langkah ini:</strong>
                    <ol>
                        <li>Lihat AC pilihan di halaman utama</li>
                        <li>Klik tombol "Sewa Sekarang"</li>
                        <li>Isi biodata dan alamat Anda</li>
                        <li>Pilih tanggal mulai dan selesai sewa</li>
                        <li>Kirim pesanan</li>
                        <li>Admin bakal hubungi untuk konfirmasi (biasanya dalam 1x24 jam)</li>
                        <li>Setelah disetujui, lakukan pembayaran</li>
                        <li>AC dikirim sesuai jadwal yang disepakati</li>
                    </ol>
                </div>

                <div class="faq-item" data-question="💳 Bayarnya pake apa aja?" data-keywords="pembayaran,bayar,metode,kartu">
                    <strong>Banyak pilihan pembayaran:</strong>
                    <ul>
                        <li>🏦 Transfer Bank (BCA, Mandiri, BNI)</li>
                        <li>💳 Kartu Kredit (Visa, Mastercard)</li>
                        <li>📱 E-wallet (GoPay, OVO, Dana)</li>
                        <li>🏪 Cicilan di Indomaret/7-Eleven</li>
                        <li>💰 Cicilan 0% pake kartu kredit</li>
                    </ul>
                    <p style="margin-top: 10px;"><em>Admin bakal kasih detail rekening atau link bayar setelah pesanan dikonfirmasi.</em></p>
                </div>

                <div class="faq-item" data-question="🚚 Pengiriman bayar berapa?" data-keywords="pengiriman,ongkos,ongkir,biaya">
                    <strong>Ongkir tergantung lokasi:</strong>
                    <ul>
                        <li>📍 Area pusat kota: <strong>Gratis atau Rp 50.000</strong></li>
                        <li>📍 Area pinggiran: <strong>Rp 100.000 - Rp 200.000</strong></li>
                        <li>📍 Area jauh/luar kota: <strong>Hubungi admin untuk penawaran</strong></li>
                        <li>⚡ Pengiriman biasanya 1-2 hari kerja</li>
                    </ul>
                    <p style="margin-top: 10px;"><em>Ongkir udah include instalasi gratis juga!</em></p>
                </div>

                <div class="faq-item" data-question="↩️ Caranya kembaliin AC gimana?" data-keywords="kembalikan,return,pengembalian">
                    <strong>Proses pengembalian gampang:</strong>
                    <ol>
                        <li>Sebelum sewa berakhir, hubungi admin (bisa WA)</li>
                        <li>Teknisi bakal datang untuk pengecekan dan pencabutan AC</li>
                        <li>Kalau ACnya dalam kondisi baik, selesai deh!</li>
                        <li>Kalau ada kerusakan, nanti kita diskusi biaya perbaikannya</li>
                        <li>Teknisi bakal ngambil barang yang disewakan</li>
                    </ol>
                </div>

                <div class="faq-item" data-question="✅ AC nya bergaransi gak?" data-keywords="garansi,jamin,warranty,tanggung">
                    <strong>Tenang, semua AC kami bergaransi:</strong>
                    <ul>
                        <li>✓ Semua AC dijamin berfungsi dengan baik</li>
                        <li>✓ Kami perawat rutin sebelum disewa</li>
                        <li>✓ Kalau AC rusak/bermasalah, kami perbaiki atau ganti gratis</li>
                        <li>✓ Garansi berlaku selama periode sewa</li>
                        <li>✗ Kecuali kerusakan karena kelalaian atau penggunaan salah</li>
                    </ul>
                </div>

                <div class="faq-item" data-question="📄 Ada surat kontraknya gak?" data-keywords="kontrak,surat,dokumen,perjanjian">
                    <strong>Iya, untuk keamanan kedua belah pihak:</strong>
                    <ul>
                        <li>📋 Surat Perjanjian Sewa (buat perlindungan)</li>
                        <li>📋 Daftar barang yang disewa + kondisinya</li>
                        <li>📋 Detail harga dan durasi sewa</li>
                        <li>📋 Cara dan tempat pengembalian</li>
                        <li>📋 Kebijakan jika AC rusak</li>
                    </ul>
                    <p style="margin-top: 10px;"><em>Semua dokumen akan disiapkan oleh admin, Anda tinggal tanda tangan aja.</em></p>
                </div>

                <div class="faq-item" data-question="❄️ AC-nya nyamankah? Dinginnya cukup gak?" data-keywords="nyaman,dingin,kualitas,bagus">
                    <strong>Pastinya enak dan dingin banget!</strong>
                    <ul>
                        <li>❄️ Kondisi bagus, baru di-service</li>
                        <li>❄️ Sudah teruji pendinginnya maksimal</li>
                        <li>❄️ Hemat listrik dengan teknologi terbaru</li>
                        <li>❄️ Bising rendah, nyaman untuk tidur/bekerja</li>
                    </ul>
                </div>

                <div class="faq-item" data-question="⚠️ Kalau AC rusak gimana? Bayar berapa?" data-keywords="rusak,kerusakan,perbaikan,ganti">
                    <strong>Tenang, kami adil dalam hal kerusakan:</strong>
                    <ul>
                        <li>🔧 Kerusakan karena pemakaian normal: <strong>Kami tanggungin</strong></li>
                        <li>🔧 Kerusakan karena kelalaian: <strong>Pembayaran sebagian</strong> (tergantung keparahan)</li>
                        <li>🔧 Rusak total karena kesalahan Anda: <strong>Ganti rugi penuh</strong></li>
                    </ul>
                    <p style="margin-top: 10px;"><em>Sebelum pengiriman, kami akan dokumentasi kondisi AC. Saat pengembalian juga sama. Jadi transparan!</em></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="row mt-4">
        <div class="col-md-10 mx-auto">
            <div class="card bg-light" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%) !important; border: 2px solid rgba(102, 126, 234, 0.2);">
                <div class="card-body text-center">
                    <h5 class="card-title mb-3 fw-bold">Masih ada pertanyaan yang lain?</h5>
                    <p class="card-text mb-4">Hubungi kami langsung melalui WhatsApp untuk pertanyaan yang lebih detail atau kebutuhan khusus!</p>
                    <a href="https://wa.me/" target="_blank" class="btn btn-success btn-lg">
                        <i class="fab fa-whatsapp"></i> Chat WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Original Accordion (Kept but hidden for reference) -->
<div style="display: none;">
    <div class="accordion mb-4" id="faqAccordion">
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
        padding: 12px 16px;
        border-radius: 25px;
        font-weight: 600;
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

    .faq-item {
        display: none;
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
            
            if (!modal.contains(event.target) && !fab.contains(event.target)) {
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
            answer: '<strong>Ongkir tergantung lokasi:</strong><ul><li>📍 Area pusat kota: <strong>Gratis atau Rp 50.000</strong></li><li>📍 Area pinggiran: <strong>Rp 100.000 - Rp 200.000</strong></li><li>📍 Area jauh/luar kota: <strong>Hubungi admin untuk penawaran</strong></li><li>⚡ Pengiriman biasanya 1-2 hari kerja</li></ul><p><em>Ongkir udah include instalasi gratis juga!</em></p>'
        },
        4: {
            question: '↩️ Cara kembaliin AC?',
            answer: '<strong>Proses pengembalian gampang:</strong><ol><li>Sebelum sewa berakhir, hubungi admin (bisa WA)</li><li>Teknisi bakal datang untuk pengecekan dan pencabutan AC</li><li>Kalau ACnya dalam kondisi baik, selesai deh!</li><li>Kalau ada kerusakan, nanti kita diskusi biaya perbaikannya</li><li>Teknisi bakal ngambil barang yang disewakan</li></ol>'
        },
        5: {
            question: '⏱️ Minimal sewa berapa hari?',
            answer: '<strong>Durasi Sewa:</strong><ul><li>Minimal: <strong>1 hari</strong> (bisa mulai kapan saja!)</li><li>Maksimal: <strong>sampai 1 tahun</strong></li><li>Bisa juga per jam untuk keperluan khusus (hubungi admin aja)</li></ul>'
        }
    };

    function getCurrentTime() {
        const now = new Date();
        return now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }

    function addMessage(text, isUser) {
        const messagesArea = document.getElementById('messagesArea');
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
                (input.includes('minimal') && keywords.includes('minimal'))) {
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

@endsection
