<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Entry;

#[OModuleAction(
	url: '/getEntry',
	filters: ['login'],
	services: ['web']
)]
class getEntryAction extends OAction {
	/**
	 * Función para obtener el detalle de una entrada
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('login');

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}
		$entry = 'null';

		if ($status=='ok') {
			$e = new Entry();
			if ($e->find(['id'=>$id])) {
				if ($e->get('id_user')==$filter['id']) {
					$entry = json_encode($e->toArray());
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
		$this->getTemplate()->add('entry',  $entry, 'nourlencode');
	}
}
