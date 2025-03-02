# News Aggregator API

This is a Laravel 8-based News Aggregator API that fetches and manages news articles. It includes user authentication, article personalization, and scheduled news fetching.

---

## **1. Project Setup (Without Docker)**
Since Docker is not working on my local system, please follow these manual installation steps.

### **Prerequisites**
Ensure you have the following installed:
- **PHP** `8.2.12`
- **Composer** `>= 2.0`
- **MySQL** `>= 5.7` (or any database of choice)
- **Laravel** `8`
- **Node.js** `>= 16` (for frontend assets, if applicable)
- **Redis** (Optional: If using queue system)
- **Supervisor** (Optional: If running queue workers in production)

### **Installation Steps**
1. **Clone the repository:**
   ```sh
   git clone https://github.com/biswaranjan3705/news-aggregator.git
   cd news-aggregator

2. Install dependencies:
    composer install
    npm install && npm run dev  # If using frontend assets
3.Set up environment variables:
    cp .env.example .env

    >>Update database details in .env

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=news_aggregator
    DB_USERNAME=root
    DB_PASSWORD=yourpassword
4.Generate application key:
    php artisan key:generate
5. Run database migrations & seeders:
   php artisan migrate --seed
6. Start the Laravel development server:
    php artisan serve

7. Scheduled Command for Fetching News  
    This application includes a Laravel scheduled task to fetch news articles. 
    Command:  php artisan news:fetch
    This command fetches the latest articles and stores them in the database.
    >>To schedule it automatically, add this to your crontab (Linux/macOS):
    * * * * * php /path-to-project/artisan schedule:run >> /dev/null 2>&1

8. API Documentation

    The API follows RESTful principles and provides endpoints for authentication, preferences, and fetching articles.

    API Documentation (Swagger/OpenAPI):
    Local API Docs: http://127.0.0.1:8000/api/documentation








