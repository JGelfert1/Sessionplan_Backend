<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'config.php';
require_once 'src/SessionPlanRepository.php';

$repository = new SessionPlanRepository(DATA_DIR);

// Parse request
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/api/', '', $path);
$parts = array_filter(explode('/', $path));

// Route handling
try {
    if (empty($parts)) {
        if ($method === 'GET') {
            // List all plans
            $plans = $repository->listPlans();
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $plans]);
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
    } elseif ($method === 'POST' && empty($parts[0])) {
        // Create new plan
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['filename'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request: filename required']);
            exit;
        }
        $plan = $repository->createPlan($data['filename'], $data);
        http_response_code(201);
        echo json_encode(['success' => true, 'data' => $plan]);
    } elseif (count($parts) >= 1) {
        $filename = $parts[0];
        
        if ($method === 'GET') {
            // Get specific plan
            $plan = $repository->getPlan($filename);
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $plan]);
        } elseif ($method === 'PUT') {
            // Update specific plan
            $data = json_decode(file_get_contents('php://input'), true);
            $plan = $repository->updatePlan($filename, $data);
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $plan]);
        } elseif ($method === 'DELETE') {
            // Delete specific plan
            $repository->deletePlan($filename);
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Plan deleted']);
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Endpoint not found']);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
