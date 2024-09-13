<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\NotFound;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\OFW\Routing\OUrl;

class NotFoundAction extends OAction {
	/**
	 * Function description
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		OUrl::goToUrl('https://logger.osumi.es');
	}
}
