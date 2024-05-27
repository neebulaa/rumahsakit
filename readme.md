# EHealth - Simple hospital back office

EHealth is a simple hospital management app that allows users to manage doctor, patient, medicine, clinic, and medical record data. Key features include email verification with OTP after login and the ability to perform multiple operations (create, update, delete) in one go.

## 💻 Tech Stacks

Here are the technology used to create this project

1. PHP Native
2. PHPMailer Library

## 📕 Features

1. Login Register + sendmail (OTP Email Verification)
2. CRUD (doctos, medicines, patients, clinics, medicalrecords)
3. Live Searching (Ajax)
4. Multiple add, edit, and delete

## 🔐 Setup

1. Clone the project and place it on `htdocs` or other directory in the web server (Apache used)
2. Open `config.php` file
3. Change `$base_url` path to `http://localhost/your_folder_path_to_the_project/ehealth`
4. Open `.htaccess` file
5. Change the not found access to `ErrorDocument 404 http://localhost/your_folder_path_to_the_project/ehealth/php/404.php`
6. Setting up SMTP for sending OTP email verification by opening `your_xampp/php/php.ini`
7. Search for `smtp` variable under `[mail function]`. By default `smtp=localhost` change it to `smtp=smtp.gmail.com`
8. Search for `smtp_port` variable under `[mail function]`. By default `smtp_port=25` change it to `smtp_port=587` and save
9. Setting up two-step verification on your Gmail account used for the project (Google account - myaccount.google.com)
10. Generate an app password (Google account - myaccount.google.com)
11. Setting up SMTP Authentication by opening `your_xampp/sendmail/sendmail.ini`
12. Search for `smtp_port` variable under `[sendmail]`. By default `smtp_port=25` change it to `smtp_port=587`
13. Search for `auth_username` and `auth_password` variable under `[sendmail]`
14. Modify to `auth_username=youremail@gmail.com` and `auth_password=your_app_password` and save
15. Back to the project, open `config.php` file
16. Change `$sendmail_auth_username="youremail@gmail.com"` and `sendmail_auth_password="your_app_password"`
17. Import database `rumahsakit.sql` and dont change the database name
18. Open the local server project and enjoy 😊
