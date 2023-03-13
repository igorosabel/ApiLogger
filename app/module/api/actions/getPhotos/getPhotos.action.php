<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Entry;
use OsumiFramework\App\Component\Model\PhotoListComponent;

#[OModuleAction(
	url: '/getPhotos',
	filters: ['login']
)]
class getPhotosAction extends OAction {
	/**
	 * Función para obtener las fotos de una entrada concreta
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('login');
		$photo_list_component = new PhotoListComponent(['list' => []]);

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$entry = new Entry();
			if ($entry->find(['id'=>$id])) {
				if ($entry->get('id_user')==$filter['id']) {
					$photo_list_component->setValue('list', $entry->getPhotos());
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
		$this->getTemplate()->add('list',   $photo_list_component);
	}
}
