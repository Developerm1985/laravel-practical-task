# Laravel Google Sheets Integration

This project demonstrates how to integrate Google Sheets with a Laravel application. You can read, write, and manipulate Google Sheets data using this setup.

## Requirements

- PHP >= 7.3
- Composer
- Laravel >= 8.x
- Node.js and NPM (for front-end assets)

## Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/yourusername/laravel-google-sheets.git
   cd laravel-google-sheets
   ```

2. **Install dependencies:**

   ```bash
   composer install
   ```

3. **Set up your environment:**

   Copy the `.env.example` to `.env`:

   ```bash
   cp .env.example .env
   ```

4. **Update your `.env` file:**

   Add your Google Sheets credentials:

   ```env
   GOOGLE_SHEET_CLIENT_ID=
   GOOGLE_SHEET_CLIENT_SECRET=
   GOOGLE_SHEET_REDIRECT_URI=
   GOOGLE_SHEET_APPLICATION_CREDENTIALS=
   ```

5. **Generate application key:**

   ```bash
   php artisan key:generate
   ```

6. **Run database migrations (if applicable):**

   ```bash
   php artisan migrate
   ```

7. **Install front-end dependencies (optional):**

   If your project includes front-end assets, run:

   ```bash
   npm install
   npm run dev
   ```

## Running the Application

To run the application locally, use the built-in PHP server:

```bash
php artisan serve --port=8001
```

Now you can access your application at `http://localhost:8001`.

## Usage

- Visit the `/google` route to authenticate with Google and access your Google Sheets.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.
