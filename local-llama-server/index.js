const express = require('express');
const { createProxyMiddleware } = require('http-proxy-middleware');
const { spawn } = require('child_process');
const cloudflaredPath = require('cloudflared').bin;
const chalk = require('chalk');
const cors = require('cors');

const app = express();
const PORT = 3000;
const OLLAMA_HOST = 'http://127.0.0.1:11434';

console.log(chalk.cyan('🚀 Initializing Local Llama Server...'));

// Enable CORS for all requests
app.use(cors());

// Log requests
app.use((req, res, next) => {
    console.log(chalk.gray(`[${new Date().toLocaleTimeString()}] ${req.method} ${req.url}`));
    next();
});

// 1. Landing Page (To fix the "White Screen" confusion)
app.get('/', (req, res) => {
    res.send(`
        <html>
        <head>
            <title>Llama Server Connected</title>
            <style>
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background: #1a1a1a; color: #fff; text-align: center; padding: 50px; }
                h1 { color: #4CAF50; }
                .box { background: #2d2d2d; padding: 20px; border-radius: 8px; display: inline-block; margin-top: 20px; text-align: left; }
                code { background: #000; padding: 5px 10px; border-radius: 4px; color: #00ff00; }
            </style>
        </head>
        <body>
            <h1>✅ Local Llama Server is Running!</h1>
            <p>Your secure tunnel is active.</p>
            <div class="box">
                <p><strong>Next Step:</strong></p>
                <ol>
                    <li>Copy the URL from your address bar (or terminal).</li>
                    <li>Go to WordPress Admin > AI Chatbot > Settings.</li>
                    <li>Paste it into <strong>Remote URL</strong>.</li>
                </ol>
            </div>
        </body>
        </html>
    `);
});

// 2. Proxy API requests to Ollama
app.use('/api', createProxyMiddleware({
    target: OLLAMA_HOST,
    changeOrigin: true,
    pathRewrite: {
        '^/': '/api/', // Add /api back because Express strips it
    },
    onProxyReq: (proxyReq, req, res) => {
        // Optional: Modify headers if needed
    },
    onError: (err, req, res) => {
        console.error(chalk.red('❌ Proxy Error:'), err.message);
        res.status(500).send('Proxy Error: Could not connect to local Ollama instance.');
    }
}));

// Start Express Server
const server = app.listen(PORT, () => {
    console.log(chalk.green(`✅ Local Proxy Server running on port ${PORT}`));
    startTunnel();
});

function startTunnel() {
    console.log(chalk.cyan('🌐 Opening secure Cloudflare tunnel...'));

    // Tunnel to our Express Proxy (Port 3000), not Ollama directly
    const tunnel = spawn(cloudflaredPath, [
        'tunnel',
        '--url',
        `http://localhost:${PORT}`
    ]);

    tunnel.stderr.on('data', (data) => {
        const output = data.toString();
        const urlMatch = output.match(/https:\/\/[a-zA-Z0-9-]+\.trycloudflare\.com/);

        if (urlMatch) {
            const publicUrl = urlMatch[0];
            console.log(chalk.green('\n🎉 Tunnel Established!'));
            console.log(chalk.white('--------------------------------------------------'));
            console.log(chalk.bold.white('🔗 Public URL: ') + chalk.bold.blue(publicUrl));
            console.log(chalk.white('--------------------------------------------------'));
            console.log(chalk.yellow('📝 Copy this URL into your WordPress Plugin Settings.'));
            console.log(chalk.gray('(Opening this URL in your browser will now show a success page)'));
        }
    });

    tunnel.on('close', (code) => {
        console.log(chalk.red(`Tunnel process exited with code ${code}`));
    });
}
