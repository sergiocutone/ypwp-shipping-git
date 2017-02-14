<?php 

$url = 'https://sergiocutone.github.io/ypwp-shipping/ver.json';
$content = file_get_contents($url);
$json = json_decode($content, true);

foreach($json['version'] as $item) {
	echo 'Version: '.$item['version'];
}

 ?>