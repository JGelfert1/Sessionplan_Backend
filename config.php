<?php
// Configuration
define('DATA_DIR', __DIR__ . '/data');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ADMIN_PASSWORD_HASH', '$2y$10$mg5qJYTcsyu6gn.jTM3vhukYo0rFvtfAjU/t7FJBCUMrMKd3m.nFG');

// Create data directory if it doesn't exist
if (!is_dir(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}
