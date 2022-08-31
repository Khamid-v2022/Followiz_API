<?php 

if(isset($_GET['file_path'])){
    $file_path = $_GET['file_path'];
    // check if file exist in server
    if(file_exists($file_path)) {
          header('Content-Description: File Transfer');
          header('Content-Type: application/octet-stream');
          header('Content-Disposition: attachment; filename="'.basename($file_path).'"');
          header('Expires: 0');
          header('Pragma: public');
          header('Content-Length: ' . filesize($file_path));
          // Clear output buffer
          flush();
          readfile($file_path);
          exit();
    }
}

?>