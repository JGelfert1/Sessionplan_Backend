#!/bin/bash

# Sessionplan Backend Test Script

BASE_URL="http://localhost:8000/api"

echo "=== Sessionplan Backend API Tests ==="
echo

# 1. List all plans
echo "1. GET / - Alle Pläne abrufen"
curl -s "$BASE_URL/" | jq '.' || echo "Keine JSON-Response"
echo
echo

# 2. Get specific plan
echo "2. GET /BASIC_BARCAMP - Spezifischen Plan abrufen"
curl -s "$BASE_URL/BASIC_BARCAMP" | jq '.data.meta' || echo "Fehler"
echo
echo

# 3. Create new plan
echo "3. POST / - Neuen Plan erstellen"
curl -s -X POST "$BASE_URL/" \
  -H "Content-Type: application/json" \
  -d '{
    "filename": "TEST_PLAN",
    "meta": {
      "title": "Test Barcamp",
      "date": "Montag, 15. Januar 2025",
      "location": "Teststadt"
    },
    "rooms": ["Raum 1", "Raum 2"],
    "slots": []
  }' | jq '.'
echo
echo

# 4. Get created plan
echo "4. GET /TEST_PLAN - Erstellten Plan abrufen"
curl -s "$BASE_URL/TEST_PLAN" | jq '.data.meta' || echo "Fehler"
echo
echo

# 5. Update plan
echo "5. PUT /TEST_PLAN - Plan aktualisieren"
curl -s -X PUT "$BASE_URL/TEST_PLAN" \
  -H "Content-Type: application/json" \
  -d '{
    "meta": {
      "title": "Aktualisierter Test Barcamp",
      "date": "Mittwoch, 20. Januar 2025",
      "location": "Aktualisierte Stadt"
    },
    "rooms": ["Raum 1", "Raum 2", "Raum 3"],
    "slots": []
  }' | jq '.'
echo
echo

# 6. Delete plan
echo "6. DELETE /TEST_PLAN - Plan löschen"
curl -s -X DELETE "$BASE_URL/TEST_PLAN" | jq '.'
echo
echo

# 7. Verify deletion
echo "7. GET /TEST_PLAN - Gelöschten Plan abrufen (sollte Fehler sein)"
curl -s "$BASE_URL/TEST_PLAN" | jq '.'
echo
echo

# 8. List all plans (should only have BASIC_BARCAMP)
echo "8. GET / - Alle Pläne abrufen (final)"
curl -s "$BASE_URL/" | jq '.data | map(.filename)'
echo
