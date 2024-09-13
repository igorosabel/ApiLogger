<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetHome;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\App\DTO\HomeDTO;
use Osumi\OsumiFramework\App\Component\Model\EntryList\EntryListComponent;
use Osumi\OsumiFramework\App\Component\Model\TagList\TagListComponent;

class GetHomeAction extends OAction {
  public string $status   = 'ok';
  public string $calendar = '';
  public ?EntryListComponent $entries = null;
  public ?TagListComponent $tags = null;

	/**
	 * Función para obtener los datos iniciales de la home
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(HomeDTO $data):void {
		$this->entries = new EntryListComponent(['list' => []]);
		$this->tags = new TagListComponent(['list' => []]);

		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if  ($this->status == 'ok') {
			$calendar_list = $this->service['Web']->getCalendar($data->getIdUser(), $data->getMonth(), $data->getYear());
			if (count($calendar_list) > 0) {
				$this->calendar = '"'.implode('", "', $calendar_list).'"';
			}

			$this->entries->setValue('list', $this->service['Web']->getHomeEntries($data->getIdUser(), $data->getDay(), $data->getMonth(), $data->getYear(), $data->getTags(), $data->getFirst()));
			$this->tags->setValue('list', $this->service['Web']->getTags($data->getIdUser()));
		}
	}
}
