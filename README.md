Finance Tracker

Finance Tracker is a personal finance management application that helps users track their income, expenses, and budgets. It features a responsive Vue.js frontend with a clean dashboard and a Laravel backend with a RESTful API for secure data management. Whether you're on desktop or mobile, this app makes financial oversight simple and intuitive.
Table of Contents

    Features (#features)
    Tech Stack (#tech-stack)
    Prerequisites (#prerequisites)
    Installation (#installation)
    Usage (#usage)
    API Endpoints (#api-endpoints)
    Screenshots (#screenshots)
    Contributing (#contributing)
    License (#license)
    Contact (#contact)

Features

    User Authentication: Secure login, registration, and logout using Laravel Sanctum.
    Transaction Management: Add, edit, and delete income and expense transactions.
    Budget Planning: Set monthly budgets by category and track spending.
    Dashboard: Visualize spending by category with a pie chart (via vue-chartjs).
    Responsive Design: Works seamlessly on desktop and mobile devices with Bootstrap.
    Real-Time Updates: Live reload during development with Vite.

Tech Stack

    Frontend: Vue.js 3, Vite, Bootstrap 5, Vue Router, Axios, vue-chartjs
    Backend: Laravel 11, Sanctum (token-based auth), MySQL
    Tools: XAMPP (local server), Git (version control)

Prerequisites

    Node.js: v18+ (for Vue frontend)
    PHP: 8.1+ (for Laravel backend)
    Composer: Latest version
    MySQL: 5.7+ (or compatible)
    XAMPP: For local Apache/MySQL setup
    Git: For cloning and version control

Installation
Backend (Laravel)

    Clone the Repository:
    bash

    git clone git@github.com:samthatcode/finance_tracker.git
    cd finance_tracker/server

    Install Dependencies:
    bash

    composer install

    Configure Environment:
        Copy .env.example to .env:
        bash

        cp .env.example .env

        Edit .env:

        APP_URL=http://server_finance_tracker.test
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=finance_tracker_db
        DB_USERNAME=root
        DB_PASSWORD=

    Generate App Key:
    bash

    php artisan key:generate

    Run Migrations:
    bash

    php artisan migrate

    Set Up Virtual Host (XAMPP):
        Edit /Applications/XAMPP/xamppfiles/etc/httpd.conf and enable vhosts.
        Edit /Applications/XAMPP/xamppfiles/etc/extra/httpd-vhosts.conf:

        <VirtualHost *:80>
            ServerName server_finance_tracker.test
            DocumentRoot "/Applications/XAMPP/xamppfiles/htdocs/finance_tracker/server/public"
            <Directory "/Applications/XAMPP/xamppfiles/htdocs/finance_tracker/server/public">
                AllowOverride All
                Require all granted
            </Directory>
        </VirtualHost>

        Edit /etc/hosts:

        127.0.0.1 server_finance_tracker.test

        Restart Apache:
        bash

        sudo /Applications/XAMPP/xamppfiles/bin/apachectl restart

Frontend (Vue)

    Navigate to Client Directory:
    bash

    cd ../client

    Install Dependencies:
    bash

    npm install

    Configure Vite:
        Edit vite.config.js (ensure proxy matches backend):
        javascript

        server: {
            host: 'client_finance_tracker.test',
            port: 5173,
            proxy: {
                '/api': {
                    target: 'http://server_finance_tracker.test',
                    changeOrigin: true,
                    rewrite: (path) => path.replace(/^\/api/, '/api')
                }
            }
        }

    Set Up Virtual Host (Development):
        Edit /etc/hosts:

        127.0.0.1 client_finance_tracker.test

    Run Development Server:
    bash

    npm run dev

        Access at http://client_finance_tracker.test:5173.

Production Build (Optional)

    Build frontend:
    bash

    npm run build

    Copy dist to server/public and serve via server_finance_tracker.test.

Usage

    Register: Go to http://client_finance_tracker.test:5173/register to create an account.
    Login: Use /login to authenticate and receive a token.
    Dashboard: After logging in, navigate to /dashboard to:
        Add transactions (income/expense).
        Set budgets by category.
        View spending charts and summaries.
    Logout: Click “Logout” in the navbar to end your session.

API Endpoints
Method
	
Endpoint
	
Description
	
Auth Required
POST
	
/api/register_user
	
Register a new user
	
No
POST
	
/api/login_user
	
Log in and get token
	
No
POST
	
/api/logout_user
	
Log out (revoke token)
	
Yes
GET
	
/api/get_user_transactions
	
Fetch user transactions
	
Yes
GET
	
/api/get_user_budgets
	
Fetch user budgets
	
Yes

    Base URL: http://server_finance_tracker.test/api
    Auth: Use Authorization: Bearer <token> for protected routes.

Screenshots
(Add these later by uploading images to your repo and linking them here)  

    Dashboard: Dashboard  
    Mobile View: Mobile

Contributing

    Fork the repository.
    Create a feature branch (git checkout -b feature/xyz).
    Commit changes (git commit -m "Add xyz feature").
    Push to your branch (git push origin feature/xyz).
    Open a Pull Request.

License
This project is licensed under the MIT License - see the LICENSE file for details.
Contact

    Author: Version Control
    GitHub: samthatcode
    Email: version.control.dev@gmail.com.com (mailto:version.control.dev@gmail.com.com)

Step 8: Create the File

    In Your Project:
    bash

    cd /Applications/XAMPP/xamppfiles/htdocs/finance_tracker/client
    touch README.md

    Copy-Paste: Open README.md in an editor (e.g., nano README.md) and paste the content above.
    Commit and Push:
    bash

    git add README.md
    git commit -m "Add README for Finance Tracker"
    git push origin main
