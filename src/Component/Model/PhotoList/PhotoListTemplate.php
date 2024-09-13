<?php
use Osumi\OsumiFramework\App\Component\Model\Photo\PhotoComponent;

foreach ($values['list'] as $i => $Photo) {
  $component = new PhotoComponent([ 'Photo' => $Photo ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
