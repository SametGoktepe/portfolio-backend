# Portfolio API - Domain-Driven Design

<p align="center">
  <strong>Clean Architecture · DDD Principles · Laravel 12 · Sanctum Auth</strong>
</p>

## 🎯 Overview

Modern portfolio management API built with **Domain-Driven Design (DDD)** architecture. Features token-based authentication, pagination, and comprehensive CRUD operations for portfolio content management.

**Base URL:** 
- Local: `http://localhost/api/v1`
- Production: `https://sametgoktepe.test/api/v1`

**Authentication:** Bearer Token (Laravel Sanctum)

---

## ⚡ Quick Start

```bash
# 1. Install dependencies
composer install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Run migrations
php artisan migrate

# 4. Start server
php artisan serve
```

**Test API:**
```bash
# Register a user
curl -X POST http://localhost/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","surname":"User","username":"testuser","email":"test@test.com","password":"password123","password_confirmation":"password123"}'

# Get skills (public)
curl http://localhost/api/v1/skills
```

---

## 📋 Table of Contents
1. [Authentication](#-authentication)
2. [About](#-about)
3. [Skills](#-skills)
4. [Education](#-education)
5. [Projects](#-projects-with-pagination)
6. [Architecture](#-architecture)
7. [Error Handling](#-error-handling)

---

## 🔐 Authentication

### Register
```http
POST /api/v1/auth/register
```
**Body:**
```json
{
  "name": "Samet",
  "surname": "Goktepe",
  "username": "sametgoktepe",
  "email": "samet@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Login
```http
POST /api/v1/auth/login
```
**Body:**
```json
{
  "email": "samet@example.com",
  "password": "password123"
}
```

### Me (Protected)
```http
GET /api/v1/auth/me
Authorization: Bearer {token}
```

### Logout (Protected)
```http
POST /api/v1/auth/logout
Authorization: Bearer {token}
```

---

## 👤 About

### Get About (Public)
```http
GET /api/v1/about
```

### Create About (Protected)
```http
POST /api/v1/about
Authorization: Bearer {token}
```
**Body:**
```json
{
  "image": "/images/profile.jpg",
  "full_name": "Samet Goktepe",
  "title": "Full Stack Developer",
  "summary": "Passionate developer...",
  "email": "samet@example.com",
  "phone": "+90 555 123 4567",
  "city": "Istanbul",
  "state": "Istanbul",
  "country": "Turkey",
  "postal_code": "34000",
  "github": "https://github.com/sametgoktepe",
  "linkedin": "https://linkedin.com/in/sametgoktepe",
  "twitter": "https://twitter.com/sametgoktepe"
}
```

### Update About (Protected)
```http
PUT /api/v1/about/{id}/update
Authorization: Bearer {token}
```

### Delete About (Protected)
```http
DELETE /api/v1/about/{id}/delete
Authorization: Bearer {token}
```

---

## 💡 Skills

### List Skills (Public)
```http
GET /api/v1/skills
```
**Response:**
```json
{
  "success": true,
  "message": "Skills retrieved successfully",
  "data": [
    {
      "category": {
        "id": "uuid",
        "name": "Frontend",
        "slug": "frontend"
      },
      "skills": [
        {"id": "uuid", "category_id": "uuid", "name": "React"},
        {"id": "uuid", "category_id": "uuid", "name": "TypeScript"}
      ]
    }
  ]
}
```

### Create/Update Skills (Protected) - Sync Operation
```http
PUT /api/v1/skills/update
Authorization: Bearer {token}
```
**Body:**
```json
{
  "category_name": "Frontend",
  "skills": ["React", "TypeScript", "Next.js"]
}
```
**Response:**
```json
{
  "success": true,
  "message": "Skills synchronized successfully",
  "data": {
    "category": {
      "id": "uuid",
      "name": "Frontend",
      "slug": "frontend"
    },
    "skills": [...],
    "statistics": {
      "added": 1,
      "deleted": 0,
      "total": 3
    }
  }
}
```

### Store Skills (Protected) - Add Only
```http
POST /api/v1/skills/store
Authorization: Bearer {token}
```

### Delete Skill (Protected)
```http
DELETE /api/v1/skills/{id}/delete
Authorization: Bearer {token}
```

---

## 🎓 Education

### List Education (Public)
```http
GET /api/v1/education
```
**Response:**
```json
{
  "success": true,
  "message": "Education records retrieved successfully",
  "data": [
    {
      "id": "uuid",
      "school": "Istanbul University",
      "degree": "Bachelor of Science",
      "field_of_study": "Computer Engineering",
      "year_period": {
        "start_year": 2020,
        "end_year": 2024,
        "is_ongoing": false,
        "duration": "4 years"
      }
    }
  ]
}
```

### Show Education (Public)
```http
GET /api/v1/education/{id}/show
```

### Create Education (Protected)
```http
POST /api/v1/education/store
Authorization: Bearer {token}
```
**Body:**
```json
{
  "school": "MIT",
  "degree": "Master of Science",
  "field_of_study": "Artificial Intelligence",
  "start_year": 2024,
  "end_year": null
}
```

### Update Education (Protected)
```http
PUT /api/v1/education/{id}/update
Authorization: Bearer {token}
```

### Delete Education (Protected)
```http
DELETE /api/v1/education/{id}/delete
Authorization: Bearer {token}
```

---

## 🚀 Projects (With Pagination)

### List Projects (Public, Paginated)
```http
GET /api/v1/projects?perPage=10&status=completed
```

**Query Parameters:**
- `perPage`: Items per page (default: 15)
- `status`: Filter (in_progress, completed, backlog, cancelled)

**Response:**
```json
{
  "success": true,
  "message": "Projects retrieved successfully",
  "data": [...],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 25,
    "last_page": 2,
    "from": 1,
    "to": 15,
    "has_more_pages": true
  }
}
```

### Show Project (Protected) 🔒
```http
GET /api/v1/projects/{id}/show
Authorization: Bearer {token}
```

### Create Project (Protected)
```http
POST /api/v1/projects/store
Authorization: Bearer {token}
```
**Body:**
```json
{
  "title": "E-Commerce Platform",
  "description": "Full-featured online shopping platform",
  "images": ["https://example.com/img1.jpg"],
  "github_url": "https://github.com/user/ecommerce",
  "demo_link": "https://shop.demo.com",
  "technologies": ["Laravel", "Vue.js", "Stripe", "Redis"],
  "status": "in_progress"
}
```

### Update Project (Protected)
```http
PUT /api/v1/projects/{id}/update
Authorization: Bearer {token}
```

### Change Status (Protected)
```http
PATCH /api/v1/projects/{id}/status
Authorization: Bearer {token}
```
**Body:**
```json
{
  "status": "completed"
}
```

### Delete Project (Protected)
```http
DELETE /api/v1/projects/{id}/delete
Authorization: Bearer {token}
```

---

## 📊 Complete Route List

### Public Routes (No Auth)
```
POST   /api/v1/auth/register
POST   /api/v1/auth/login
GET    /api/v1/about
GET    /api/v1/skills
GET    /api/v1/education
GET    /api/v1/education/{id}/show
GET    /api/v1/projects              (Paginated)
```

### Protected Routes (Requires Bearer Token)
```
# Auth
POST   /api/v1/auth/logout
POST   /api/v1/auth/logout-all
GET    /api/v1/auth/me

# About
POST   /api/v1/about
PUT    /api/v1/about/{id}/update
DELETE /api/v1/about/{id}/delete

# Skills
POST   /api/v1/skills/store
PUT    /api/v1/skills/update         (Sync)
DELETE /api/v1/skills/{id}/delete

# Education
POST   /api/v1/education/store
PUT    /api/v1/education/{id}/update
DELETE /api/v1/education/{id}/delete

# Projects
GET    /api/v1/projects/{id}/show
POST   /api/v1/projects/store
PUT    /api/v1/projects/{id}/update
PATCH  /api/v1/projects/{id}/status
DELETE /api/v1/projects/{id}/delete
```

---

## 🎨 Status Enum Values

```json
{
  "in_progress": {
    "label": "In Progress",
    "color": "bg-blue-500"
  },
  "completed": {
    "label": "Completed",
    "color": "bg-green-500"
  },
  "backlog": {
    "label": "Backlog",
    "color": "bg-yellow-500"
  },
  "cancelled": {
    "label": "Cancelled",
    "color": "bg-red-500"
  }
}
```

---

## 🛠️ Development Setup

```bash
# Install dependencies
composer install

# Run migrations
php artisan migrate

# Generate app key
php artisan key:generate

# Start server
php artisan serve
```

---

## 📦 Technology Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Architecture**: Domain-Driven Design (DDD)
- **Database**: MySQL (via Herd)
- **Authentication**: Laravel Sanctum (Token-based)
- **Testing**: Pest PHP
- **Validation**: Form Requests + Value Objects

---

## ⚡ Quick Test Script

```bash
#!/bin/bash

# 1. Register
echo "Registering user..."
REGISTER_RESPONSE=$(curl -s -X POST http://localhost/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","surname":"User","username":"testuser","email":"test@test.com","password":"password123","password_confirmation":"password123"}')

TOKEN=$(echo $REGISTER_RESPONSE | jq -r '.data.token')
echo "Token: $TOKEN"

# 2. Create Project
echo "Creating project..."
curl -X POST http://localhost/api/v1/projects/store \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Portfolio API",
    "description": "DDD-based portfolio management system",
    "technologies": ["Laravel", "DDD", "Sanctum"],
    "status": "in_progress"
  }'

# 3. List Projects
echo "Listing projects..."
curl "http://localhost/api/v1/projects?perPage=5"
```

Save as `test-api.sh`, make executable: `chmod +x test-api.sh`, run: `./test-api.sh`

---

## 💎 Why Domain-Driven Design?

### Traditional Approach Problem:
```php
// ❌ Business logic scattered everywhere
$user->email = $request->email; // No validation
$user->save();
```

### DDD Approach Solution:
```php
// ✅ Validation in Value Objects
$email = new Email($request->email); // Validates format
$user->updateEmail($email);          // Business rules in domain
```

### Benefits:
1. **Self-Validating** - Value objects validate themselves
2. **Testable** - Pure business logic, easy to test
3. **Reusable** - Value objects used across domains
4. **Maintainable** - Changes isolated to specific layers
5. **Type-Safe** - Compile-time error checking

---

## 📈 Performance

- **Pagination**: Efficient large dataset handling
- **Batch Operations**: Bulk skill insertion
- **Eager Loading**: Prevent N+1 queries (where needed)
- **Optimized Autoload**: Composer optimization
- **Database Indexes**: UUID primary keys, foreign keys

---

## 🌍 CORS Configuration

For frontend applications, configure CORS in `config/cors.php`:

```php
'paths' => ['api/*'],
'allowed_methods' => ['*'],
'allowed_origins' => ['http://localhost:3000', 'https://yourdomain.com'],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => false,
```

---

## 📝 License

MIT License - Feel free to use for your portfolio!

---

## 👨‍💻 Author

**Samet Goktepe**
- GitHub: [@sametgoktepe](https://github.com/sametgoktepe)
- Website: [sametgoktepe.test](https://sametgoktepe.test)

---

## 🤝 Contributing

This is a personal portfolio API. Feel free to fork and customize for your own use!

---

**Built with ❤️ using Laravel & DDD principles**

---

## 🏗️ Architecture

### Domain-Driven Design Structure

```
src/Domain/                     # Business Logic Layer
├── About/
│   ├── Models/                 # Domain entities
│   ├── ValueObjects/           # Value objects with validation
│   ├── Services/               # Business logic
│   ├── Repositories/           # Repository interfaces
│   └── Exceptions/
├── Auth/
│   ├── ValueObjects/           # Email, Password, Username
│   └── Services/               # AuthService
├── Category/
│   ├── Models/
│   ├── ValueObjects/           # CategoryName, CategorySlug
│   ├── Services/
│   └── Repositories/
├── Education/
│   ├── Models/
│   ├── ValueObjects/           # School, Degree, YearPeriod
│   ├── Services/
│   └── Repositories/
├── Project/
│   ├── Models/
│   ├── ValueObjects/           # Title, Description, ProjectStatus
│   ├── Services/               # Pagination logic
│   └── Repositories/
├── Skill/
│   ├── Models/
│   ├── ValueObjects/           # SkillName
│   ├── Services/
│   └── Repositories/
└── Shared/
    ├── Exceptions/             # DomainException
    └── ValueObjects/           # Uuid base class

src/Infrastructure/             # Technical Implementation Layer
├── Persistence/Eloquent/
│   ├── Models/                 # Eloquent models
│   └── Repositories/           # Repository implementations
└── Providers/                  # Service providers

app/                            # Application Layer
├── Http/
│   ├── Controllers/Api/        # HTTP request handling
│   ├── Requests/               # Validation
│   └── Resources/              # Response transformation
├── Models/                     # Eloquent models (legacy)
└── Enums/                      # Shared enums (Status)
```

### Key Principles

✅ **Separation of Concerns**: Clear boundaries between layers
✅ **Dependency Inversion**: Domain doesn't depend on infrastructure
✅ **Value Objects**: Immutable, self-validating
✅ **Rich Domain Models**: Business logic in domain entities
✅ **Repository Pattern**: Data persistence abstraction
✅ **Service Layer**: Complex business operations

---

## ⚠️ Error Handling

All API responses follow a consistent structure:

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {...}
}
```

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation errors",
  "errors": {
    "email": ["Email already exists"],
    "password": ["Password must be at least 8 characters"]
  }
}
```

### Domain Error (422)
```json
{
  "success": false,
  "message": "Domain validation error",
  "error": "Skill 'React' already exists in this category"
}
```

### Authentication Error (401)
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

### Not Found (404)
```json
{
  "success": false,
  "message": "Project not found"
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "Failed to create project",
  "error": "Error details..."
}
```

---

## 🎨 Frontend Integration

### React/Next.js Example

```typescript
// api/client.ts
const API_BASE_URL = 'https://sametgoktepe.test/api/v1';

export const apiClient = {
  async getProjects(page = 1, perPage = 10, status?: string) {
    const params = new URLSearchParams({ 
      perPage: perPage.toString(),
      ...(status && { status })
    });
    
    const response = await fetch(`${API_BASE_URL}/projects?${params}`);
    return response.json();
  },
  
  async getSkills() {
    const response = await fetch(`${API_BASE_URL}/skills`);
    return response.json();
  },
  
  async login(email: string, password: string) {
    const response = await fetch(`${API_BASE_URL}/auth/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });
    return response.json();
  }
};
```

### Vue.js Example

```javascript
// composables/useApi.js
import { ref } from 'vue';

export function useProjects() {
  const projects = ref([]);
  const pagination = ref(null);
  const loading = ref(false);

  async function fetchProjects(perPage = 15, status = null) {
    loading.value = true;
    const params = new URLSearchParams({ perPage });
    if (status) params.append('status', status);
    
    const response = await fetch(`/api/v1/projects?${params}`);
    const data = await response.json();
    
    projects.value = data.data;
    pagination.value = data.pagination;
    loading.value = false;
  }

  return { projects, pagination, loading, fetchProjects };
}
```

---

## 📚 Additional Documentation

- `AUTH_API_DOCUMENTATION.md` - Detailed authentication guide
- `PROJECT_API_DOCUMENTATION.md` - Projects endpoint details

---

## 🧪 Testing

### Manual Testing
```bash
# Get all skills (public)
curl http://localhost/api/v1/skills

# Get paginated projects
curl "http://localhost/api/v1/projects?perPage=5&status=completed"

# Login and create project
TOKEN=$(curl -s -X POST http://localhost/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"password123"}' \
  | jq -r '.data.token')

curl -X POST http://localhost/api/v1/projects/store \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "My Project",
    "description": "Project description here",
    "technologies": ["Laravel", "Vue.js"],
    "status": "in_progress"
  }'
```

### Automated Testing (Pest)
```bash
php artisan test
```

---

## 🔑 Key Features

- ✅ **DDD Architecture** - Clean separation of concerns
- ✅ **Token Authentication** - Secure API access with Sanctum
- ✅ **Pagination** - Efficient data loading for projects
- ✅ **Value Objects** - Self-validating domain objects
- ✅ **Status Enum** - Type-safe project status management
- ✅ **Sync Operations** - Smart skill synchronization
- ✅ **Public/Private Routes** - Read public, write protected
- ✅ **Rich Responses** - Detailed error messages and data
- ✅ **UUID Primary Keys** - Secure, non-sequential IDs

---

## 🔒 Security

- **Authentication**: Laravel Sanctum token-based
- **Authorization**: Middleware protection on write operations
- **Validation**: Multi-layer (Request + Value Objects)
- **Password**: Bcrypt hashing
- **CORS**: Configurable in `config/cors.php`

**Public Access:** GET operations (viewing portfolio)
**Protected Access:** POST/PUT/DELETE (managing content)

---

## 📖 API Response Examples

### Successful Create
```json
{
  "success": true,
  "message": "Project created successfully",
  "data": {
    "id": "9d3e4f5a-...",
    "title": "Portfolio Website",
    "status": {
      "value": "completed",
      "label": "Completed",
      "color": "bg-green-500"
    }
  }
}
```

### Pagination Response
```json
{
  "success": true,
  "data": [...],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 25,
    "last_page": 2,
    "has_more_pages": true
  }
}
```

