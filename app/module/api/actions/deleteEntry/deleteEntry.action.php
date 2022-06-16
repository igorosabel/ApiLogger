<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Entry;

#[OModuleAction(
	url: '/deleteEntry',
	filter: 'login',
	services: ['web']
)]
class deleteEntryAction extends OAction {
	/**
	 * Función para borrar una entrada
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

		if ($status=='ok') {
			$entry = new Entry();
			if ($entry->find(['id'=>$id])) {
				if ($entry->get('id_user')==$filter['id']) {
					$entry->deleteFull();
					$this->web_service->cleanEmptyTags($filter['id']);
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
	}
}
