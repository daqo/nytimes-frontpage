<?php

// set the output-file
$outputfile = "/var/www/html/nytimes/nyt.jpg";

// set path to todays NYT frontpage
$pathToPdf="https://static01.nyt.com/images/".date('Y')."/".date('m')."/".date('d')."/nytfrontpage/scan.pdf";

// check if there is any today
$file_headers = @get_headers($pathToPdf);
if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
    $exists = false;
}
else {
    $exists = true;
}

// if there's none today, get yesterdays
if(!$exists) {
	$yesterday = date('Y/m/d',strtotime("-1 days"));
	$pathToPdf="https://static01.nyt.com/images/".$yesterday."/nytfrontpage/scan.pdf";
}

// you needed to have Imagick compiled in PHP otherwise this won't work
$im = new Imagick();

// So this panel is 1440x2550, 600dpi, it doesn't really like PNG so we are converting a PDF to JPG (sue me)
// get it here, compression to nothing, output it
$im->setResolution(600,600);
$im->readimage($pathToPdf);
$im->setImageCompressionQuality(100);
$im->setImageFormat('jpg');
$im->stripImage();
$im->scaleImage(1440,2550);
$im->writeImage($outputfile);
$im->clear();
$im->destroy();
echo 'Finished Processing';

// and display it with nice margins

?>

