<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Photo;

#[OModuleAction(
	url: '/getEntryPhoto/:id'
)]
class getEntryPhotoAction extends OAction {
	/**
	 * Función para obtener una foto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$photo = 'null';

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$p = new Photo();
			if ($p->find(['id'=>$id])) {
				$photo = '"'.trim($p->getData()).'"';
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('photo', $photo, 'nourlencode');
	}
}
