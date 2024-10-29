<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Home\Index;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Routing\OUrl;

class IndexComponent extends OComponent {
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
