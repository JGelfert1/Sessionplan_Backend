#!/bin/bash

# Sessionplan Backend Start Script

PORT=${1:-8000}

echo "🚀 Sessionplan Backend startet..."
echo "📍 Port: $PORT"
echo "🌐 Admin: http://localhost:$PORT/admin.html"
echo "🔌 API: http://localhost:$PORT/api"
echo ""
echo "Drücke Ctrl+C zum Stoppen"
echo ""

cd "$(dirname "$0")"
php -S 0.0.0.0:$PORT router.php
