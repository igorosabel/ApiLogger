<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetHome;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\DTO\HomeDTO;
use Osumi\OsumiFramework\App\Service\WebService;
use Osumi\OsumiFramework\App\Component\Model\EntryList\EntryListComponent;
use Osumi\OsumiFramework\App\Component\Model\TagList\TagListComponent;

class GetHomeComponent extends OComponent {
  private ?WebService $ws = null;

  public string $status   = 'ok';
  public string $calendar = '';
  public ?EntryListComponent $entries = null;
  public ?TagListComponent $tags = null;

  public function __construct() {
    parent::__construct();
    $this->ws = inject(WebService::class);
    $this->entries = new EntryListComponent();
		$this->tags = new TagListComponent();
  }

	/**
	 * Función para obtener los datos iniciales de la home
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(HomeDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if  ($this->status === 'ok') {
			$calendar_list = $this->ws->getCalendar($data->id_user, $data->month, $data->year);
			if (count($calendar_list) > 0) {
				$this->calendar = '"' . implode('", "', $calendar_list) . '"';
			}

			$this->entries->list = $this->ws->getHomeEntries($data->id_user, $data->day, $data->month, $data->year, $data->tags, $data->first);
			$this->tags->list = $this->ws->getTags($data->id_user);
		}
	}
}
