<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetEntryPhoto;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Photo;

class GetEntryPhotoComponent extends OComponent {
  public string $status = 'ok';
  public string $photo  = 'null';

	/**
	 * Función para obtener una foto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id = $req->getParamInt('id');

		if (is_null($id)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$p = Photo::findOne(['id' => $id]);
			if (!is_null($p)) {
				$this->photo = '"' . trim($p->getData()) . '"';
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
