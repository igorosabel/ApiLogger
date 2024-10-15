<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\DeletePhoto;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Photo;

class DeletePhotoComponent extends OComponent {
  public string $status = 'ok';

	/**
	 * Función para borrar una foto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$id = $req->getParamInt('id');

		if (is_null($id)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$photo = new Photo();
			if ($photo->find(['id' => $id])) {
				$photo->deleteFull();
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
