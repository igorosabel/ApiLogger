<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Entry;

#[OModuleAction(
	url: '/uploadPhoto',
	services: ['web']
)]
class uploadPhotoAction extends OAction {
	/**
	 * Función para guardar una nueva foto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$photo = $req->getParamString('photo');

		$id_photo   = 'null';
		$created_at = 'null';
		$updated_at = 'null';

		if (is_null($id) || is_null($photo)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$entry = new Entry();
			if ($entry->find(['id'=>$id])) {
				$result = $this->web_service->addPhoto($entry, $photo);

				$id_photo   = $result['id'];
				$created_at = '"'.$result['createdAt'].'"';
				$updated_at = '"'.$result['updatedAt'].'"';
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status',     $status);
		$this->getTemplate()->add('id',         $id_photo);
		$this->getTemplate()->add('created_at', $created_at, 'nourlencode');
		$this->getTemplate()->add('updated_at', $updated_at, 'nourlencode');
	}
}
