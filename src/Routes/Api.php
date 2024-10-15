<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\Api\DeleteEntry\DeleteEntryComponent;
use Osumi\OsumiFramework\App\Module\Api\DeletePhoto\DeletePhotoComponent;
use Osumi\OsumiFramework\App\Module\Api\GetEntries\GetEntriesComponent;
use Osumi\OsumiFramework\App\Module\Api\GetEntry\GetEntryComponent;
use Osumi\OsumiFramework\App\Module\Api\GetEntryPhoto\GetEntryPhotoComponent;
use Osumi\OsumiFramework\App\Module\Api\GetHome\GetHomeComponent;
use Osumi\OsumiFramework\App\Module\Api\GetPhotos\GetPhotosComponent;
use Osumi\OsumiFramework\App\Module\Api\GetPublicEntry\GetPublicEntryComponent;
use Osumi\OsumiFramework\App\Module\Api\GetTagEntries\GetTagEntriesComponent;
use Osumi\OsumiFramework\App\Module\Api\GetTags\GetTagsComponent;
use Osumi\OsumiFramework\App\Module\Api\Login\LoginComponent;
use Osumi\OsumiFramework\App\Module\Api\Register\RegisterComponent;
use Osumi\OsumiFramework\App\Module\Api\SaveEntry\SaveEntryComponent;
use Osumi\OsumiFramework\App\Module\Api\UploadPhoto\UploadPhotoComponent;
use Osumi\OsumiFramework\App\Filter\LoginFilter;

ORoute::prefix('/api', function() {
  ORoute::post('/deleteEntry',       DeleteEntryComponent::class,   [LoginFilter::class]);
  ORoute::post('/deletePhoto',       DeletePhotoComponent::class);
  ORoute::post('/getEntries',        GetEntriesComponent::class,    [LoginFilter::class]);
  ORoute::post('/getEntry',          GetEntryComponent::class,      [LoginFilter::class]);
  ORoute::get('/getEntryPhoto/:id',  GetEntryPhotoComponent::class);
  ORoute::post('/getHome',           GetHomeComponent::class,       [LoginFilter::class]);
  ORoute::post('/getPhotos',         GetPhotosComponent::class,     [LoginFilter::class]);
  ORoute::post('/getPublicEntry',    GetPublicEntryComponent::class);
  ORoute::post('/getTagEntries',     GetTagEntriesComponent::class, [LoginFilter::class]);
  ORoute::post('/getTags',           GetTagsComponent::class,       [LoginFilter::class]);
  ORoute::post('/login',             LoginComponent::class);
  ORoute::post('/register',          RegisterComponent::class);
  ORoute::post('/saveEntry',         SaveEntryComponent::class,     [LoginFilter::class]);
  ORoute::post('/uploadPhoto',       UploadPhotoComponent::class,   [LoginFilter::class]);
});
