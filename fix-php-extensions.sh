#!/bin/bash

echo "=========================================="
echo "Fixing PHP Extensions for Nailville App"
echo "=========================================="
echo ""

# Check if running as root
if [ "$EUID" -eq 0 ]; then 
    SUDO=""
else
    SUDO="sudo"
fi

# Install required PHP extensions
echo "Installing required PHP extensions..."
$SUDO apt update
$SUDO apt install -y php8.3-mysql php8.3-pdo php8.3-xml php8.3-dom php8.3-mbstring php8.3-curl

# Alternative: If php8.3 not found, try without version
if [ $? -ne 0 ]; then
    echo "Trying without version number..."
    $SUDO apt install -y php-mysql php-pdo php-xml php-dom php-mbstring php-curl
fi

echo ""
echo "Checking installed extensions..."
php -m | grep -E "(pdo_mysql|PDO|dom|xml)" || echo "Warning: Some extensions may not be loaded"

echo ""
echo "=========================================="
echo "Extensions installation complete!"
echo "=========================================="
echo ""
echo "Next steps:"
echo "1. Run: php artisan migrate --force"
echo "2. Run: php artisan sessions:cleanup"
echo "3. Test the login page"
echo ""
