# Local Llama Server

This utility creates a secure **Cloudflare Tunnel** from your local computer to the internet, allowing your hosted WordPress AI Chatbot Plugin to communicate with your default local Ollama instance (port 11434).

## Prerequisites

1.  **Node.js**: Installed on your computer.
2.  **Ollama**: Installed and running (`ollama serve`).

## Setup

1.  Open this folder in your terminal.
2.  Install dependencies:
    ```bash
    npm install
    ```

## Usage

1.  Start the server:
    ```bash
    npm start
    ```
2.  Copy the **Public URL** (ending in `trycloudflare.com`).
3.  Go to your WordPress Admin Dashboard -> **AI Chatbot** -> **Settings**.
4.  Select **Remote Ollama**.
5.  Paste the URL into the **Remote URL** field.
6.  Save Settings.

**Note:** The URL changes every time you restart the script.
