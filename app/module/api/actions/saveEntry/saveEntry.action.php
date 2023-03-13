<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Tools\OTools;
use OsumiFramework\App\Model\Entry;

#[OModuleAction(
	url: '/saveEntry',
	filters: ['login'],
	services: ['web']
)]
class saveEntryAction extends OAction {
	/**
	 * Función para guardar una entrada
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$title  = $req->getParamString('title');
		$body   = $req->getParamString('body');
		$tags   = $req->getParam('tags');
		$filter = $req->getFilter('login');

		if (is_null($title) || is_null($body) || is_null($tags) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$entry = new Entry();
			if (!is_null($id)) {
				$entry->find(['id'=>$id]);
			}
			$entry->set('id_user', $filter['id']);
			$entry->set('title',   $title);
			$entry->set('body',    $body);
			$entry->save();

			$this->web_service->saveTags($entry, $tags);
		}

		$this->getTemplate()->add('status', $status);
	}
}
