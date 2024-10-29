<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Home\NotFound;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\OFW\Routing\OUrl;

class NotFoundComponent extends OComponent {
	/**
	 * Function description
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		OUrl::goToUrl('https://logger.osumi.es');
	}
}
