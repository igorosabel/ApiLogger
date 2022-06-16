<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Tag;

#[OModuleAction(
	url: '/getTagEntries',
	filter: 'login',
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

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
		  $status = 'error';
		}
		$tag  = 'null';
		$list = '[]';

		if ($status=='ok') {
			$t = new Tag();
			$t->find(['id' => $id]);
			$tag = json_encode($t->toArray());
			$list = $this->web_service->getTagEntries($id);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('tag',    $tag,  'nourlencode');
		$this->getTemplate()->add('list',   $list, 'nourlencode');
	}
}
