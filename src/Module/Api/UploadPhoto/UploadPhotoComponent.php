<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\UploadPhoto;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\WebService;
use Osumi\OsumiFramework\App\Model\Entry;

class UploadPhotoComponent extends OComponent {
  private ?WebService $ws = null;

  public string $status     = 'ok';
  public string | int $id   = 'null';
  public string $created_at = 'null';
  public string $updated_at = 'null';

  public function __construct() {
    parent::__construct();
    $this->ws = inject(WebService::class);
  }

	/**
	 * Función para guardar una nueva foto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id_entry = $req->getParamInt('id');
		$photo = $req->getParamString('photo');

		if (is_null($id_entry) || is_null($photo)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$entry = Entry::findOne(['id' => $id_entry]);
			if (!is_null($entry)) {
				$result = $this->ws->addPhoto($entry, $photo);

				$this->id         = $result['id'];
				$this->created_at = '"'.$result['createdAt'].'"';
				$this->updated_at = '"'.$result['updatedAt'].'"';
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
