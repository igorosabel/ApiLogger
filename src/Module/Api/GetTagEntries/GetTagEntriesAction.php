<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetTagEntries;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Tag;
use Osumi\OsumiFramework\App\Component\Model\Tag\TagComponent;
use Osumi\OsumiFramework\App\Component\Model\EntryList\EntryListComponent;

class GetTagEntriesAction extends OAction {
  public string $status = 'ok';
  public ?TagComponent $tag = null;
  public ?EntryListComponent $list = null;

	/**
	 * Función para obtener las entradas con una tag concreta
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('Login');
		$this->tag = new TagComponent(['Tag' => null]);
		$this->list = new EntryListComponent(['list' => []]);

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
		  $this->status = 'error';
		}

		if ($this->status == 'ok') {
			$t = new Tag();
			$t->find(['id' => $id]);
			$this->tag->setValue('Tag', $t);
			$this->list->setValue('list', $this->service['Web']->getTagEntries($id));
		}
	}
}
