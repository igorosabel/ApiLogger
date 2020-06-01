<?php declare(strict_types=1);
class home extends OController {
  /**
   * Página temporal, sitio cerrado
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
  public function closed(ORequest $req): void {
    OUrl::goToUrl('https://logger.osumi.es');
  }

  /**
   * Página de error 404
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
  public function notFound(ORequest $req): void {
    OUrl::goToUrl('https://logger.osumi.es');
  }

  /**
   * Home pública
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
  public function index(ORequest $req): void {
    OUrl::goToUrl('https://logger.osumi.es');
  }
}