<?php
require_once __DIR__ .'/router.php';


$path = PATH;
//error_log ($path . 'spirit/titles');
get($path, '/App/Views/indexLocal.php');
get($path . 'admin/exists', 'App/API/People/AdminExists.php');
get($path . 'cron/tips/first', 'App/API/Emails/EmailFirstTips.php');
get($path . 'cron/tips/next', 'App/API/Emails/EmailNextTips.php');
get($path . 'email/$id', 'App/API/Emails/GetEmail.php');
get($path . 'email/ad/recent/$number', 'App/API/Emails/AddRecentTitles.php');
get($path . 'email/blog/recent/$number', 'App/API/Emails/BlogRecentTitles.php');
get($path . 'email/series/$series/$sequence', 'App/API/Emails/SeriesEmailText.php');
get($path . 'email/view/$id', 'App/API/Emails/GetEmailView.php');
get($path . 'spirit/text/$language', 'App/API/Materials/getSpiritText.php');
get($path . 'spirit/titles', 'App/API/Materials/getSpiritTitles.php');
get($path . 'test', 'App/API/Materials/getTractsToView.php');
get($path . 'tracts/view', 'App/API/Materials/getTractsToView.php');

post($path . 'admin/create', 'App/API/People/AdminCreate.php', $postData);
post($path . 'admin/login', 'App/API/People/AdminLogin.php', $postData);
post($path . 'email/images', 'App/API/Emails/UploadImages.php');
post($path . 'email/images/upload', 'App/API/Emails/UploadImages.php');
post($path . 'email/images/upload/tinymce', 'App/API/Emails/UploadImagesTinyMce.php');
post($path . 'email/images/upload/tinymce2', 'App/API/Emails/UploadImagesTinyMce2.php');
post($path . 'email/que/emails', 'App/API/Emails/QueEmails.php',$postData);
post($path . 'email/send', 'App/API/Emails/SendEmail.php',$postData);
post($path . 'email/series', 'App/API/Emails/SeriesEmailTextUpdate.php',$postData);
post($path . 'materials/download', 'App/API/Materials/DownloadMaterialsUpdateUser.php', $postData);

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
