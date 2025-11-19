# Complete Portfolio API Documentation

## 🎯 Overview
DDD (Domain-Driven Design) mimarisine uygun, Laravel 12 + Sanctum tabanlı Portfolio API.

**Base URL:** `http://localhost/api/v1` veya `https://sametgoktepe.test/api/v1`

---

## 📋 Table of Contents
1. [Authentication](#authentication)
2. [About](#about)
3. [Skills](#skills)
4. [Education](#education)
5. [Projects (Paginated)](#projects)

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
GET /api/v1/projects?per_page=10&status=completed
```

**Query Parameters:**
- `per_page`: Items per page (default: 15)
- `status`: Filter (in_progress, completed, backlog, cancelled)

**Response:**
```json
{
  "success": true,
  "message": "Projects retrieved successfully",
  "data": [...],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 25,
    "last_page": 3,
    "from": 1,
    "to": 10,
    "has_more_pages": true
  }
}
```

### Show Project (Public)
```http
GET /api/v1/projects/{id}/show
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
GET    /api/v1/projects/{id}/show
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
curl "http://localhost/api/v1/projects?per_page=5"
```

Save as `test-api.sh`, make executable: `chmod +x test-api.sh`, run: `./test-api.sh`

