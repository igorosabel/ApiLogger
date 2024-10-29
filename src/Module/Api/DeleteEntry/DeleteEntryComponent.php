<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\DeleteEntry;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\WebService;
use Osumi\OsumiFramework\App\Model\Entry;

class DeleteEntryComponent extends OComponent {
  private ?WebService $ws = null;

  public string $status = 'ok';

  public function __construct() {
    parent::__construct();
    $this->ws = inject(WebService::class);
  }

	/**
	 * Función para borrar una entrada
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('Login');

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$entry = Entry::findOne(['id' => $id]);
			if (!is_null($entry)) {
				if ($entry->id_user === $filter['id']) {
					$entry->deleteFull();
					$this->ws->cleanEmptyTags($filter['id']);
				}
				else {
					$this->status = 'error';
				}
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
