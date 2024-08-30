<?php


/*********************************************
 * Change this line to set the upload folder *
 *********************************************/

$imageFolder = ROOT_IMAGES_EMAILS; // This could be passed from a configuration or environment variable
$uploadUrl = URL_IMAGES_EMAILS;
writeLog('UploadImagesTinyMce-11', print_r($_POST, true));
error_reporting(E_ALL);
error_log('origin ' . $_SERVER['HTTP_ORIGIN']);
error_log('Raw Input: ' . file_get_contents('php://input'));

error_log('Post: ' . print_r($_POST, true));
reset ($_FILES);
$temp = current($_FILES);
if (is_uploaded_file($temp['tmp_name'])){
  error_log('UploadImagesTinyMce-15: ' . $temp['tmp_name']);

  /*
    If your script needs to receive cookies, set images_upload_credentials : true in
    the configuration and enable the following two headers.
  */
   header('Access-Control-Allow-Credentials: true');
   header('P3P: CP="There is no P3P policy."');

  // Sanitize input
  if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
    error_log('UploadImagesTinyMce-25: invalied file name');  
    header("HTTP/1.1 400 Invalid file name.");
      return;
  }

  // Verify extension
  if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
    error_log ('UploadImagesTinyMce-32: invalied file extension');
    header("HTTP/1.1 400 Invalid extension.");
      return;
  }
  $filename = $temp['name'];
  $suffix = strrpos($filename, '.');
  $filename = substr($filename, 0, $suffix) . '_' . time() . substr($filename, $suffix);
  $filename = str_replace(' ', '_', $filename); 
  // Accept upload if there was no origin, or if it is an accepted origin
  $filetowrite = $imageFolder . $filename;
  move_uploaded_file($temp['tmp_name'], $filetowrite);

  // Determine the base URL
  $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? "https://" : "http://";
  $baseurl = $protocol . $_SERVER["HTTP_HOST"] . rtrim(dirname($_SERVER['REQUEST_URI']), "/") . "/";

  // Respond to the successful upload with JSON.
  // Use a location key to specify the path to the saved image resource.
  // { location : '/your/uploaded/image/file'}
  error_log('UploadImagesTinyMce-50: '. $uploadUrl .'/'. $filename);
  echo json_encode(array('location' => $uploadUrl .'/'. $filename));
} else {
  error_log('UploadImagesTinyMce-53: sever error');
  // Notify editor that the upload failed
  header("HTTP/1.1 500 Server Error");
}
