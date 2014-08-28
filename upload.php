<?php

error_reporting(E_ALL);

// we first include the upload class, as we will need it here to deal with the uploaded file
include('plugins/class.upload_0.32/class.upload.php');

// retrieve eventual CLI parameters
$cli = (isset($argc) && $argc > 1);
if ($cli) {
	if (isset($argv[1])) $_GET['file'] = $argv[1];
	if (isset($argv[2])) $_GET['dir'] = $argv[2];
	if (isset($argv[3])) $_GET['pics'] = $argv[3];
}

// set variables
$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : 'images');
$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);

if (!$cli && !(isset($_SERVER['HTTP_X_FILE_NAME']) && isset($_SERVER['CONTENT_LENGTH']))) {

}

// we have three forms on the test page, so we redirect accordingly
if ((isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '')) == 'xhr') {

	// ---------- XMLHttpRequest UPLOAD ----------
		
	// we first check if it is a XMLHttpRequest call
	if (isset($_SERVER['HTTP_X_FILE_NAME']) && isset($_SERVER['CONTENT_LENGTH'])) {
	
		// we create an instance of the class, feeding in the name of the file
		// sent via a XMLHttpRequest request, prefixed with 'php:'
		$handle = new Upload('php:'.$_SERVER['HTTP_X_FILE_NAME']);
	
	} else {	
		// we create an instance of the class, giving as argument the PHP object
		// corresponding to the file field from the form
		// This is the fallback, using the standard way
		$handle = new Upload($_FILES['file']);
	}
	
	
	// then we check if the file has been uploaded properly
	// in its *temporary* location in the server (often, it is /tmp)
	if ($handle->uploaded) {

		// yes, the file is on the server
		// below are some example settings which can be used if the uploaded file is an image.
		$handle->image_resize			= true;
		$handle->image_ratio_y			= true;
		$handle->image_x					= 400;
		$handle->file_name_body_pre		= 'thumb-';
		$handle->image_convert			= 'jpg';
		// $handle->file_new_name_body	 = 'foto';
		// $handle->file_overwrite		 = true;
		// $handle->image_ratio_crop		 = true;
		// $handle->image_y				 = 145;
		// $handle->image_x				 = 170;
		// $handle->jpeg_quality			 = 100;

		// now, we start the upload 'process'. That is, to copy the uploaded file
		// from its temporary location to the wanted location
		// It could be something like $handle->Process('/home/www/my_uploads/');
		$handle->Process($dir_dest);

		// we check if everything went OK
		if ($handle->processed) {
			// everything was fine !
			$data['$thumbnail'] = $dir_pics . '/' . $handle->file_dst_name;
			//echo '<p class="result">';
			//echo '  <b>File uploaded with success smoke</b><br />';
			//echo '  <img src="'.$dir_pics.'/' . $handle->file_dst_name . '" />';
			//$info = getimagesize($handle->file_dst_pathname);
			//echo '  File: <a href="'.$dir_pics.'/' . $handle->file_dst_name . '">' . $handle->file_dst_name . '</a><br/>';
			//echo '   (' . $info['mime'] . ' - ' . $info[0] . ' x ' . $info[1] .' -  ' . round(filesize($handle->file_dst_pathname)/256)/4 . 'KB)';
			//echo '</p>';
		} else {
			// one error occured
			// echo '<p class="result">';
			// echo '  <b>File not uploaded to the wanted location</b><br />';
			// echo '  Error: ' . $handle->error . '';
			// echo '</p>';
			$data['$error'] = 'File not uploaded to the wanted location' . $handle->error;
		}


		// we now process the image a second time, with some other settings
		$handle->image_resize			= true;
		$handle->image_ratio_y			= true;
		$handle->image_x					= 1200;
		$handle->image_convert			= 'jpg';

		$handle->Process($dir_dest);

		// we check if everything went OK
		if ($handle->processed) {
			// everything was fine !
			$data['$img'] = $dir_pics . '/' . $handle->file_dst_name;

		} else {
			// one error occured
			$data['$error'] = 'File not uploaded to the wanted location' . $handle->error;
		}

	} else {
		// if we're here, the upload file failed for some reasons
		// i.e. the server didn't receive the file
		// echo '<p class="result">';
		// echo '  <b>File not uploaded on the server</b><br />';
		// echo '  Error: ' . $handle->error . '';
		// echo '</p>';
		$data['$error'] = 'File not uploaded on the server' . $handle->error;
	}


		// now, we start the upload 'process'. That is, to copy the uploaded file
		// from its temporary location to the wanted location
		// It could be something like $handle->Process('/home/www/my_uploads/');
		// $handle->Process($dir_dest);

		// // we check if everything went OK
		// if ($handle->processed) {
		//	  // everything was fine !
		//	  echo '<p class="result">';
		//	  echo '  <b>File uploaded with success</b><br />';
		//	  echo '  File: <a href="'.$dir_pics.'/' . $handle->file_dst_name . '">' . $handle->file_dst_name . '</a>';
		//	  echo '   (' . round(filesize($handle->file_dst_pathname)/256)/4 . 'KB)';
		//	  echo '</p>';
		// } else {
		//	  // one error occured
		//	  echo '<p class="result">';
		//	  echo '  <b>File not uploaded to the wanted location</b><br />';
		//	  echo '  Error: ' . $handle->error . '';
		//	  echo '</p>';
		// }
}


if (!$cli && !(isset($_SERVER['HTTP_X_FILE_NAME']) && isset($_SERVER['CONTENT_LENGTH']))) {
	//echo '<p class="result"><a href="index.html">do another test</a></p>';
	if (isset($handle)) {
		//echo '<pre>';
		//echo($handle->log);
		//echo '</pre>';
	}
}

echo json_encode($data);
?>
