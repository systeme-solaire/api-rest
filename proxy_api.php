<?php
define("LOADED_AS_MODULE","1");
define('ALLOWED_DOMAIN', 'le-systeme-solaire.net');
include_once('include/apiconf.php');

/**
 * Vérifie que la requête provient du domaine autorisé
 */
function verifyDomain() {
    // Vérifier le referer
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $refererHost = parse_url($referer, PHP_URL_HOST);
    
    // Vérifier l'origine
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    $originHost = parse_url($origin, PHP_URL_HOST);
    
    // Vérifier le host
    $host = $_SERVER['HTTP_HOST'] ?? '';
    
    $allowedDomain = ALLOWED_DOMAIN;
    
    // Accepter le domaine principal et ses sous-domaines
    $isValidReferer = $refererHost === $allowedDomain || 
                      str_ends_with($refererHost, '.' . $allowedDomain);
    
    $isValidOrigin = $originHost === $allowedDomain || 
                     str_ends_with($originHost, '.' . $allowedDomain);
    
    $isValidHost = $host === $allowedDomain || 
                   str_ends_with($host, '.' . $allowedDomain);
    
    // Au moins une des vérifications doit passer
    if (!$isValidReferer && !$isValidOrigin && !$isValidHost) {
        http_response_code(403);
        die(json_encode(['error' => 'Accès interdit']));
    }
}

/**
 * Vérifie que la méthode est autorisée
 */
function verifyMethod() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        die(json_encode(['error' => 'Méthode non autorisée']));
    }
}

/**
 * Ajoute les en-têtes CORS pour le domaine autorisé
 */
function addCorsHeaders() {
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    $originHost = parse_url($origin, PHP_URL_HOST);
    $allowedDomain = ALLOWED_DOMAIN;
    
    // Vérifier que l'origine est autorisée
    if ($originHost === $allowedDomain || str_ends_with($originHost, '.' . $allowedDomain)) {
        header('Access-Control-Allow-Origin: ' . $origin);
    }
    
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Access-Control-Allow-Credentials: true');
}

// Vérifications de sécurité
verifyDomain();
verifyMethod();
addCorsHeaders();

try {
    // Récupération des paramètres selon la méthode
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input) {
            $lat = isset($input['lat']) ? (float)$input['lat'] : $lat;
            $lon = isset($input['lon']) ? (float)$input['lon'] : $lon;
            $elev = isset($input['elev']) ? (float)$input['elev'] : $elev;
            $zone = isset($input['zone']) ? (float)$input['zone'] : $zone;
            $datetime = isset($input['datetime']) ? $input['datetime'] : $datetime;
        }
    } else {
        // Méthode GET
        $lat = isset($_GET['lat']) ? (float)$_GET['lat'] : $lat;
        $lon = isset($_GET['lon']) ? (float)$_GET['lon'] : $lon;
        $elev = isset($_GET['elev']) ? (float)$_GET['elev'] : $elev;
        $zone = isset($_GET['zone']) ? (float)$zone : $zone;
        $datetime = isset($_GET['datetime']) ? $_GET['datetime'] : $datetime;
    }
    
    // Validation des paramètres
    if ($lat < -90 || $lat > 90) {
        throw new Exception("Latitude invalide: {$lat} (doit être entre -90 et 90)");
    }
    if ($lon < -180 || $lon > 180) {
        throw new Exception("Longitude invalide: {$lon} (doit être entre -180 et 180)");
    }
    
    // Validation du format datetime (ISO 8601 basique)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}$/', $datetime)) {
        throw new Exception("Format de date invalide: {$datetime}. Utilisez YYYY-MM-DDTHH:MM:SS");
    }
    
    // Construction de l'URL avec paramètres
    $params = http_build_query([
        'lat' => $lat,
        'lon' => $lon,
        'elev' => $elev,
        'datetime' => $datetime,
        'zone' => $zone
    ]);
    
    $apiUrl = API_URL . '?' . $params;
    
    // Configuration de la requête cURL
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . API_TOKEN
        ]
    ]);
    
    // Exécution de la requête
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    // Vérification des erreurs cURL
    if ($curlError) {
        throw new Exception("Erreur de connexion à l'API: " . $curlError);
    }
    
    // Gestion des codes de réponse HTTP
    if ($httpCode === 401) {
        throw new Exception("Token d'authentification invalide ou expiré");
    } elseif ($httpCode === 403) {
        throw new Exception("Accès non autorisé à l'API");
    } elseif ($httpCode === 429) {
        throw new Exception("Limite de requêtes dépassée - veuillez réessayer plus tard");
    } elseif ($httpCode >= 400) {
        throw new Exception("Erreur API: HTTP {$httpCode}");
    }
    
    // Vérification que la réponse est du JSON valide
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Réponse API invalide: " . json_last_error_msg());
    }
    
    // Ajout de métadonnées de proxy
    if (is_array($data)) {
        $data['proxy'] = [
            'version' => '1.0',
            'timestamp' => date('Y-m-d H:i:s'),
            'processed_at' => $_SERVER['HTTP_HOST'] ?? 'localhost'
        ];
    }
    
    // Retour de la réponse
    http_response_code($httpCode);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {

}
?>