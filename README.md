## About The Project

[![wakatime](https://wakatime.com/badge/user/26566595-1b62-44e9-bdc1-94bbe94879ec/project/d4b0deca-4afb-45a4-b50c-e939e6189207.svg)](https://wakatime.com/badge/user/26566595-1b62-44e9-bdc1-94bbe94879ec/project/d4b0deca-4afb-45a4-b50c-e939e6189207)

This is a web-based application for UNSAP's student admission process (PMB) for academic.

### Built With
* PHP 8.1.4
* Laravel 8.30

## Getting Started

### Prerequisites
* ServBay/MAMP/WAMP Server
* Composer
* PHP >= 8.1.4

### Installation
1. Clone the repository
```bash
git clone https://github.com/yourusername/pmb-unsap.git
```

2. Install Composer dependencies
```bash
composer install
```

3. Copy `.env.example` to `.env`
```bash
cp .env.example .env
```

4. Generate application key
```bash
php artisan key:generate
```

5. Run migrations
```bash
php artisan migrate
```

6. Start the development server
```bash
php artisan serve
```

## Contributing
Contributions are welcome. Please follow these steps:
1. Fork the Project
2. Create your Feature Branch
3. Commit your Changes
4. Push to the Branch
5. Open a Pull Request
