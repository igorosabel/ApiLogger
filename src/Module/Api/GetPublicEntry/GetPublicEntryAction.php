<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetPublicEntry;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Entry;
use Osumi\OsumiFramework\App\Component\Model\Entry\EntryComponent;

class GetPublicEntryAction extends OAction {
  public string $status = 'ok';
  public ?EntryComponent $entry = null;

	/**
	 * Función para obtener el detalle de una entrada pública
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$id          = $req->getParamInt('id');
		$this->entry = new EntryComponent(['Entry' => null]);

		if ($this->status == 'ok') {
			$e = new Entry();
			if ($e->find(['id' => $id])) {
				if ($e->get('is_public')) {
					$this->entry->setValue('Entry', $e);
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
