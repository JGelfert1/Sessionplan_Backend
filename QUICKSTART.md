# 🚀 Schnellstart Sessionplan Admin

## Schritt 1: Server starten

```bash
cd /workspaces/Sessionplan_Backend
bash start.sh

# Oder direkt:
# php -S localhost:8000
```

## Schritt 2: Admin-Interface öffnen

Öffne im Browser: **http://localhost:8000/admin.html**

## Schritt 3: Sessionpläne verwalten

### Neuen Plan erstellen
1. Klicke "➕ Neuer Plan"
2. Gib einen **Dateinamen** ein (z.B. `MEINE_KONFERENZ`)
3. Trage Metadaten ein:
   - Titel: z.B. "Tech Konferenz 2025"
   - Datum: z.B. "15. März 2025"
   - Ort: z.B. "Berlin"
4. Füge **Räume** hinzu (z.B. "Hauptsaal", "Workshop A")
5. Füge **Zeitslots** hinzu (z.B. "09:00", "10:30")
6. Klicke **💾 Speichern**

### Bestehenden Plan bearbeiten
1. Wähle Plan in der Sidebar
2. Bearbeite die Felder
3. Klicke **💾 Speichern**

### Plan löschen
1. Öffne den Plan
2. Klicke **🗑️ Löschen** (nur im Edit-Modus)
3. Bestätige die Löschung

## 📁 Wo landen die Dateien?

Alle Pläne werden gespeichert in:
```
data/
├── BASIC_BARCAMP.json
├── MEINE_KONFERENZ.json
└── ...
```

Diese Dateien sind **Teil des Git-Repositories**!

## 🔄 Pläne mit Git synchronisieren

```bash
# Neue Pläne hinzufügen
git add data/
git commit -m "Neue Sessionpläne hinzugefügt"
git push

# Oder alle Änderungen
git add .
git commit -m "Updates"
git push
```

## 🌐 API testen

```bash
# Alle Pläne abrufen
curl http://localhost:8000/api/ | jq .

# Spezifischen Plan abrufen
curl http://localhost:8000/api/BASIC_BARCAMP | jq .

# Mit Test-Script (curl + jq erforderlich)
bash test-api.sh
```

## 🎨 Frontend mit Backend verbinden

Im Original-Frontend ([JGelfert1/Sessionplan](https://github.com/JGelfert1/Sessionplan)) kannst du die PLANS-Variable so anpassen:

```javascript
// In index.html:
const PLANS = [
  {name: 'Barcamp Beispiel A', file: 'http://localhost:8000/api/BASIC_BARCAMP'},
  {name: 'Meine Konferenz', file: 'http://localhost:8000/api/MEINE_KONFERENZ'}
];
```

## ✅ Checklist

- [x] Server läuft auf http://localhost:8000
- [x] Admin-Frontend erreichbar unter http://localhost:8000/admin.html
- [x] API funktioniert unter http://localhost:8000/api/
- [x] Sessionpläne werden im `data/`-Verzeichnis gespeichert
- [x] Dateien sind im Git-Repository trackbar
- [x] CRUD-Operationen funktionieren

## 🔗 Wichtige Links

- 📄 **Admin Frontend:** http://localhost:8000/admin.html
- 🔌 **API Root:** http://localhost:8000/api/
- 📖 **API Dokumentation:** [API.md](API.md)
- 📋 **README:** [README.md](README.md)

## ⚠️ Häufige Probleme

### "Fehler beim Laden der Pläne"
- Prüfe, ob der Server läuft: `ps aux | grep php`
- Prüfe die Server-Logs: `tail -f /tmp/server.log`

### JSON-Datei hat Fehler
- Prüfe die Datei manuell: `cat data/MEIN_PLAN.json | jq .`
- Überprüfe auf Syntaxfehler

### Plan wird nicht gespeichert
- Prüfe die Dateinamens-Validierung (nur Buchstaben, Zahlen, `-`, `_`)
- Prüfe die File-Permissions: `ls -la data/`

## 💡 Tipps & Tricks

### Alle Pläne auf einmal exportieren
```bash
tar -czf sessionplans_backup.tar.gz data/
```

### Plan-Struktur aus Vorlage kopieren
```bash
cp data/BASIC_BARCAMP.json data/NEUE_VORLAGE.json
# Dann im Admin-Frontend bearbeiten
```

### Server-Logs live verfolgen
```bash
tail -f /tmp/server.log
```

Viel Spaß beim Verwalten deiner Sessionpläne! 🎉
