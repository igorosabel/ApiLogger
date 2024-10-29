<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetEntry;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Entry;
use Osumi\OsumiFramework\App\Component\Model\Entry\EntryComponent;

class GetEntryComponent extends OComponent {
  public string $status = 'ok';
  public ?EntryComponent $entry = null;

  public function __construct() {
    parent::__construct();
    $this->entry = new EntryComponent();
  }

	/**
	 * Función para obtener el detalle de una entrada
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
			$e = Entry::findOne(['id'=> $id]);
			if (!is_null($e)) {
				if ($e->id_user === $filter['id']) {
					$this->entry->entry = $e;
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
