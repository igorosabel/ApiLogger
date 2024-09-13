<?php declare(strict_types=1);

use Osumi\OsumiFramework\Routing\OUrl;
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
use Osumi\OsumiFramework\App\Module\Home\Closed\ClosedAction;
use Osumi\OsumiFramework\App\Module\Api\Index\IndexAction;
use Osumi\OsumiFramework\App\Module\Api\NotFound\NotFoundAction;

use Osumi\OsumiFramework\App\Filter\LoginFilter;
use Osumi\OsumiFramework\App\Service\WebService;

$api_urls = [
  [
    'url' => '/deleteEntry',
    'action' => DeleteEntryAction::class,
    'filters' => [LoginFilter::class],
  	'services' => [WebService::class],
    'type' => 'json'
  ],
  [
    'url' => '/deletePhoto',
    'action' => DeletePhotoAction::class,
    'type' => 'json'
  ],
  [
    'url' => '/getEntries',
    'action' => GetEntriesAction::class,
    'filters' => [LoginFilter::class],
  	'services' => [WebService::class],
    'type' => 'json'
  ],
  [
    'url' => '/getEntry',
    'action' => GetEntryAction::class,
    'filters' => [LoginFilter::class],
  	'services' => [WebService::class],
    'type' => 'json'
  ],
  [
    'url' => '/getEntryPhoto/:id',
    'action' => GetEntryPhotoAction::class,
    'type' => 'json'
  ],
  [
    'url' => '/getHome',
    'action' => GetHomeAction::class,
    'filters' => [LoginFilter::class],
  	'services' => [WebService::class],
    'type' => 'json'
  ],
  [
    'url' => '/getPhotos',
    'action' => GetPhotosAction::class,
    'filters' => [LoginFilter::class],
    'type' => 'json'
  ],
  [
    'url' => '/getPublicEntry',
    'action' => GetPublicEntryAction::class,
    'type' => 'json'
  ],
  [
    'url' => '/getTagEntries',
    'action' => GetTagEntriesAction::class,
    'filters' => [LoginFilter::class],
  	'services' => [WebService::class],
    'type' => 'json'
  ],
  [
    'url' => '/getTags',
    'action' => GetTagsAction::class,
    'filters' => [LoginFilter::class],
  	'services' => [WebService::class],
    'type' => 'json'
  ],
  [
    'url' => '/login',
    'action' => LoginAction::class,
    'type' => 'json'
  ],
  [
    'url' => '/register',
    'action' => RegisterAction::class,
    'type' => 'json'
  ],
  [
    'url' => '/saveEntry',
    'action' => SaveEntryAction::class,
    'filters' => [LoginFilter::class],
  	'services' => [WebService::class],
    'type' => 'json'
  ],
  [
    'url' => '/uploadPhoto',
    'action' => UploadPhotoAction::class,
  	'services' => [WebService::class],
    'type' => 'json'
  ],
];

$home_urls = [
  [
    'url' => '/closed',
    'action' => ClosedAction::class
  ],
  [
    'url' => '/',
    'action' => IndexAction::class
  ],
  [
    'url' => '/notFound',
    'action' => NotFoundAction::class
  ],
];

$urls = [];
OUrl::addUrls($urls, $api_urls, '/api');
OUrl::addUrls($urls, $home_urls);

return $urls;
