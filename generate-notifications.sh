#!/bin/bash

# Test Notification Generator Script
# This script generates test notifications every 30 seconds

echo "🔔 Notification Generator"
echo "========================"
echo ""
echo "This will generate test notifications every 30 seconds."
echo "Press Ctrl+C to stop."
echo ""

# Check if interval argument is provided
INTERVAL=${1:-30}

echo "⏱️  Interval: ${INTERVAL} seconds"
echo ""

# Run the Laravel command
php artisan notifications:generate-test --interval=$INTERVAL
