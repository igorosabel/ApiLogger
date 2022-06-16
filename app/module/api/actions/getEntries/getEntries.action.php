<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;

#[OModuleAction(
	url: '/getEntries',
	filter: 'login',
	services: ['web']
)]
class getEntriesAction extends OAction {
	/**
	 * Función para obtener las entradas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$filter = $req->getFilter('login');

		if (is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}
		$list = '[]';

		if ($status=='ok') {
			$list = $this->web_service->getEntries($filter['id']);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $list, 'nourlencode');
	}
}
