<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetEntries;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\WebService;
use Osumi\OsumiFramework\App\Component\Model\EntryList\EntryListComponent;

class GetEntriesComponent extends OComponent {
  private ?WebService $ws = null;

  public string $status = 'ok';
  public ?EntryListComponent $list = null;

  public function __construct() {
    parent::__construct();
    $this->ws = inject(WebService::class);
    $this->list = new EntryListComponent();
  }

	/**
	 * Función para obtener las entradas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$filter = $req->getFilter('Login');

		if (is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$this->list->list = $this->ws->getEntries($filter['id']);
		}
	}
}
