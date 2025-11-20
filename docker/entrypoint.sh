#!/bin/sh
set -e

# Start supervisor in background
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf &

# Wait for database to be ready (with timeout)
timeout=60
counter=0
until nc -z portfolio-backend-db 3306 || [ $counter -ge $timeout ]; do
    echo "Waiting for database... ($counter/$timeout)"
    sleep 1
    counter=$((counter + 1))
done

if [ $counter -ge $timeout ]; then
    echo "Warning: Database connection timeout. Continuing anyway..."
fi

# Wait a bit for services to start
sleep 3

# Run Laravel migrations and optimizations
php artisan migrate --force || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Keep container running
wait

