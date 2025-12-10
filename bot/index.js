import makeWASocket, { useMultiFileAuthState, Browsers } from "@whiskeysockets/baileys";
import axios from "axios";
import qrcode from "qrcode-terminal";

const API_URL = "http://127.0.0.1:8000/api/wa/webhook";

async function startBot() {
    const { state, saveCreds } = await useMultiFileAuthState("./auth");

    const sock = makeWASocket({
        auth: state,
        browser: Browsers.macOS("Chrome"),
        printQRInTerminal: false // sudah deprecated
    });

    sock.ev.on("creds.update", saveCreds);

    // === QR CODE HANDLER ===
    sock.ev.on("connection.update", (update) => {
        const { qr, connection } = update;

        if (qr) {
            console.log("🔐 Scan QR berikut:");
            qrcode.generate(qr, { small: true });
        }

        if (connection === "open") {
            console.log("✅ Bot WhatsApp sudah tersambung!");
        }

        if (connection === "close") {
            console.log("❌ Koneksi terputus, mencoba reconnect...");
            startBot();
        }
    });

    // === MESSAGE HANDLER ===
    sock.ev.on("messages.upsert", async ({ messages }) => {
    const msg = messages[0];

    // Hindari LOOP balasan
    if (msg.key.fromMe) return;

    const sender = msg.key.remoteJid;
    const textMessage = msg.message?.conversation
        || msg.message?.extendedTextMessage?.text
        || "";

    console.log("📩 Pesan masuk:", textMessage);

    if (!textMessage) return;

    try {
        const res = await axios.post("http://localhost:8000/api/wa/webhook", {
            from: sender,
            message: textMessage
        });

        if (res.data.reply) {
            await sock.sendMessage(sender, { text: res.data.reply });
        }
    } catch (err) {
        console.log("Error kirim ke Laravel:", err.message);
    }
});

}

startBot();
