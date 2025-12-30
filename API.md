# Sessionplan Backend API

PHP REST-API für die Verwaltung von Sessionplänen. Sessionpläne werden als JSON-Dateien im `data/`-Verzeichnis gespeichert.

## Anforderungen

- PHP 8.2+
- Schreibzugriff auf das `data/`-Verzeichnis

## Installation

1. Repository klonen
2. Dev Container starten (`.devcontainer/devcontainer.json`)
3. Server starten:
   ```bash
   php -S localhost:8000
   ```

## API-Endpoints

### GET `/api/` - Alle Pläne abrufen
```bash
curl http://localhost:8000/api/
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "filename": "BASIC_BARCAMP",
      "file": "BASIC_BARCAMP.json",
      "created": 1703001600,
      "size": 2048
    }
  ]
}
```

### GET `/api/{filename}` - Spezifischen Plan abrufen
```bash
curl http://localhost:8000/api/BASIC_BARCAMP
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "meta": {
      "title": "Barcamp Ruhr — Beispiel-Sessionplan A",
      "date": "Samstag, 10. Januar 2025",
      "location": "Kulturzentrum"
    },
    "rooms": ["Raum A", "Raum B", "Raum C"],
    "slots": [...]
  }
}
```

### POST `/api/` - Neuen Plan erstellen
```bash
curl -X POST http://localhost:8000/api/ \
  -H "Content-Type: application/json" \
  -d '{
    "filename": "MEIN_PLAN",
    "meta": {
      "title": "Mein Sessionplan",
      "date": "Montag, 15. Januar 2025",
      "location": "Berlin"
    },
    "rooms": ["Raum 1", "Raum 2"],
    "slots": []
  }'
```

**Response (201):**
```json
{
  "success": true,
  "data": {
    "filename": "MEIN_PLAN",
    "file": "MEIN_PLAN.json",
    "created": 1703001600,
    "size": 256
  }
}
```

### PUT `/api/{filename}` - Plan aktualisieren
```bash
curl -X PUT http://localhost:8000/api/BASIC_BARCAMP \
  -H "Content-Type: application/json" \
  -d '{
    "meta": {
      "title": "Aktualisierter Plan",
      "date": "Mittwoch, 20. Januar 2025",
      "location": "Köln"
    },
    "rooms": ["Raum A", "Raum B"],
    "slots": [...]
  }'
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "filename": "BASIC_BARCAMP",
    "file": "BASIC_BARCAMP.json",
    "updated": 1703001700,
    "size": 2100
  }
}
```

### DELETE `/api/{filename}` - Plan löschen
```bash
curl -X DELETE http://localhost:8000/api/BASIC_BARCAMP
```

**Response (200):**
```json
{
  "success": true,
  "message": "Plan deleted"
}
```

## Datenstruktur

Sessionpläne folgen dieser JSON-Struktur:

```json
{
  "meta": {
    "title": "Barcamp Name",
    "date": "Datum",
    "location": "Ort"
  },
  "rooms": ["Raum A", "Raum B"],
  "slots": [
    {
      "time": "09:00",
      "full_span": false,
      "cells": {
        "Raum A": {
          "title": "Session Title",
          "speaker": "Speaker Name",
          "tags": ["tag1", "tag2"],
          "badge": "Optional Badge"
        },
        "Raum B": null
      }
    }
  ]
}
```

### Felder

- **meta** (object): Metainformationen zum Sessionplan
  - `title` (string): Titel des Barcamps
  - `date` (string): Datum
  - `location` (string): Veranstaltungsort

- **rooms** (array): Liste der verfügbaren Räume

- **slots** (array): Zeitslots mit Sessions
  - `time` (string): Uhrzeit (z.B. "09:00")
  - `full_span` (boolean): Gilt die Session für alle Räume?
  - `cells` (object): Sessions pro Raum oder alle (`"all"`)
    - `title` (string): Titel der Session
    - `speaker` (string): Name des Speakers
    - `tags` (array): Liste von Tags
    - `badge` (string|null): Optionales Badge (z.B. "Plenum")

## Sicherheit

- Dateinamen-Validierung: Nur alphanumerische Zeichen, Unterstriche und Bindestriche
- Directory-Traversal-Schutz
- CORS-Header gesetzt
- Maximale Dateigröße: 5MB
- JSON-Validierung
