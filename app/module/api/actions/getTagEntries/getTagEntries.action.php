<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Tag;
use OsumiFramework\App\Component\Model\TagComponent;
use OsumiFramework\App\Component\Model\EntryListComponent;

#[OModuleAction(
	url: '/getTagEntries',
	filters: ['login'],
	services: ['web']
)]
class getTagEntriesAction extends OAction {
	/**
	 * Función para obtener las entradas con una tag concreta
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('login');
		$tag_component = new TagComponent(['tag' => null]);
		$entry_list_component = new EntryListComponent(['list' => []]);

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
		  $status = 'error';
		}
		$tag  = 'null';

		if ($status=='ok') {
			$t = new Tag();
			$t->find(['id' => $id]);
			$tag_component->setValue('tag', $t);
			$entry_list_component->setValue('list', $this->web_service->getTagEntries($id));
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('tag',    $tag_component);
		$this->getTemplate()->add('list',   $entry_list_component);
	}
}
