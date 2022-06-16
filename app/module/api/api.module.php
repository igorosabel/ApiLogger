<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Routing\OModule;

#[OModule(
	actions: 'register, login, getEntries, getEntry, getPublicEntry, getTags, saveEntry, getTagEntries, deleteEntry, getPhotos, getEntryPhoto, uploadPhoto, deletePhoto',
	type: 'json',
	prefix: '/api'
)]
class apiModule {}
