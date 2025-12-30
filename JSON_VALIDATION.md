# ✅ JSON-Validierung Fix

## Problem
- Das Admin-Frontend sendete das `filename`-Feld mit in die gespeicherte JSON-Datei
- Dies könnte zu teilweise invalid formatiertem JSON führen

## Lösung

### 1. Backend (SessionPlanRepository.php)
✅ **savePlan()**: Entfernt `filename`-Feld vor dem Speichern
```php
unset($data['filename']);
```

✅ **validatePlanData()**: Neue Validierungsfunktion
- Prüft, dass Daten als JSON encodierbar sind
- Verifiziert JSON-Struktur mit decode/re-encode

✅ **createPlan()** & **updatePlan()**: Rufen validatePlanData() auf

### 2. Frontend (admin.html)
✅ **savePlan()**: Sendet `filename` nur bei POST
```javascript
// Für POST: mit filename
requestData = { filename: filename, ...plan }

// Für PUT: ohne filename
requestData = plan
```

## Validierung ✓

Alle JSON-Dateien sind nun valides JSON:
```
BASIC_BARCAMP.json: ✓ Valid
TEST_VALID.json: ✓ Valid
```

## Vorher vs. Nachher

### Vorher (falsch):
```json
{
  "filename": "MEIN_PLAN",    // ❌ Sollte nicht da sein
  "meta": {...},
  "rooms": [...],
  "slots": [...]
}
```

### Nachher (richtig):
```json
{
  "meta": {...},              // ✓ Saubere Struktur
  "rooms": [...],
  "slots": [...]
}
```

## Test
```bash
# Neuen Plan erstellen und validieren
curl -X POST http://localhost:8000/api/ \
  -H "Content-Type: application/json" \
  -d '{"filename":"TEST","meta":{"title":"Test"},"rooms":[],"slots":[]}'

# Datei prüfen
jq . data/TEST.json
```

**Ergebnis**: ✓ Nur valides JSON wird gespeichert
