<?php
/**
 * Router für PHP Development Server
 * Leitet alle /api/* Anfragen zu index.php
 */

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Statische Dateien servieren (html, css, js, json, etc.)
if ($url !== '/' && file_exists(__DIR__ . $url)) {
    return false;
}

// Alles andere zu index.php
require_once __DIR__ . '/index.php';
?>
