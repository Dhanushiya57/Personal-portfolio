Deploying to a shared PHP host (cPanel) — step-by-step

This guide prepares your portfolio for a public PHP+MySQL host and explains how to upload and configure the site.

1) Choose a host
- Use any host that supports PHP 7.4+ and MySQL. Popular choices: Hostinger, Namecheap, A2 Hosting, SiteGround, or free options like InfinityFree.

2) Prepare your files (already done)
- I turned off `display_errors` in `config.php` for production.
- I created a zip of your project at `C:\xampp\htdocs\personal_portfolio_deploy.zip` (if you followed the automated zip step).

3) Create the MySQL database in cPanel
- Login to your hosting cPanel.
- Open "MySQL Databases".
- Create a new database (e.g., `portfolio_db`).
- Create a database user and a strong password.
- Add the user to the database and grant ALL PRIVILEGES.

4) Import `database.sql`
- Open phpMyAdmin in cPanel.
- Select the new database.
- Use the "Import" tab and upload `database.sql` from your local project.

5) Upload site files
Option A — File Manager
- In cPanel go to File Manager → public_html (or your site's document root).
- Upload `personal_portfolio_deploy.zip` and extract it.
- If files extracted into a subfolder, move them so the site root contains `index.html`, `config.php`, `css/`, `js/`, etc.

Option B — FTP (FileZilla)
- Connect to your host using FTP/SFTP credentials.
- Upload entire project folder to `public_html/`.

6) Update `config.php`
- Edit `config.php` on the server (use File Manager Edit or download/edit/upload):
  - `DB_HOST` → provided by your host (usually `localhost`)
  - `DB_USER` → the db user you created
  - `DB_PASS` → the db user's password
  - `DB_NAME` → the database name you created
  - `ADMIN_EMAIL` → set to your email
  - `SITE_URL` → `https://yourdomain.com/` (set your domain)

7) Post-deploy actions
- Remove or secure one-time scripts: delete `setup_database.php` from the live server.
- Check file permissions: PHP files `644`, folders `755`.
- Test the site in a browser. Visit `https://yourdomain.com/`.

8) Troubleshooting
- 500 errors: check `error_log` in cPanel or temporarily enable `display_errors` (not recommended on production).
- Contact form not working: confirm `contact_handler.php` exists and `config.php` DB settings are correct; check mail settings or SMTP.

9) Optional: Use SMTP for contact emails
- Many hosts require SMTP. If you need, I can add SMTP (PHPMailer) integration in `contact_handler.php`.

If you want, I can now:
- Upload the zip for you (provide FTP/cPanel credentials), or
- Walk you through the FileZilla or cPanel steps while you perform them.
