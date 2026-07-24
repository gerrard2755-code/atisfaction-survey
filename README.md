# Satisfaction Survey System

A comprehensive web application for collecting and analyzing website satisfaction surveys.

## Technology Stack

### Backend
- **Laravel 11** - PHP Framework
- **MySQL 8.0** - Database
- **RESTful API** - API Design

### Frontend
- **Vue 3** - JavaScript Framework
- **Vite** - Build Tool
- **Bootstrap 5** - CSS Framework
- **Axios** - HTTP Client
- **Pinia** - State Management

### DevOps
- **Docker** - Containerization
- **Docker Compose** - Multi-container Orchestration

## Project Structure

```
.
├── backend/                 # Laravel API
│   ├── app/
│   │   ├── Models/         # Eloquent Models
│   │   └── Http/
│   │       └── Controllers/Api/
│   ├── database/
│   │   └── migrations/     # Database Migrations
│   ├── routes/
│   │   └── api.php         # API Routes
│   └── Dockerfile
├── frontend/                # Vue.js Application
│   ├── src/
│   │   ├── components/     # Vue Components
│   │   ├── views/          # Page Views
│   │   ├── stores/         # Pinia Stores
│   │   ├── router/         # Vue Router
│   │   └── App.vue         # Root Component
│   └── Dockerfile
├── docker-compose.yml       # Docker Compose Configuration
└── README.md
```

## Quick Start

### With Docker (Recommended)

```bash
# Clone the repository
git clone <repository-url>
cd satisfaction-survey

# Start services
docker-compose up -d

# Run migrations
docker-compose exec api php artisan migrate

# Create admin user
docker-compose exec api php artisan tinker
```

Access the application:
- **Frontend**: http://localhost:5173
- **API**: http://localhost:8000
- **PhpMyAdmin**: http://localhost:8080 (admin / password)

### Manual Setup

#### Backend Setup
```bash
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan serve
```

#### Frontend Setup
```bash
cd frontend
npm install
npm run dev
```

## Database Schema

### Tables

- **users** - Admin and staff users
- **questionnaires** - Survey questionnaires
- **questions** - Survey questions
- **responses** - Submitted survey responses
- **response_details** - Individual question scores
- **suggestions** - User comments/suggestions

## API Documentation

### Authentication
- `POST /api/auth/login` - Login
- `POST /api/auth/logout` - Logout

### Surveys (Public)
- `GET /api/surveys` - Get all active surveys
- `GET /api/surveys/{id}` - Get survey details

### Response Submission (Public)
- `POST /api/responses` - Submit survey response

### Admin Endpoints (Protected)
- `POST /api/surveys` - Create survey
- `PUT /api/surveys/{id}` - Update survey
- `DELETE /api/surveys/{id}` - Delete survey
- `POST /api/surveys/{id}/questions` - Add question
- `PUT /api/questions/{id}` - Update question
- `DELETE /api/questions/{id}` - Delete question
- `GET /api/responses` - Get all responses
- `GET /api/admin/dashboard` - Dashboard statistics

## Features

✅ Survey creation and management
✅ Multi-category question support (Content, Design, Usability, Performance)
✅ 1-5 point rating scale
✅ Demographic tracking (Gender, Age, User Type)
✅ Comment/Suggestion submission
✅ Admin dashboard with statistics
✅ Daily response tracking
✅ Category-wise score analysis
✅ Responsive design
✅ Export functionality (coming soon)

## Development

### Backend Development
```bash
cd backend
php artisan tinker          # Interactive shell
php artisan make:model Name # Generate model
php artisan migrate         # Run migrations
php artisan db:seed         # Seed database
```

### Frontend Development
```bash
cd frontend
npm run dev                 # Development server
npm run build               # Production build
npm run lint                # Lint code
```

## Testing

```bash
# Backend tests
cd backend
php artisan test

# Frontend tests
cd frontend
npm run test
```

## Deployment

See [DEPLOYMENT.md](./DEPLOYMENT.md) for production deployment instructions.

## Contributors

- Development Team

## License

MIT License
