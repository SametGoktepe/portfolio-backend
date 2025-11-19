# Authentication API Documentation

## Overview
DDD (Domain-Driven Design) mimarisine uygun, Laravel Sanctum tabanlı token-based authentication sistemi.

## Base URL
```
http://localhost/api/v1
```

## Authentication Endpoints

### 1. Register (Kayıt Ol)
```http
POST /api/v1/auth/register
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "John",
  "surname": "Doe",
  "username": "johndoe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Validation Rules:**
- `name`: Required, 2-100 characters
- `surname`: Optional, max 100 characters
- `username`: Required, 3-50 characters, only letters, numbers, underscore, hyphen, unique
- `email`: Required, valid email, unique, max 255 characters
- `password`: Required, min 8 characters, must be confirmed

**Response (201 Created):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John",
      "surname": "Doe",
      "username": "johndoe",
      "email": "john@example.com",
      "created_at": "2025-11-19 20:00:00"
    },
    "token": "1|abc123token...",
    "token_type": "Bearer"
  }
}
```

---

### 2. Login (Giriş Yap)
```http
POST /api/v1/auth/login
Content-Type: application/json
```

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John",
      "surname": "Doe",
      "username": "johndoe",
      "email": "john@example.com",
      "created_at": "2025-11-19 20:00:00"
    },
    "token": "2|xyz456token...",
    "token_type": "Bearer"
  }
}
```

**Error Response (401 Unauthorized):**
```json
{
  "success": false,
  "message": "Login failed",
  "error": "Invalid credentials"
}
```

---

### 3. Get Current User (Authenticated)
```http
GET /api/v1/auth/me
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John",
    "surname": "Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "created_at": "2025-11-19 20:00:00",
    "updated_at": "2025-11-19 20:00:00"
  }
}
```

---

### 4. Logout (Current Device)
```http
POST /api/v1/auth/logout
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Logout successful"
}
```

---

### 5. Logout All Devices
```http
POST /api/v1/auth/logout-all
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Logged out from all devices successfully"
}
```

---

## Using Authentication Token

After login or registration, you'll receive a token. Include this token in all protected API requests:

```http
Authorization: Bearer {your-token-here}
```

### Example with cURL:
```bash
# Register
curl -X POST http://localhost/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John","surname":"Doe","username":"johndoe","email":"john@example.com","password":"password123","password_confirmation":"password123"}'

# Get user info
curl -X GET http://localhost/api/v1/auth/me \
  -H "Authorization: Bearer 1|abc123token..."
```

### Example with JavaScript (Fetch):
```javascript
fetch('http://localhost/api/v1/auth/me', {
  method: 'GET',
  headers: {
    'Authorization': 'Bearer ' + token,
    'Content-Type': 'application/json'
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

---

## Error Responses

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

### Unauthorized (401)
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "An error occurred during registration",
  "error": "Error details..."
}
```

---

## Protected Routes

The following routes require authentication (Bearer token):

### Auth Routes:
- `POST /api/v1/auth/logout` - Logout current device
- `POST /api/v1/auth/logout-all` - Logout all devices
- `GET /api/v1/auth/me` - Get current user

### Content Routes (if you want to protect them):
You can add `->middleware('auth:sanctum')` to any route group to protect them.

---

## Security Best Practices

1. **HTTPS**: Always use HTTPS in production
2. **Token Storage**: Store tokens securely (httpOnly cookies for web, secure storage for mobile)
3. **Token Expiration**: Configure token expiration in `config/sanctum.php`
4. **Password**: Enforce strong password policies
5. **Rate Limiting**: Add rate limiting to prevent brute force attacks

---

## Testing with Postman

1. **Register/Login**: Get your token from register or login response
2. **Save Token**: Save the token as an environment variable
3. **Protected Requests**: Add header `Authorization: Bearer {{token}}`

---

## Domain-Driven Design Structure

```
src/Domain/Auth/
├── ValueObjects/
│   ├── Email.php          # Email validation
│   ├── Password.php       # Password validation & hashing
│   └── Username.php       # Username validation
└── Services/
    └── AuthService.php    # Authentication business logic

app/Http/
├── Controllers/Api/Auth/
│   └── AuthController.php # HTTP layer
└── Requests/Auth/
    ├── RegisterRequest.php
    └── LoginRequest.php
```

## Token Management

### Token Lifespan
- Default: No expiration (configurable in `config/sanctum.php`)
- Can be set to expire after X minutes

### Token Abilities (Optional)
You can create tokens with specific abilities:
```php
$token = $user->createToken('token-name', ['server:update'])->plainTextToken;
```

Then check abilities in middleware or controller:
```php
if ($request->user()->tokenCan('server:update')) {
    // User has permission
}
```

