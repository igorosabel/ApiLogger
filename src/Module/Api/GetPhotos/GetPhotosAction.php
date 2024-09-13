<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetPhotos;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Entry;
use Osumi\OsumiFramework\App\Component\Model\PhotoList\PhotoListComponent;

class GetPhotosAction extends OAction {
  public string $status = 'ok';
  public ?PhotoListComponent $list = null;

	/**
	 * Función para obtener las fotos de una entrada concreta
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('Login');
		$this->list = new PhotoListComponent(['list' => []]);

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status=='ok') {
			$entry = new Entry();
			if ($entry->find(['id' => $id])) {
				if ($entry->get('id_user') == $filter['id']) {
					$this->list->setValue('list', $entry->getPhotos());
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
