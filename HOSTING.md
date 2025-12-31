# WordPress Llama Chatbot Plugin - Hosting Setup Guide

## Overview

The AI Chatbot with Llama plugin now supports multiple deployment options, making it compatible with any hosting environment - from shared hosting to dedicated servers.

## Provider Options

### 1. Local Ollama (VPS/Dedicated Servers)

**Best for:** Users with root access to their server

**Pros:**
- ✅ Free (no API costs)
- ✅ Full control over models
- ✅ No data sent to third parties
- ✅ Unlimited usage

**Cons:**
- ❌ Requires server setup
- ❌ Needs adequate RAM (8GB+ recommended)
- ❌ Not available on shared hosting

**Setup:**
1. Install Ollama on your server:
   ```bash
   curl https://ollama.ai/install.sh | sh
   ```

2. Pull a model:
   ```bash
   ollama pull llama2
   ```

3. Start Ollama:
   ```bash
   ollama serve
   ```

4. In WordPress admin:
   - Select "Local Ollama" as provider
   - API Endpoint: `http://localhost:11434/api/chat`
   - Model: `llama2`
   - Test connection

---

### 2. Remote Ollama (Any Hosting)

**Best for:** Users who want to run Ollama on a separate server

**Pros:**
- ✅ Works on shared hosting
- ✅ Free (no API costs)
- ✅ Full control over models
- ✅ Scalable (dedicated AI server)

**Cons:**
- ❌ Requires separate server for Ollama
- ❌ Network latency
- ❌ Need to manage firewall/security

**Setup:**

#### On Your Ollama Server:

1. Install Ollama:
   ```bash
   curl https://ollama.ai/install.sh | sh
   ```

2. Pull models:
   ```bash
   ollama pull llama2
   ```

3. Start Ollama with network binding:
   ```bash
   OLLAMA_HOST=0.0.0.0:11434 ollama serve
   ```

4. Configure firewall to allow port 11434:
   ```bash
   # UFW example
   sudo ufw allow 11434/tcp
   ```

5. (Optional) Set up HTTPS with nginx reverse proxy

#### In WordPress Admin:

1. Select "Remote Ollama" as provider
2. Remote URL: `https://your-ollama-server.com:11434`
3. Model: `llama2`
4. Test connection

**Security Note:** Always use HTTPS for remote connections and consider IP whitelisting.

---

### 3. OpenAI (Easiest for Shared Hosting)

**Best for:** Shared hosting users who want zero setup

**Pros:**
- ✅ Works on ANY hosting
- ✅ No server setup required
- ✅ Professional-grade AI (GPT-4)
- ✅ Instant setup
- ✅ Always available

**Cons:**
- ❌ Costs money (pay-per-use)
- ❌ Data sent to OpenAI
- ❌ Requires internet connection

**Setup:**

