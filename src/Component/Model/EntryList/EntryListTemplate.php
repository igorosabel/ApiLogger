<?php
use Osumi\OsumiFramework\App\Component\Model\Entry\EntryComponent;

foreach ($values['list'] as $i => $Entry) {
  $component = new EntryComponent([ 'Entry' => $Entry ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
