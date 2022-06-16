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
		$id = $req->getParamInt('id');
		if (is_null($id)) {
			echo 'error';
			exit;
		}
		else {
			$p = new Photo();
			if ($p->find(['id'=>$id])) {
				$photo_data = $p->getImage();
				header('Content-type: '.$photo_data['type']);
				echo base64_decode($photo_data['image']);
				exit;
			}
			else {
				echo 'error';
				exit;
			}
		}
	}
}