1. Get an OpenAI API key:
   - Go to [platform.openai.com/api-keys](https://platform.openai.com/api-keys)
   - Create account / Sign in
   - Click "Create new secret key"
   - Copy the key (starts with `sk-...`)

2. Add payment method:
   - Go to [platform.openai.com/account/billing](https://platform.openai.com/account/billing)
   - Add credit card
   - Set usage limits (recommended)

3. In WordPress admin:
   - Select "OpenAI" as provider
   - Paste your API key
   - Choose model (GPT-3.5 Turbo recommended for cost)
   - Test connection

**Cost Estimation:**
- GPT-3.5 Turbo: ~$0.002 per 1,000 tokens (~750 words)
- GPT-4: ~$0.03 per 1,000 tokens
- Average chat: 200-500 tokens
- 1,000 chats/month with GPT-3.5: ~$2-5

---

## Hosting Provider Compatibility

### ✅ Fully Compatible

| Hosting Type | Local Ollama | Remote Ollama | OpenAI |
|--------------|--------------|---------------|--------|
| **Shared Hosting** (Bluehost, HostGator, etc.) | ❌ | ✅ | ✅ |
| **Managed WordPress** (WP Engine, Kinsta) | ❌ | ✅ | ✅ |
| **VPS** (DigitalOcean, Linode) | ✅ | ✅ | ✅ |
| **Dedicated Server** | ✅ | ✅ | ✅ |
| **Cloud** (AWS, Google Cloud) | ✅ | ✅ | ✅ |

---

## Recommended Setups

### For Beginners
**Use OpenAI**
- Easiest setup
- No technical knowledge required
- Works immediately

### For Budget-Conscious Users
**Use Remote Ollama**
- Get a cheap VPS ($5/month)
- Install Ollama there
- Connect from WordPress

### For Privacy-Focused Users
**Use Local or Remote Ollama**
- Your data never leaves your servers
- Full control

### For High-Traffic Sites
**Use OpenAI or Dedicated Ollama Server**
- OpenAI scales automatically
- Or use powerful dedicated server for Ollama

---

## Remote Ollama Deployment Examples

### DigitalOcean Droplet

1. Create droplet (Ubuntu 22.04, 2GB RAM minimum)
2. SSH into droplet
3. Install Ollama:
   ```bash
   curl https://ollama.ai/install.sh | sh
   ```
4. Pull model:
   ```bash
   ollama pull llama2:7b
   ```
5. Create systemd service:
   ```bash
   sudo nano /etc/systemd/system/ollama.service
   ```
   
   ```ini
   [Unit]
   Description=Ollama Service
   After=network.target

   [Service]
   Type=simple
   User=root
   Environment="OLLAMA_HOST=0.0.0.0:11434"
   ExecStart=/usr/local/bin/ollama serve
   Restart=always

   [Install]
   WantedBy=multi-user.target
   ```

6. Start service:
   ```bash
   sudo systemctl enable ollama
   sudo systemctl start ollama
   ```

7. Configure firewall:
   ```bash
   sudo ufw allow 11434/tcp
   sudo ufw enable
   ```

8. In WordPress: Use `http://YOUR_DROPLET_IP:11434`

### Docker Deployment

```yaml
version: '3.8'
services:
  ollama:
    image: ollama/ollama:latest
    ports:
      - "11434:11434"
    volumes:
      - ollama_data:/root/.ollama
    restart: unless-stopped

volumes:
  ollama_data:
```

Run:
```bash
docker-compose up -d
docker exec -it ollama_ollama_1 ollama pull llama2
```

---

## Security Best Practices

### For Remote Ollama

1. **Use HTTPS:** Set up nginx reverse proxy with SSL
2. **Firewall:** Only allow your WordPress server IP
3. **Authentication:** Consider adding basic auth
4. **VPN:** Use VPN for extra security

### For OpenAI

1. **Secure API Key:** Never commit to version control
2. **Set Usage Limits:** Prevent unexpected bills
3. **Monitor Usage:** Check OpenAI dashboard regularly
4. **Rotate Keys:** Change API keys periodically

---

## Troubleshooting

### Remote Ollama Connection Failed

**Check firewall:**
```bash
sudo ufw status
```

**Test from WordPress server:**
```bash
curl http://your-ollama-server:11434/api/tags
```

**Check Ollama logs:**
```bash
sudo journalctl -u ollama -f
```

### OpenAI Authentication Error

- Verify API key is correct
- Check if billing is set up
- Ensure usage limits aren't exceeded
- Try generating new API key

### Slow Responses

**For Ollama:**
- Use smaller model (7B instead of 13B/70B)
- Upgrade server RAM
- Use SSD storage

**For OpenAI:**
- Check internet connection
- Try different model
- Contact OpenAI support

---

## Cost Comparison

### Monthly Cost Estimates (1,000 conversations)

| Provider | Setup | Monthly Cost | Notes |
|----------|-------|--------------|-------|
| **Local Ollama** | Server costs | $0 | Included in hosting |
| **Remote Ollama (VPS)** | $5-20 | $5-20 | VPS hosting fee |
| **OpenAI GPT-3.5** | $0 | $2-5 | Pay-per-use |
| **OpenAI GPT-4** | $0 | $30-75 | Pay-per-use |

---

## Migration Guide

### Switching Providers

1. Go to AI Chatbot → Settings
2. Select new provider
3. Configure settings
4. Test connection
5. Save changes

**Note:** Conversation history is preserved when switching providers.

---

## Support

For hosting-specific questions:
- **Ollama:** [github.com/ollama/ollama/issues](https://github.com/ollama/ollama/issues)
- **OpenAI:** [platform.openai.com/docs](https://platform.openai.com/docs)
- **Plugin:** Check plugin documentation

---

## Next Steps

1. Choose your provider based on your needs
2. Follow the setup guide above
3. Test the connection
4. Customize the system prompt
5. Enable the chatbot
6. Monitor usage and costs
