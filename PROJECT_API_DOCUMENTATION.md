# Projects API Documentation

## Overview
DDD yapısına uygun, pagination destekli Projects API. Status Enum kullanımı ile güçlü durum yönetimi.

## Endpoints

### 1. List Projects (Paginated) 📄
```http
GET /api/v1/projects?per_page=10&status=completed
```

**Query Parameters:**
- `per_page` (optional): Items per page (default: 15)
- `status` (optional): Filter by status (in_progress, completed, backlog, cancelled)

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Projects retrieved successfully",
  "data": [
    {
      "id": "uuid-here",
      "title": "Portfolio Website",
      "description": "Modern portfolio with React and Laravel",
      "images": ["https://example.com/image1.jpg"],
      "github_url": "https://github.com/user/portfolio",
      "demo_link": "https://portfolio.com",
      "technologies": ["React", "Laravel", "Tailwind"],
      "status": {
        "value": "completed",
        "label": "Completed",
        "color": "bg-green-500"
      }
    }
  ],
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

---

### 2. Show Single Project
```http
GET /api/v1/projects/{id}/show
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": "uuid",
    "title": "E-Commerce Platform",
    "description": "Full-featured e-commerce with payment integration",
    "images": ["url1", "url2"],
    "github_url": "https://github.com/...",
    "demo_link": "https://demo.com",
    "technologies": ["Laravel", "Vue.js", "MySQL", "Redis"],
    "status": {
      "value": "in_progress",
      "label": "In Progress",
      "color": "bg-blue-500"
    }
  }
}
```

---

### 3. Create Project 🔒 (Protected)
```http
POST /api/v1/projects/store
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "title": "AI Chat Application",
  "description": "Real-time chat with AI integration",
  "images": [
    "https://example.com/screenshot1.jpg",
    "https://example.com/screenshot2.jpg"
  ],
  "github_url": "https://github.com/user/ai-chat",
  "demo_link": "https://ai-chat.demo.com",
  "technologies": ["Node.js", "Socket.io", "OpenAI", "React"],
  "status": "in_progress"
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Project created successfully",
  "data": {
    "id": "new-uuid",
    "title": "AI Chat Application",
    "description": "Real-time chat with AI integration",
    "images": ["https://example.com/screenshot1.jpg", "https://example.com/screenshot2.jpg"],
    "github_url": "https://github.com/user/ai-chat",
    "demo_link": "https://ai-chat.demo.com",
    "technologies": ["Node.js", "Socket.io", "OpenAI", "React"],
    "status": {
      "value": "in_progress",
      "label": "In Progress",
      "color": "bg-blue-500"
    }
  }
}
```

---

### 4. Update Project 🔒 (Protected)
```http
PUT /api/v1/projects/{id}/update
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:** (Same as create)

---

### 5. Change Status 🔒 (Protected)
```http
PATCH /api/v1/projects/{id}/status
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "status": "completed"
}
```

**Available Statuses:**
- `in_progress` - Devam ediyor (Blue)
- `completed` - Tamamlandı (Green)
- `backlog` - Beklemede (Yellow)
- `cancelled` - İptal edildi (Red)

---

### 6. Delete Project 🔒 (Protected)
```http
DELETE /api/v1/projects/{id}/delete
Authorization: Bearer {token}
```

---

## Validation Rules

### Required Fields
- `title`: 3-255 characters
- `description`: Min 10 characters
- `technologies`: Array, min 1 item, each max 50 chars

### Optional Fields
- `images`: Array of valid URLs
- `github_url`: Valid URL
- `demo_link`: Valid URL
- `status`: Enum (in_progress, completed, backlog, cancelled)

---

## Pagination Examples

### Default Pagination (15 per page)
```bash
curl http://localhost/api/v1/projects
```

### Custom Per Page
```bash
curl "http://localhost/api/v1/projects?per_page=5"
```

### Filter by Status
```bash
curl "http://localhost/api/v1/projects?status=completed"
```

### Combined
```bash
curl "http://localhost/api/v1/projects?per_page=10&status=in_progress"
```

---

## Status Enum Usage

### Backend (PHP)
```php
use App\Enums\Status;

$status = Status::COMPLETED;
$status->value();      // "completed"
$status->label();      // "Completed"
$status->color();      // "bg-green-500"
```

### Frontend (Response)
```json
"status": {
  "value": "completed",
  "label": "Completed",
  "color": "bg-green-500"
}
```

Use `color` for Tailwind CSS classes or map to your own colors.

---

## cURL Examples

### Create Project
```bash
curl -X POST http://localhost/api/v1/projects/store \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Blog Platform",
    "description": "Modern blogging platform with markdown support",
    "images": ["https://example.com/blog-preview.jpg"],
    "github_url": "https://github.com/user/blog",
    "demo_link": "https://myblog.com",
    "technologies": ["Laravel", "Livewire", "Alpine.js", "Tailwind"],
    "status": "in_progress"
  }'
```

### List Projects with Pagination
```bash
curl "http://localhost/api/v1/projects?per_page=5&status=completed"
```

### Update Project Status
```bash
curl -X PATCH http://localhost/api/v1/projects/{id}/status \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"status": "completed"}'
```

---

## Domain-Driven Design Structure

```
src/Domain/Project/
├── Models/
│   ├── Project.php           # Rich domain model
│   └── ProjectId.php          # UUID identifier
├── ValueObjects/
│   ├── Title.php              # Title validation (3-255 chars)
│   ├── Description.php        # Description validation (min 10 chars)
│   ├── ProjectStatus.php      # Status enum wrapper
│   ├── Technologies.php       # Array validation
│   ├── Images.php             # Array of image URLs
│   └── Url.php                # URL validation
├── Services/
│   └── ProjectService.php     # Business logic with pagination
└── Repositories/
    └── ProjectRepositoryInterface.php

src/Infrastructure/
├── Persistence/Eloquent/
│   ├── Models/
│   │   └── EloquentProject.php
│   └── Repositories/
│       └── EloquentProjectRepository.php  # Pagination implementation
└── Providers/
    └── ProjectServiceProvider.php

app/Enums/
└── Status.php                 # Shared Status Enum

app/Http/
├── Controllers/Api/Project/
│   └── ProjectController.php  # Pagination controller
└── Requests/Project/
    └── ProjectRequest.php     # Validation
```

---

## Features

✅ **Pagination Support** - Efficient data loading
✅ **Status Filtering** - Filter by project status
✅ **Status Enum** - Type-safe status management
✅ **Rich Value Objects** - Domain validation
✅ **URL Validation** - GitHub & demo links
✅ **Image Management** - Multiple images support
✅ **Technology Stack** - Array of technologies
✅ **Status Colors** - Frontend-ready color codes
✅ **Public/Private Routes** - Read public, write protected

---

## Migration

Run migration to create projects table:
```bash
php artisan migrate
```

---

## Testing

```bash
# Login first
TOKEN=$(curl -X POST http://localhost/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"password123"}' \
  | jq -r '.data.token')

# Create project
curl -X POST http://localhost/api/v1/projects/store \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Project",
    "description": "This is a test project for API testing",
    "technologies": ["PHP", "Laravel"],
    "status": "in_progress"
  }'

# List projects (public)
curl "http://localhost/api/v1/projects?per_page=5"
```

