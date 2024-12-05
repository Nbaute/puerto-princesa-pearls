# Puerto Princesa Pearls

## Information

### Terms

-   Composer - PHP Package manager
-   NPM - JavaScript’s Package manager

### Composer packages:

-   Laravel - full-stack framework (Backend, Frontend)
-   Laravel Sanctum - for bearer authentication (Login, Registration)
-   Laravel Captcha - for authentication security (Registration)
-   Laravel Livewire - for realtime reactivity (Applied everywhere)
-   Laravel Livewire Tables - for datatables (Orders)
-   Laravel Media Library - for media uploading/reading (shops, products, and profiles)
-   Laravel Permission - for role management (Buyer and Seller)

### NPM Packages

-   TailwindCSS - utility-first CSS framework
-   DaisyUI - component library for TailwindCSS

### Server Information

-   Linode - third-party cloud computing platform
-   Region: SG, Singapore (ap-south) (Nearest region to the Philippines)
-   Linux Distribution: AlmaLinux 8
-   Plan: Shared CPU Plan (Specs: Linode 2 GB CPU, 1GB RAM, - 50GB Storage, 12 USD)

## Installation

### Prerequisites

-   PHP 8.3
-   Composer
-   Docker

### Initial Setup

1. Clone the repository:

```bash
git clone YOUR_REMOTE_URL
```

2. Install the composer packages:

```bash
composer install –ignore-platform-reqs
```

3. Setup the .env file:

```
cp .env.example .env
```

(Replace the `APP_NAME`, `APP_URL`, and all `DB_*` fields with the correct values)

4. Run Laravel Sail (Please start Docker before performing this step)

```bash
./vendor/bin/sail up -d
```

5. Migrate and seed:

```bash
php artisan migrate:fresh --seed && php artisan db:seed --class=PearlsSeeder
```

6. Install the NPM packages:

```bash
npm install
```

7. Build the project using Vite:

```bash
npm run build
```

### Debugging & Development

#### Key generation

Run this command if the project requires you to generate a new key:

```bash
php artisan key:generate
```

#### Storage linking

If you're encountering storage/media/image-related issues, run the following:

```bash
chmod -R 0775 . && php artisan storage:link
```

#### Rebuilding the Laravel Sail app

```bash
./vendor/bin/sail down -v
./vendor/bin/sail up -d
```

### Syncing changes from GitHub

1. Go to the project's root folder and run the following:

```bash
git pull
```

#### In case you want the real-time Vite building process (To sync TailwindCSS design every `Ctrl + S`)...

```bash
npm run dev
```

#### In case you need to run the migrations...

```bash
php artisan migrate
```

#### In case you need to run a specific seeder...

```bash
php artisan db:seed --class=INSERT_SEEDER_CLASS_NAME_HERE
```

#### In case you need to re-run all the migrations and seeder...

```bash
php artisan migrate:fresh --seed && php artisan db:seed --class=PearlsSeeder
```

#### In case you need to login via phpMyAdmin...

Check the `.env` file and look for `DB_USERNAME` and `DB_PASSWORD` which will serve as the default username and password when you visit phpMyAdmin

-   Default phpMyAdmin URL: http://YOUR_IP_ADDRESS:9999

## Seeded Login Credentials

```
===

Email: puertoprincesapearls@gmail.com
Password: password
Shops owned: 0

===

Email: oden@gmail.com
Password: password
Shops owned: 1

===
Email: hannies@gmail.com
Password: password
Shops owned: 1

===
Email: chamz@gmail.com
Password: password
Shops owned: 1

===
```

## SSH Login Credentials

To login via CLI, run:

```bash
ssh root@139.162.52.209
```

and enter the SSH password.

## Routes

### /
- The official homepage of the website featuring the shops and products of top leading brands

### /contact-us
- Contact page to allow user to write an email to the website owner
### /register
- Registration page of the website with CAPTCHA authentication

### /login
- Login page of the website

### /profile
- Allows the user to edit their basic details
- Allows the user to update their password

### /shops
- Listing of active shops
### /shops/{username}
- The shop's page featuring their products
### /shops/{username}/products/{productId}
- The product's page

### /my-shops
- Listing of all shops owned by the user
### /my-shops-create
- Allows the user to create a new shop
### /my/shops/{username}
- Allows to visit a shop owned by the user and make changes
### /my/shops/{username}/products/create
- Allows to create a new product under a specific shop owned by the user
### /my/shops/{username}/products/{productId}
- Allows to visit the product page owned by the user and make changes
### /search
- Allows the user to search for specific terms globally which will feed shops and products results
### /my-cart
- Allows the user to view and manage their shopping cart

### /orders
- Allows the user to view all their orders as buyer/seller
### /orders/{transactionCode}
- Allows the user to view a specific order as buyer/seller