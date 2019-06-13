<?php
require_once dirname(__FILE__).'/CL_pdf.php';

print_r("Start");
$body = $_POST['body'];
file_put_contents('report.html',$body);
$thermal = $_POST['thermal'];
$dynamic = $_POST['dynamic'];

error_log("Action");
error_log($body);
error_log("thermal");
error_log($thermal);
error_log("dynamic");
error_log($dynamic);
error_log("Stop");
?>
