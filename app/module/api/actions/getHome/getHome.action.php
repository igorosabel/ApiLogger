<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\HomeDTO;
use OsumiFramework\App\Component\Model\EntryListComponent;
use OsumiFramework\App\Component\Model\TagListComponent;

#[OModuleAction(
	url: '/getHome',
	filters: ['login'],
	services: ['web']
)]
class getHomeAction extends OAction {
	/**
	 * Función para obtener los datos iniciales de la home
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(HomeDTO $data):void {
		$status   = 'ok';
		$calendar = '';
		$entry_list_component = new EntryListComponent(['list' => []]);
		$tag_list_component   = new TagListComponent(['list' => []]);

		if (!$data->isValid()) {
			$status = 'error';
		}

		if  ($status == 'ok') {
			$calendar_list = $this->web_service->getCalendar($data->getIdUser(), $data->getMonth(), $data->getYear());
			if (count($calendar_list) > 0) {
				$calendar = '"'.implode('", "', $calendar_list).'"';
			}

			$entry_list_component->setValue('list', $this->web_service->getHomeEntries($data->getIdUser(), $data->getDay(), $data->getMonth(), $data->getYear(), $data->getTags(), $data->getFirst()));
			$tag_list_component->setValue('list',   $this->web_service->getTags($data->getIdUser()));
		}

		$this->getTemplate()->add('status',   $status);
		$this->getTemplate()->add('calendar', $calendar, 'nourlencode');
		$this->getTemplate()->add('entries',  $entry_list_component);
		$this->getTemplate()->add('tags',     $tag_list_component);
	}
}
