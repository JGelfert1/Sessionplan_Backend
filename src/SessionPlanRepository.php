<?php
class SessionPlanRepository
{
    private $dataDir;

    public function __construct($dataDir)
    {
        $this->dataDir = $dataDir;
        if (!is_dir($this->dataDir)) {
            mkdir($this->dataDir, 0755, true);
        }
    }

    /**
     * List all session plans
     */
    public function listPlans()
    {
        $plans = [];
        if (!is_dir($this->dataDir)) {
            return $plans;
        }

        $files = glob($this->dataDir . '/*.json');
        foreach ($files as $file) {
            $basename = basename($file, '.json');
            $plans[] = [
                'filename' => $basename,
                'file' => basename($file),
                'created' => filemtime($file),
                'size' => filesize($file)
            ];
        }

        // Sort by creation time descending
        usort($plans, function ($a, $b) {
            return $b['created'] - $a['created'];
        });

        return $plans;
    }

    /**
     * Get specific plan by filename
     */
    public function getPlan($filename)
    {
        $path = $this->validateFilename($filename);

        if (!file_exists($path)) {
            throw new Exception("Plan not found: $filename");
        }

        $content = file_get_contents($path);
        if ($content === false) {
            throw new Exception("Failed to read plan file");
        }

        $data = json_decode($content, true);
        if ($data === null) {
            throw new Exception("Invalid JSON in plan file");
        }

        return $data;
    }

    /**
     * Create new plan
     */
    public function createPlan($filename, $data)
    {
        $path = $this->validateFilename($filename);

        if (file_exists($path)) {
            throw new Exception("Plan already exists: $filename");
        }

        // Validate data structure
        $this->validatePlanData($data);

        if (!isset($data['meta'])) {
            $data['meta'] = [];
        }

        if (!isset($data['rooms'])) {
            $data['rooms'] = [];
        }

        if (!isset($data['slots'])) {
            $data['slots'] = [];
        }

        $this->savePlan($path, $data);

        return [
            'filename' => $filename,
            'file' => basename($path),
            'created' => time(),
            'size' => filesize($path)
        ];
    }

    /**
     * Update existing plan
     */
    public function updatePlan($filename, $data)
    {
        $path = $this->validateFilename($filename);

        if (!file_exists($path)) {
            throw new Exception("Plan not found: $filename");
        }

        // Validate data structure
        $this->validatePlanData($data);

        // Validate required fields
        if (!isset($data['meta'])) {
            $data['meta'] = [];
        }

        if (!isset($data['rooms'])) {
            $data['rooms'] = [];
        }

        if (!isset($data['slots'])) {
            $data['slots'] = [];
        }

        $this->savePlan($path, $data);

        return [
            'filename' => $filename,
            'file' => basename($path),
            'updated' => time(),
            'size' => filesize($path)
        ];
    }

    /**
     * Delete plan
     */
    public function deletePlan($filename)
    {
        $path = $this->validateFilename($filename);

        if (!file_exists($path)) {
            throw new Exception("Plan not found: $filename");
        }

        if (!unlink($path)) {
            throw new Exception("Failed to delete plan");
        }

        return true;
    }

    /**
     * Save plan to file
     */
    private function savePlan($path, $data)
    {
        // Remove filename field if present (should not be in the saved data)
        unset($data['filename']);

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new Exception("Failed to encode JSON");
        }

        if (strlen($json) > MAX_FILE_SIZE) {
            throw new Exception("File too large (max " . (MAX_FILE_SIZE / 1024 / 1024) . "MB)");
        }

        if (file_put_contents($path, $json) === false) {
            throw new Exception("Failed to write plan file");
        }

        // Set file permissions
        chmod($path, 0644);
    }

    /**
     * Validate and sanitize filename
     */
    private function validateFilename($filename)
    {
        // Remove .json extension if present
        $filename = preg_replace('/\.json$/i', '', $filename);

        // Sanitize filename - only allow alphanumeric, underscore, hyphen
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $filename)) {
            throw new Exception("Invalid filename format");
        }

        // Prevent directory traversal
        if (strpos($filename, '..') !== false || strpos($filename, '/') !== false) {
            throw new Exception("Invalid filename");
        }

        return $this->dataDir . '/' . $filename . '.json';
    }

    /**
     * Validate plan data structure
     */
    private function validatePlanData($data)
    {
        if (!is_array($data)) {
            throw new Exception("Plan data must be an array");
        }

        // Check if data can be JSON encoded
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new Exception("Plan data cannot be JSON encoded: " . json_last_error_msg());
        }

        // Verify JSON can be decoded back
        $decoded = json_decode($json, true);
        if ($decoded === null) {
            throw new Exception("Invalid JSON structure in plan data");
        }

        return true;
    }
}
