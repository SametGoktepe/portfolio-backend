# Docker Setup for Portfolio Backend

This document explains how to run the Portfolio Backend application using Docker with Traefik proxy.

## Prerequisites

- Docker and Docker Compose installed
- Traefik proxy network created: `docker network create proxy`
- Environment variables configured in traefik-proxy directory

## Environment Variables

Add the following environment variables to your `traefik-proxy/.env` file or set them in the traefik-proxy docker-compose.yml:

```bash
# Portfolio Backend Application
PORTFOLIO_APP_NAME=Portfolio
PORTFOLIO_APP_ENV=production
PORTFOLIO_APP_KEY=base64:your-generated-key-here
PORTFOLIO_APP_DEBUG=false
PORTFOLIO_APP_URL=https://app.service.sametgoktepe.com

# Database Configuration
PORTFOLIO_DB_DATABASE=portfolio
PORTFOLIO_DB_USERNAME=portfolio_user
PORTFOLIO_DB_PASSWORD=your-secure-password
PORTFOLIO_DB_ROOT_PASSWORD=your-root-password

# Logging
PORTFOLIO_LOG_LEVEL=error
```

## Generate Application Key

Before running the application, generate a Laravel application key:

```bash
cd portfolio-backend
php artisan key:generate --show
```

Copy the generated key and set it as `PORTFOLIO_APP_KEY` in your environment variables.

## Running with Traefik Proxy

The application is configured to run within the traefik-proxy docker-compose setup:

```bash
cd traefik-proxy
docker-compose up -d portfolio-backend-app portfolio-backend-db
```

## Running Standalone

If you want to run the application standalone (not with traefik-proxy):

```bash
cd portfolio-backend
docker-compose up -d
```

## Building the Image

To build the Docker image manually:

```bash
cd portfolio-backend
docker build -t portfolio-backend:latest .
```

## Accessing the Application

Once running, the application will be available at:
- **URL**: https://app.service.sametgoktepe.com
- **API Base**: https://app.service.sametgoktepe.com/api/v1

## Database Migrations

Migrations run automatically on container startup. If you need to run them manually:

```bash
docker exec -it portfolio-backend-app php artisan migrate
```

## Remote Database Connection

To enable remote database connections, the `docker-compose.yml` has been configured with port mapping. The database is now accessible from outside the Docker network.

### Configuration

The database service exposes port 3306 by default (configurable via `DB_EXTERNAL_PORT` environment variable). MySQL is configured to listen on all interfaces (`0.0.0.0`).

### Connection Details

When connecting remotely, use the following connection parameters:

- **Host**: Your server's IP address or domain name
- **Port**: `3306` (or the value set in `DB_EXTERNAL_PORT`)
- **Database**: Value from `DB_DATABASE` environment variable (default: `portfolio`)
- **Username**: Value from `MYSQL_USER` environment variable (default: `portfolio_user`)
- **Password**: Value from `MYSQL_PASSWORD` environment variable

### Example Connection Strings

**MySQL Command Line:**
```bash
mysql -h YOUR_SERVER_IP -P 3306 -u portfolio_user -p portfolio
```

**Laravel .env (for external connection):**
```env
DB_CONNECTION=mysql
DB_HOST=YOUR_SERVER_IP
DB_PORT=3306
DB_DATABASE=portfolio
DB_USERNAME=portfolio_user
DB_PASSWORD=your-password
```

**MySQL Workbench / phpMyAdmin:**
- Server: `YOUR_SERVER_IP`
- Port: `3306`
- Username: `portfolio_user`
- Password: `your-password`
- Database: `portfolio`

### Security Considerations

⚠️ **Important Security Notes:**

1. **Firewall**: Ensure your firewall only allows connections from trusted IP addresses:
   ```bash
   # Example: Allow only specific IP
   sudo ufw allow from TRUSTED_IP_ADDRESS to any port 3306
   ```

2. **Strong Passwords**: Use strong, unique passwords for database users.

3. **SSL/TLS**: For production, consider enabling SSL/TLS connections by configuring MySQL SSL certificates.

4. **VPN/SSH Tunnel**: For better security, consider using an SSH tunnel instead of direct port exposure:
   ```bash
   ssh -L 3306:localhost:3306 user@your-server
   ```

5. **Change Default Port**: Consider changing the external port to a non-standard port:
   ```env
   DB_EXTERNAL_PORT=13306
   ```

### Restart After Configuration

After modifying environment variables or port settings, restart the database container:

```bash
docker-compose restart db
# Or if using traefik-proxy
docker-compose restart portfolio-backend-db
```

## Logs

View application logs:

```bash
# From traefik-proxy directory
docker-compose logs -f portfolio-backend-app

# Or standalone
cd portfolio-backend
docker-compose logs -f app
```

## Troubleshooting

### Database Connection Issues

Ensure the database container is running and the environment variables are set correctly:

```bash
docker exec -it portfolio-backend-db mysql -u portfolio_user -p portfolio
```

### Permission Issues

If you encounter permission issues with storage:

```bash
docker exec -it portfolio-backend-app chown -R www-data:www-data /var/www/html/storage
docker exec -it portfolio-backend-app chmod -R 755 /var/www/html/storage
```

### Clear Cache

```bash
docker exec -it portfolio-backend-app php artisan config:clear
docker exec -it portfolio-backend-app php artisan cache:clear
docker exec -it portfolio-backend-app php artisan route:clear
docker exec -it portfolio-backend-app php artisan view:clear
```

## Development

For development, you can mount the source code as a volume and use Laravel's built-in development server. Modify the docker-compose.yml accordingly.

