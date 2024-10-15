<?php
use Osumi\OsumiFramework\App\Component\Model\Entry\EntryComponent;

foreach ($list as $i => $entry) {
  $component = new EntryComponent([ 'entry' => $entry ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
