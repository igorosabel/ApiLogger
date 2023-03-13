<?php
use OsumiFramework\App\Component\Model\EntryComponent;

foreach ($values['list'] as $i => $entry) {
  $component = new EntryComponent([ 'entry' => $entry ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
