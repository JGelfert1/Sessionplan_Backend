# Sessionplan Backend

PHP REST-API für die Verwaltung von Barcamp-Sessionplänen.

**Features:**
- ✅ CRUD-Operationen für Sessionpläne
- ✅ JSON-Speicherung im `data/`-Verzeichnis (im Git Repository)
- ✅ Admin-Frontend zur Verwaltung
- ✅ Keine Datenbankabhängigkeit
- ✅ CORS-Unterstützung
- ✅ Eingabevalidierung & Sicherheit
- ✅ Responsive API

## 🚀 Quick Start

### Mit dem Start-Script (empfohlen)

```bash
cd /workspaces/Sessionplan_Backend
bash start.sh

# Dann öffnen:
# Admin: http://localhost:8000/admin.html
# API: http://localhost:8000/api
```

### Lokal ohne Script

```bash
cd /workspaces/Sessionplan_Backend
php -S localhost:8000
```

## 📍 Datenspeicherung

✅ **Alle Sessionpläne werden im Repository gespeichert:**

```
data/
├── .gitkeep                    # Ordner ist Teil des Repos
├── BASIC_BARCAMP.json         # Beispiel-Plan (trackt von Git)
├── MEIN_PLAN.json             # Mit Admin erstellter Plan (auto. trackt)
└── ...weitere Pläne...
```

**Wichtig:** Die JSON-Dateien im `data/`-Verzeichnis werden vom Backend automatisch erstellt und gespeichert. Sie sind Teil des Git-Repositories und können committed werden.

```bash
# Beispiel: Neue Pläne sind im Repo sichtbar
git status
# On branch main
# Untracked files:
#   data/MEIN_NEUER_PLAN.json
#   data/ANDERER_PLAN.json

git add data/
git commit -m "Neue Sessionpläne hinzugefügt"
```

## 🎨 Admin-Frontend

Öffne **http://localhost:8000/admin.html** im Browser

### Was du tun kannst:
- **📋 Pläne auswählen** - Sidebar zeigt alle gespeicherten Pläne
- **➕ Neue Pläne erstellen** - "Neuer Plan" Button
- **✏️ Pläne bearbeiten** - Metadaten, Räume, Zeitslots
- **💾 Speichern** - Auto-Speicherung im `data/` Verzeichnis
- **🗑️ Löschen** - Plan entfernen

## 🔌 API Dokumentation

Siehe [API.md](API.md) für ausführliche Dokumentation.

### Schnelle Beispiele

```bash
# Alle Pläne abrufen
curl http://localhost:8000/api/

# Plan auslesen
curl http://localhost:8000/api/BASIC_BARCAMP

# Neuen Plan erstellen
curl -X POST http://localhost:8000/api/ \
  -H "Content-Type: application/json" \
  -d '{
    "filename": "KONFERENZ_2025",
    "meta": {
      "title": "Tech Konferenz 2025",
      "date": "15. März 2025",
      "location": "Berlin"
    },
    "rooms": ["Hauptsaal", "Workshop A", "Workshop B"],
    "slots": []
  }'

# Plan aktualisieren
curl -X PUT http://localhost:8000/api/KONFERENZ_2025 \
  -H "Content-Type: application/json" \
  -d '{"meta":{"title":"Neue Title"},"rooms":[],"slots":[]}'

# Plan löschen
curl -X DELETE http://localhost:8000/api/KONFERENZ_2025
```

## 🧪 API Tests

```bash
# Mit Test-Script
bash test-api.sh

# Oder manuell
curl http://localhost:8000/api/ | jq .
```

## 📁 Verzeichnisstruktur

```
Sessionplan_Backend/
├── admin.html                  # Admin-Frontend (öffne im Browser!)
├── index.php                   # API Entry Point
├── config.php                  # Konfiguration
├── src/
│   └── SessionPlanRepository.php    # CRUD-Logik
├── data/                       # 📍 SESSIONPLÄNE (im Git!)
│   ├── .gitkeep
│   └── *.json
├── .devcontainer/
│   └── devcontainer.json       # Dev Container Setup
├── .htaccess                   # Routing für Apache
├── API.md                      # API-Dokumentation
├── README.md                   # Dieses Dokument
├── start.sh                    # Start-Script
├── test-api.sh                 # Test-Script
└── .gitignore
```

## 🔒 Sicherheit

- ✅ Eingabevalidierung (Dateinamen)
- ✅ Directory-Traversal-Schutz
- ✅ JSON-Validierung
- ✅ Maximale Dateigröße: 5MB
- ✅ CORS-Header
- ✅ Sanitized HTML Output

## 🌍 Frontend-Integration

Das Original-Frontend (`JGelfert1/Sessionplan`) kann mit diesem Backend integriert werden:

```javascript
// Im Frontend (index.html) anpassen:
const PLANS = [
  {name: 'Plan A', file: 'http://localhost:8000/api/BASIC_BARCAMP'},
  {name: 'Plan B', file: 'http://localhost:8000/api/KONFERENZ_2025'}
];
```

## 💡 Tipps

### Pläne nach Git committen
```bash
cd /workspaces/Sessionplan_Backend
git add data/
git commit -m "Neue/aktualisierte Sessionpläne"
git push
```

### Backup erstellen
```bash
cp -r data/ data.backup_$(date +%Y%m%d)
```

### Alle Pläne löschen (lokal)
```bash
rm data/*.json  # Nur JSON-Dateien, .gitkeep bleibt
```

## 🛠️ Entwicklung

### PHP Server mit Debug
```bash
php -S localhost:8000 -d display_errors=1
```

### VS Code REST Client testen
Erstelle `test.http`:
```http
@baseUrl = http://localhost:8000/api

### Get all plans
GET {{baseUrl}}/

### Create plan
POST {{baseUrl}}/
Content-Type: application/json

{...}
```

## Lizenz

Siehe [LICENSE](LICENSE)
