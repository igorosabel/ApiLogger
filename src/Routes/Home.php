<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\Home\Closed\ClosedAction;
use Osumi\OsumiFramework\App\Module\Home\Index\IndexAction;
use Osumi\OsumiFramework\App\Module\Home\NotFound\NotFoundAction;

ORoute::post('/closed',   ClosedAction::class);
ORoute::post('/',         IndexAction::class);
ORoute::post('/notFound', NotFoundAction::class);
