<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class HomeDTO implements ODTO{
  public ?int $day = null;
	public ?int $month = null;
	public ?int $year = null;
  public ?bool $first = null;
	public ?array $tags = null;
  public ?int $id_user = null;

	public function isValid(): bool {
		return (!is_null($this->id_user));
	}

	public function load(ORequest $req): void {
		$filter = $req->getFilter('Login');

		$this->day = $req->getParamInt('day');
		$this->month = $req->getParamInt('month');
		$this->year = $req->getParamInt('year');
		$this->first = $req->getParamBool('first');
		$this->tags = $req->getParam('tags');
		$this->id_user = array_key_exists('id', $filter) ? $filter['id'] : null;
	}
}
