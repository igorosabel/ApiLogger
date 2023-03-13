<?php
use OsumiFramework\App\Component\Model\PhotoComponent;

foreach ($values['list'] as $i => $photo) {
  $component = new PhotoComponent([ 'photo' => $photo ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
