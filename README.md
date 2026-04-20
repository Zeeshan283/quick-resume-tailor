<div align="center">

<br/>

<img src="https://img.shields.io/badge/version-1.0.0-blue?style=for-the-badge&logoColor=white" alt="Version"/>
<img src="https://img.shields.io/badge/license-MIT-green?style=for-the-badge" alt="License"/>
<img src="https://img.shields.io/badge/Chrome-Extension-yellow?style=for-the-badge&logo=googlechrome&logoColor=white" alt="Chrome Extension"/>
<img src="https://img.shields.io/badge/Laravel-11-red?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel"/>
<img src="https://img.shields.io/badge/BYOK-Enabled-purple?style=for-the-badge" alt="BYOK"/>

<br/><br/>

# ⚡ Quick Resume Tailor

### Stop applying blindly. Start getting interviews.

**Quick Resume Tailor** is an AI-powered Chrome Extension that instantly tailors your resume against any job description. Built with a privacy-first, **Bring Your Own Key (BYOK)** architecture — you own the AI, you control the cost.

<br/>

[**Download Extension →**](./Quick-Resume-Tailor.zip) &nbsp;&nbsp;|&nbsp;&nbsp; [**Live Demo →**](https://dark-wind-4f93.quickresumetailor.workers.dev/)

<br/>

</div>

---

## 🎯 What It Does

Most resumes never get seen by a human recruiter. Automated systems (ATS) filter them out before a single human eye reads them. **Quick Resume Tailor** fixes that — automatically.

It reads the job description, analyzes your resume, and produces a rewritten version that is laser-targeted to that specific role.

<br/>

## ✨ Key Features

| Feature | Description |
|---|---|
| 🔑 **BYOK Architecture** | Use your own AI key (Grok, OpenAI, Groq, etc.). Zero subscription costs. |
| 🔒 **100% Stateless** | We never store your personal data. Processed in-memory, never on disk. |
| 🛡️ **Proxy Masked Backend** | Your traffic routes through Cloudflare — backend identity fully protected. |
| 📄 **Deep PDF Parsing** | High-accuracy extraction of your resume's full content structure. |
| 🔗 **LinkedIn Integration** | Detects job descriptions on LinkedIn pages automatically. |
| 💡 **Any AI Model** | Compatible with any OpenAI-style API endpoint. |

<br/>

## 🚀 Quick Start (Non-Coders)

**No technical experience required.**

1. **[Download the Zip](./Quick-Resume-Tailor.zip)** — Click the link and save the file.
2. **Extract** — Right-click the file and select "Extract All".
3. **Open Chrome Extensions** — Go to `chrome://extensions/` in your browser.
4. **Enable Developer Mode** — Toggle the switch in the top-right corner.
5. **Load the Extension** — Click "Load Unpacked" and select the extracted folder.
6. **Configure** — Open the extension, go to Settings, and enter your AI API Key.

> [!TIP]
> You can even use any free api and there model.

<br/>

## 🛠️ Developer Setup

```bash
# 1. Clone the repository
git clone https://github.com/Zeeshan283/quick-resume-tailor.git
cd quick-resume-tailor

# 2. Backend setup
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan serve

# 3. Extension setup
cd ../extension
npm install
npm run build
# Load the `extension/dist` folder into chrome://extensions
```

<br/>

## 🏗️ Architecture

```
User (Chrome)
    │
    ▼
Cloudflare Worker Proxy  ← Masks the backend domain
    │
    ▼
Laravel API (Hostinger)  ← Stateless, header-driven
    │
    ▼
Your AI Provider (BYOK)  ← Your key, your cost
```

<br/>

## 🤝 Need Professional Help?

If you want a **human expert** to review your resume, write your career story, or help you break into a new field — we offer premium career services.

- **Resume Rewrite** — We don't just edit. We rebuild your narrative from scratch.
- **ATS Optimization** — Guaranteed to pass keyword filters for your target roles.
- **1-on-1 Career Consultation** — Personalized strategy for your next big move.


<br/>

## 📄 License

This project is licensed under the **MIT License** — you are free to use, modify, and distribute it.

<br/>

---

<div align="center">

**Built with precision by [Zeeshan](https://github.com/Zeeshan283)**

*If this project helped you, consider starring ⭐ the repo.*

</div>
