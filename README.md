# Sessionplan Backend

PHP REST-API für die Verwaltung von Barcamp-Sessionplänen.

**Features:**
- ✅ CRUD-Operationen für Sessionpläne
- ✅ JSON-Speicherung im `data/`-Verzeichnis
- ✅ Keine Datenbankabhängigkeit
- ✅ CORS-Unterstützung
- ✅ Eingabevalidierung & Sicherheit
- ✅ Responsive API

## Quick Start

### Mit Dev Container

```bash
# VS Code
# 1. Öffne den Workspace
# 2. Klicke "Reopen in Container" oder nutze Command Palette (Ctrl+Shift+P)
#    → "Dev Containers: Reopen in Container"

# Server startet auf http://localhost:8000
```

### Lokal (ohne Container)

```bash
# Voraussetzung: PHP 8.2+
cd /workspaces/Sessionplan_Backend
php -S localhost:8000
```

## API Dokumentation

Siehe [API.md](API.md) für ausführliche Dokumentation.

**Beispiel-Anfragen:**

```bash
# Alle Pläne abrufen
curl http://localhost:8000/api/

# Plan erstellen
curl -X POST http://localhost:8000/api/ \
  -H "Content-Type: application/json" \
  -d '{"filename":"MEIN_PLAN","meta":{"title":"Mein Plan"},"rooms":[],"slots":[]}'

# Plan abrufen
curl http://localhost:8000/api/BASIC_BARCAMP

# Plan aktualisieren
curl -X PUT http://localhost:8000/api/BASIC_BARCAMP \
  -H "Content-Type: application/json" \
  -d '{"meta":{"title":"Neuer Titel"},"rooms":[],"slots":[]}'

# Plan löschen
curl -X DELETE http://localhost:8000/api/BASIC_BARCAMP
```

## API Tests

```bash
# Test-Script ausführen (erfordert curl, jq)
bash test-api.sh

# Oder manuell mit curl testen
curl http://localhost:8000/api/
```

## Verzeichnisstruktur

```
Sessionplan_Backend/
├── index.php                 # API Entry Point
├── config.php               # Konfiguration
├── src/
│   └── SessionPlanRepository.php  # CRUD-Logik
├── data/                    # JSON-Dateien (auto-created)
│   └── BASIC_BARCAMP.json   # Beispiel-Plan
├── .devcontainer/
│   └── devcontainer.json    # Dev Container Config
├── API.md                   # API Dokumentation
├── README.md                # Dieses Dokument
└── test-api.sh             # Test-Script
```

## Integrierung mit Frontend

Das Frontend (`JGelfert1/Sessionplan`) lädt JSON-Dateien über `fetch()`:

```javascript
const res = await fetch('data/template/BASIC_BARCAMP.json');
const data = await res.json();
```

Das Backend kann mit folgendem Setup integriert werden:

1. **Frontend-API auf Backend umleiten:**

```javascript
// Im Frontend (index.html)
const PLANS = [
  {name: 'Plan A', file: 'http://localhost:8000/api/BASIC_BARCAMP'}
];
```

2. **Oder Backend-Pläne mit eigenem Server servieren:**

```bash
# Backend-Server
php -S localhost:8000

# Frontend-Server (in separatem Terminal)
php -S localhost:8001

# Frontend kann über CORS auf Backend zugreifen
```

## Sicherheitsfeatures

- ✅ Eingabevalidierung (Dateinamen)
- ✅ Directory-Traversal-Schutz
- ✅ JSON-Validierung
- ✅ Maximale Dateigröße: 5MB
- ✅ CORS-Header

## Entwicklung

Siehe [API.md](API.md) für:
- Detaillierte API-Dokumentation
- Datenstruktur-Spezifikation
- Weitere Beispiele

## Lizenz

Siehe [LICENSE](LICENSE)
