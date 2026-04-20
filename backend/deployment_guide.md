# Deployment Guide: Quick Resume Tailor

This document provides step-by-step instructions to move your application from your local machine to a production server and connect your Chrome Extension to it.

## Part 1: Backend Deployment (Laravel)

### 1. Server Requirements
- PHP 8.1 or higher
- Composer
- SQLite (default) or MySQL
- Nginx or Apache
- SSL Certificate (Required for Chrome Extensions to communicate with a server)

### 2. Initial Setup on Server
1. **Upload Code**: Clone your repository or upload your `backend/` folder to the server.
2. **Install Dependencies**:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```
3. **Setup Environment**:
   - Copy `.env.example` to `.env`.
   - Run `php artisan key:generate`.
   - Set `APP_DEBUG=false` and `APP_ENV=production`.
   - Ensure `AI_API_KEY`, `AI_BASE_URL`, and `AI_MODEL` are empty (as you are using BYOK).

4. **Database & Storage**:
   ```bash
   touch database/database.sqlite
   php artisan migrate --force
   php artisan storage:link
   ```

5. **Permissions**:
   ```bash
   sudo chown -R www-data:www-data storage bootstrap/cache
   sudo chmod -R 775 storage bootstrap/cache
   ```

### 3. CORS Configuration (Important)
Ensure your `config/cors.php` or `HandleCors` middleware allows the extension to talk to it. Since Chrome Extensions have an origin like `chrome-extension://<id>`, it's best to allow your server's domain:
```php
'allowed_origins' => ['*'], // Or specific chrome-extension ID
```

---

## Part 2: Extension Configuration

### 1. Update API Endpoint
Open `extension/src/App.tsx` and find the `API_BASE` variable. Change it to your live server URL:
```typescript
// From:
const API_BASE = "http://localhost:8000/api";

// To:
const API_BASE = "https://your-production-domain.com/api";
```

### 2. Update Manifest Permissions
Open `extension/public/manifest.json`. Ensure your production domain is allowed in `host_permissions`:
```json
"host_permissions": [
  "https://*.linkedin.com/*",
  "https://your-production-domain.com/*"
]
```

### 3. Build the Production Bundle
Inside the `extension/` folder, run:
```bash
npm run build
```
This will create a `dist/` folder. This is the folder you will upload to the Chrome Web Store or share with users.

---

## Part 3: Publishing to Users

### For Private Testing
1. Zip the `dist/` folder created in the previous step.
2. Send the ZIP to your friends/users.
3. They can go to `chrome://extensions`, enable **Developer Mode**, and click **Load Unpacked** (after unzipping).

### For Public Release (Chrome Web Store)
1. Go to the [Chrome Web Store Developer Dashboard](https://chrome.google.com/webstore/devconsole).
2. Create a new item.
3. Upload the `dist/` folder ZIP.
4. Fill in the descriptions and screenshots.
5. Submit for review!

---

> [!IMPORTANT]
> **SSL is mandatory (HTTPS)**. Chrome will block any extension trying to talk to an `http://` (insecure) server. Ensure your backend has a valid SSL certificate (via Let's Encrypt or similar).

---

## Part 4: Hostinger Shared Hosting (Specific Steps)

Hostinger Shared Hosting is a bit unique. Here is how to handle it:

### 1. Folder Structure
Do NOT upload your code directly into `public_html`. Instead:
1. Upload the entire `backend/` folder to your root directory (the level ABOVE `public_html`).
2. In the Hostinger Control Panel, go to **Domains** -> **Websites** -> **Change Document Root**.
3. Point your domain to `/backend/public`. This ensures your core code is safe and not public.

### 2. Using SSH (Recommended)
1. In Hostinger Panel, go to **Advanced** -> **SSH Access** and click **Enable**.
2. Connect to your server using Terminal.
3. Navigate to your `backend` folder and run your artisan commands there.

### 3. Database (MySQL vs SQLite)
Hostinger works best with MySQL. 
1. Create a **MySQL Database** in the Hostinger panel.
2. Put these into your `.env` file on the server:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_db_name
   DB_USERNAME=your_db_user
   DB_PASSWORD=your_password
   ```

### 4. Fix for Storage Link
If you cannot run `php artisan storage:link` via SSH, you can add this temporary route to your `routes/web.php` and visit it once in your browser to create the link:
```php
Route::get('/setup-storage', function () {
    Artisan::call('storage:link');
    return 'Storage link created!';
});
```
