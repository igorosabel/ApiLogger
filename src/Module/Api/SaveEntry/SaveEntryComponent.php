<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\SaveEntry;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\App\Service\WebService;
use Osumi\OsumiFramework\App\Model\Entry;

class SaveEntryComponent extends OComponent {
  private ?WebService $ws = null;

  public string $status = 'ok';

  public function __construct() {
    parent::__construct();
    $this->ws = inject(WebService::class);
  }

	/**
	 * Función para guardar una entrada
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id        = $req->getParamInt('id');
		$title     = $req->getParamString('title');
		$body      = $req->getParamString('body');
		$tags      = $req->getParam('tags');
		$is_public = $req->getParamBool('isPublic');
		$filter    = $req->getFilter('Login');

		if (is_null($title) || is_null($body) || is_null($tags) || is_null($filter) || is_null($is_public) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$entry = Entry::create();
			if (!is_null($id)) {
				$entry = Entry::findOne(['id' => $id]);
			}
			$entry->id_user   = $filter['id'];
			$entry->title     = $title;
			$entry->body      = $body;
			$entry->is_public = $is_public;
			$entry->save();

			$this->ws->saveTags($entry, $tags);
		}
	}
}
