<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Photo;

#[OModuleAction(
	url: '/deletePhoto'
)]
class deletePhotoAction extends OAction {
	/**
	 * Función para borrar una foto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id = $req->getParamInt('id');

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$photo = new Photo();
			if ($photo->find(['id'=>$id])) {
				$photo->deleteFull();
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}
