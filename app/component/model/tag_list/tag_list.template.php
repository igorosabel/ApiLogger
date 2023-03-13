<?php
use OsumiFramework\App\Component\Model\TagComponent;

foreach ($values['list'] as $i => $tag) {
  $component = new TagComponent([ 'tag' => $tag ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
