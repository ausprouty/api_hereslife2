<?php

error_reporting(E_ALL);
// Set CORS headers
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
/***************************************************
 * Only these origins are allowed to upload images *
 ***************************************************/
$accepted_origins = array(
    "http://localhost:5173",
    "http://localhost:5173/",
    "http://localhost",
    "https://hereslife.com",
    "https://api.hereslife.com",
    "https://axd.5f8.myftpupload.com"
);
// Handle preflight requests
error_log("\n\n" . $_SERVER['HTTP_ORIGIN'] . "\n\n");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    error_log('options');
    if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
        //error_log('setting allowed origin in options');
        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        header('Access-Control-Allow-Credentials: true');
        header("HTTP/1.1 200 OK");
        exit;
    } else {
        error_log('options says forbidden');
        header("HTTP/1.1 403 Forbidden Source");
        exit;
    }
}
error_log("continuing after options\n\n");
require_once  __DIR__.'/App/Configuration/my-autoload.inc.php';
require_once  __DIR__.'/Vendor/autoload.php';
$dir = __DIR__ ;
error_log($dir  );
///home/hereslife/api.hereslife.com/backend
if ( $dir == '/home/hereslife/api.hereslife.com/backend'){
    error_log('I want to include remote');
    require_once $dir .'/App/Configuration/.env.remote.php';
    $location = 'remote';
    
}
else{
    require_once $dir .'/App/Configuration/.env.local.php';
    
    $location = 'local';
}
error_log($location);
require_once __DIR__ .'/router.php';
require_once __DIR__.'/App/Includes/writeLog.php';

error_log('SERVER: ' . print_r($_SERVER, true));
$headers = apache_request_headers();
if (isset($headers['Authorization'])) {
    writelog('Routes-37','Authorization');
    $authHeader = $headers['Authorization'];
    $token = str_replace('Bearer ', '', $authHeader); // Extract the token
    writelog('Routes-40','Token: ' . $token);
    // Proceed with token validation and request processing
}  

$path = PATH;
//error_log ($path . 'spirit/titles');
get($path, '/App/Views/indexLocal.php');
get($path . 'admin/exists', 'App/API/People/AdminExists.php');
get($path . 'email/$id', 'App/API/Emails/GetEmail.php');
get($path . 'email/ad/recent/$number', 'App/API/Emails/AddRecentTitles.php');
get($path . 'email/blog/recent/$number', 'App/API/Emails/BlogRecentTitles.php');
get($path . 'email/series/$series/$sequence', 'App/API/Emails/SeriesEmailText.php');
get($path . 'email/view/$id', 'App/API/Emails/GetEmailView.php');
get($path . 'spirit/text/$language', 'App/API/Materials/getSpiritText.php');
get($path . 'spirit/titles', 'App/API/Materials/getSpiritTitles.php');
get($path . 'test', 'App/API/Materials/getTractsToView.php');
get($path . 'tracts/view', 'App/API/Materials/getTractsToView.php');

post($path . 'admin/create', 'App/API/People/AdminCreate.php');
post($path . 'admin/login', 'App/API/People/AdminLogin.php');
post($path . 'email/images', 'App/API/Emails/UploadImages.php');
post($path . 'email/images/upload', 'App/API/Emails/UploadImages.php');
post($path . 'email/images/upload/tinymce', 'App/API/Emails/UploadImagesTinyMce.php');
post($path . 'email/images/upload/tinymce2', 'App/API/Emails/UploadImagesTinyMce2.php');
post($path . 'email/que/emails', 'App/API/Emails/QueEmails.php');
post($path . 'email/send', 'App/API/Emails/SendEmail.php');
post($path . 'email/series', 'App/API/Emails/SeriesEmailTextUpdate.php');
post($path . 'materials/download', 'App/API/Materials/DownloadMaterialsUpdateUser.php');

if (ENVIRONMENT == 'local'){
    get($path . 'test/spirit/titles', 'App/Tests/canGetSpiritTitlesByLanguage.php');
    get($path . 'test/tracts/view', 'App/Tests/canGetTractsToView.php');
    get($path . 'test/tracts/monolingual', 'App/Tests/canGetTractsMonolingual.php');
    get($path . 'test/tracts/bilingual/english', 'App/Tests/canGetTractsBilingualEnglish.php');
    get($path . '/rcd/test', 'App/Tests/canAccessFromWordPress.php');
}

any($path . '*', function() {
    header("HTTP/1.1 404 Not Found");
    echo "404 Not Found: The requested resource could not be found.";
    exit;
});
