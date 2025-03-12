<?php

// hii, hiii! you're most likely here because you've been royally fucked :3c

ob_end_clean();

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Expires: 0');
while (true) {
	readfile(__DIR__.'/resource/tracks/borough_of_broadkill.wav');
}