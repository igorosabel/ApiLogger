<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\Api\DeleteEntry\DeleteEntryAction;
use Osumi\OsumiFramework\App\Module\Api\DeletePhoto\DeletePhotoAction;
use Osumi\OsumiFramework\App\Module\Api\GetEntries\GetEntriesAction;
use Osumi\OsumiFramework\App\Module\Api\GetEntry\GetEntryAction;
use Osumi\OsumiFramework\App\Module\Api\GetEntryPhoto\GetEntryPhotoAction;
use Osumi\OsumiFramework\App\Module\Api\GetHome\GetHomeAction;
use Osumi\OsumiFramework\App\Module\Api\GetPhotos\GetPhotosAction;
use Osumi\OsumiFramework\App\Module\Api\GetPublicEntry\GetPublicEntryAction;
use Osumi\OsumiFramework\App\Module\Api\GetTagEntries\GetTagEntriesAction;
use Osumi\OsumiFramework\App\Module\Api\GetTags\GetTagsAction;
use Osumi\OsumiFramework\App\Module\Api\Login\LoginAction;
use Osumi\OsumiFramework\App\Module\Api\Register\RegisterAction;
use Osumi\OsumiFramework\App\Module\Api\SaveEntry\SaveEntryAction;
use Osumi\OsumiFramework\App\Module\Api\UploadPhoto\UploadPhotoAction;
use Osumi\OsumiFramework\App\Filter\LoginFilter;

ORoute::group('/api', 'json', function() {
  ORoute::post('/deleteEntry',       DeleteEntryAction::class,   [LoginFilter::class]);
  ORoute::post('/deletePhoto',       DeletePhotoAction::class);
  ORoute::post('/getEntries',        GetEntriesAction::class,    [LoginFilter::class]);
  ORoute::post('/getEntry',          GetEntryAction::class,      [LoginFilter::class]);
  ORoute::post('/getEntryPhoto/:id', GetEntryPhotoAction::class);
  ORoute::post('/getHome',           GetHomeAction::class,       [LoginFilter::class]);
  ORoute::post('/getPhotos',         GetPhotosAction::class,     [LoginFilter::class]);
  ORoute::post('/getPublicEntry',    GetPublicEntryAction::class);
  ORoute::post('/getTagEntries',     GetTagEntriesAction::class, [LoginFilter::class]);
  ORoute::post('/getTags',           GetTagsAction::class,       [LoginFilter::class]);
  ORoute::post('/login',             AddAuthorAction::class);
  ORoute::post('/register',          RegisterAction::class);
  ORoute::post('/saveEntry',         SaveEntryAction::class,     [LoginFilter::class]);
  ORoute::post('/uploadPhoto',       UploadPhotoAction::class,   [LoginFilter::class]);
});
