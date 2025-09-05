<?php
define("LOADED_AS_MODULE","1");
include_once('include/dbaccess.php');
$GLOBALS['DEBUG']=0;

if (isset($_POST['lang'])) {
    $lang = $_POST['lang'];
}
$en=false;
if ($lang=="en"){$en=true;}

DBAccess::ConfigInit();

// Fonction GUID
function generateGUID() {
    if (function_exists('com_create_guid')) {
        return trim(com_create_guid(), '{}');
    }
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

session_start();

// Vérification du captcha
$captchaInput = $_POST['captcha'] ?? '';
$captchaData  = $_SESSION['captcha'] ?? null;

if (!$captchaData) {
    if ($en) {
        echo "❌ Missing Captcha. Reload the page.";
    }else{
        echo "❌ Captcha manquant. Rechargez la page.";
    }
    exit;
}

// Vérifier expiration (2 minutes = 120 secondes)
if (time() - $captchaData['time'] > 600) {
    unset($_SESSION['captcha']);
    if ($en) {
        echo "❌ Expired Captcha. Click on captcha to reload a new one.";
    }else{
        echo "❌ Captcha expiré. Cliquez sur l'image pour en générer un nouveau.";
    }
    exit;
}

// Vérifier code
if (strtolower($captchaInput) !== strtolower($captchaData['code'])) {
    unset($_SESSION['captcha']);
    if ($en) {
        echo "❌ Invalid Captcha. Please retry.";
    }else{
        echo "❌ Captcha invalide. Veuillez réessayer.";
    }
    exit;
}

// OK → supprimer le captcha pour éviter réutilisation
unset($_SESSION['captcha']);

// Récupérer email
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
if (!$email) {
    if ($en) {
        echo "❌ Invalid email";
    }else{
        echo "❌ Email invalide";
    }
    exit;
}

// Générer clé
$apiKey = generateGUID();

// Sauvegarde en BDD
$stmt = $GLOBALS['BDD']->prepare("INSERT INTO syssol_tab_api_keys (email, api_key, is_validated) VALUES (?, ?, 0)
                       ON DUPLICATE KEY UPDATE api_key = VALUES(api_key), is_validated = 0");
$stmt->execute([$email, $apiKey]);

// Envoyer email avec lien de validation
$headers = 'From: api@le-systeme-solaire.net' . "\r\n" .
    'Reply-To: api@le-systeme-solaire.net';

    if ($en) {
        $validationUrl = "https://api.le-systeme-solaire.net/validate.php?lang=en&key=" . urlencode($apiKey);
        mail($email, "Your API key for the Solar system OpenData", "Thx for using the Solar system OpenData. Click here to validate your token : " . $validationUrl, $headers);
        echo "✅ An email was sended to you.";
    }else{
        $validationUrl = "https://api.le-systeme-solaire.net/validate.php?lang=fr&key=" . urlencode($apiKey);
        mail($email, "Votre clé API pour l'OpenData du système solaire", "Merci d'utiliser l'Opendata du système solaire. Pour valider votre clé veuillez cliquer ici : " . $validationUrl, $headers);
        echo "✅ Un email de validation vous a été envoyé.";
    }
exit;
?>