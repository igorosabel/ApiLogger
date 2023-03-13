<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Entry;
use OsumiFramework\App\Component\Model\EntryComponent;

#[OModuleAction(
	url: '/getPublicEntry'
)]
class getPublicEntryAction extends OAction {
	/**
	 * Función para obtener el detalle de una entrada pública
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$entry_component = new EntryComponent(['entry' => null]);

		if ($status=='ok') {
			$e = new Entry();
			if ($e->find(['id'=>$id])) {
				if ($e->get('is_public')) {
					$entry_component->setValue('entry', $e);
				}
				else {
					$status = 'error';
				}
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('entry',  $entry_component);
	}
}
