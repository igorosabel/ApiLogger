<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
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
	public function run(ORequest $req):void {
		$status   = 'ok';
		$day      = $req->getParamInt('day');
		$month    = $req->getParamInt('month');
		$year     = $req->getParamInt('year');
		$tags     = $req->getParam('tags');
		$filter   = $req->getFilter('login');
		$calendar = '';
		$entry_list_component = new EntryListComponent(['list' => []]);
		$tag_list_component   = new TagListComponent(['list' => []]);

		if (!array_key_exists('id', $filter)) {
			$status = 'error';
		}

		if  ($status == 'ok') {
			$calendar_list = $this->web_service->getCalendar($filter['id'], $month, $year);
			if (count($calendar_list) > 0) {
				$calendar = '"'.implode('", "', $calendar_list).'"';
			}

			$entry_list_component->setValue('list', $this->web_service->getHomeEntries($filter['id'], $day, $month, $year, $tags));
			$tag_list_component->setValue('list',   $this->web_service->getTags($filter['id']));
		}

		$this->getTemplate()->add('status',   $status);
		$this->getTemplate()->add('calendar', $calendar, 'nourlencode');
		$this->getTemplate()->add('entries',  $entry_list_component);
		$this->getTemplate()->add('tags',     $tag_list_component);
	}
}
