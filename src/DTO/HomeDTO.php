<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class HomeDTO implements ODTO{
  private ?int $day = null;
	private ?int $month = null;
	private ?int $year = null;
  private ?bool $first = null;
	private ?array $tags = null;
  private ?int $id_user = null;

	public function getDay(): ?int {
		return $this->day;
	}
	private function setDay(?int $day): void {
		$this->day = $day;
	}
	public function getMonth(): ?int {
		return $this->month;
	}
	private function setMonth(?int $month): void {
		$this->month = $month;
	}
  public function getYear(): ?int {
		return $this->year;
	}
	private function setYear(?int $year): void {
		$this->year = $year;
	}
	public function getFirst(): ?bool {
		return $this->first;
	}
	private function setFirst(?bool $first): void {
		$this->first = $first;
	}
	public function getTags(): ?array {
		return $this->tags;
	}
	private function setTags(?array $tags): void {
		$this->tags = $tags;
	}
  public function getIdUser(): ?int {
		return $this->id_user;
	}
	private function setIdUser(?int $id_user): void {
		$this->id_user = $id_user;
	}

	public function isValid(): bool {
		return (!is_null($this->getIdUser()));
	}

	public function load(ORequest $req): void {
		$filter = $req->getFilter('Login');

		$this->setDay($req->getParamInt('day'));
		$this->setMonth($req->getParamInt('month'));
		$this->setYear($req->getParamInt('year'));
		$this->setFirst($req->getParamBool('first'));
		$this->setTags($req->getParam('tags'));
		$this->setIdUser(array_key_exists('id', $filter) ? $filter['id'] : null);
	}
}
