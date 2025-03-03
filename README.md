# Logistics Delivery Points Tracker

A Laravel API application for managing delivery points with live weather data integration.

## Features

- RESTful API for managing delivery points
- Live weather data integration with OpenWeatherMap API
- MySQL database storage
- Input validation
- Error handling

## Requirements

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- OpenWeatherMap API key

## GitHub Repository

You need to create a GitHub repository first:

1. Go to [GitHub](https://github.com/) and sign in
2. Click the "+" icon in the top right corner and select "New repository"
3. Name your repository (e.g., "logistics-delivery-points-tracker")
4. Add a description (optional)
5. Choose public or private visibility
6. Click "Create repository"

Then, connect your local repository to GitHub:

```bash
# From your project directory
git remote add origin https://github.com/YOUR-USERNAME/YOUR-REPOSITORY-NAME.git
git push -u origin main
```

Replace `YOUR-USERNAME` with your GitHub username and `YOUR-REPOSITORY-NAME` with the name you gave your repository.

## Setup Instructions

### 1. Clone the repository

```bash
git clone https://github.com/yourusername/logistics-delivery-points.git
cd logistics-delivery-points
```

### 2. Install dependencies

```bash
composer install
```

### 3. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Set up MySQL database

```bash
# Start MySQL service if not running
brew services start mysql

# Create a database
mysql -u root -e "CREATE DATABASE IF NOT EXISTS delivery_points;"
```

### 5. Configure database connection

Update the `.env` file with your database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=delivery_points
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Add OpenWeatherMap API key

1. Get your API key from [OpenWeatherMap](https://openweathermap.org/api)
2. Add it to your `.env` file:

```
OPENWEATHERMAP_API_KEY=your_api_key_here
```

### 7. Run migrations

```bash
php artisan migrate
```

### 8. Start the development server

```bash
php artisan serve
```

The application will be accessible at `http://localhost:8000`.

## API Endpoints

### Create Delivery Point
- **POST** `/api/delivery`
- Required fields:
  - `name` (string)
  - `city` (string)
  - `contact_person` (string)
- Optional fields:
  - `contact_number` (numeric)

### List All Delivery Points
- **GET** `/api/delivery`
- Returns all delivery points with live weather data for each city

### View Delivery Point
- **GET** `/api/delivery/{id}`
- Returns a specific delivery point with live weather data for its city

## Example API Usage

### Create a Delivery Point
```bash
curl -X POST http://localhost:8000/api/delivery \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Main Branch",
    "city": "London",
    "contact_person": "John Doe",
    "contact_number": "1234567890"
  }'
```

### List All Delivery Points
```bash
curl http://localhost:8000/api/delivery
```

### View Specific Delivery Point
```bash
curl http://localhost:8000/api/delivery/1
```

## Response Format

The API returns JSON responses with the following structure:

```json
{
  "id": 1,
  "name": "Main Branch",
  "city": "London",
  "contact_person": "John Doe",
  "contact_number": "1234567890",
  "created_at": "2025-03-03T15:30:00.000000Z",
  "updated_at": "2025-03-03T15:30:00.000000Z",
  "weather": {
    "temperature": 15.6,
    "condition": "scattered clouds"
  }
}
```

## How the Application Works

### Architecture

The application follows Laravel's MVC architecture:

1. **Models**: The `DeliveryPoint` model represents the delivery point entity
2. **Controllers**: The `DeliveryPointController` handles API requests
3. **Services**: The `WeatherService` fetches weather data from OpenWeatherMap

### Weather Data Integration

Weather data is fetched live from OpenWeatherMap when delivery points are retrieved. The process works as follows:

1. When a delivery point is requested, the controller retrieves the delivery point from the database
2. The controller then uses the `WeatherService` to fetch current weather data for the city
3. The weather data is added to the delivery point response
4. The combined data is returned to the client

Weather data is not stored in the database and is fetched in real-time for each request.

## Troubleshooting

### API returns 404

If the API endpoints return 404 errors, try clearing the route cache:

```bash
php artisan route:clear
php artisan optimize:clear
```

### Database connection issues

If you encounter database connection issues, make sure:
- MySQL service is running
- Database credentials in `.env` are correct
- The database exists

### Weather data not showing

If weather data is not showing:
- Check if your OpenWeatherMap API key is valid
- Ensure the city name is correctly spelled
- Check if the OpenWeatherMap service is available

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Deployment Options

### Option 1: Deploy to Laravel Forge

[Laravel Forge](https://forge.laravel.com/) provides a simple way to deploy Laravel applications:

1. Sign up for Laravel Forge
2. Connect your server provider (DigitalOcean, AWS, etc.)
3. Create a new server
4. Connect your GitHub repository
5. Configure deployment settings
6. Deploy your application

### Option 2: Deploy to Heroku

1. Create a `Procfile` in your project root:
   ```
   web: vendor/bin/heroku-php-apache2 public/
   ```

2. Create a Heroku app and push your code:
   ```bash
   heroku create
   git push heroku main
   ```

3. Configure environment variables:
   ```bash
   heroku config:set APP_KEY=$(php artisan --no-ansi key:generate --show)
   heroku config:set OPENWEATHERMAP_API_KEY=your_api_key_here
   ```

4. Set up a database:
   ```bash
   heroku addons:create heroku-postgresql:hobby-dev
   ```

5. Run migrations:
   ```bash
   heroku run php artisan migrate
   ```

### Option 3: Deploy to a VPS

1. Set up a VPS with a web server (Nginx/Apache)
2. Set up PHP and MySQL
3. Clone your repository to the server
4. Configure the web server to point to your application
5. Set up environment variables
6. Run migrations

For detailed deployment instructions, refer to the [Laravel deployment documentation](https://laravel.com/docs/10.x/deployment).
