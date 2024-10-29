<?php
use Osumi\OsumiFramework\App\Component\Model\TagList\TagListComponent;
?>
<?php if (is_null($entry)): ?>
null
<?php else: ?>
{
	"id": <?php echo $entry->id ?>,
	"title": "<?php echo urlencode($entry->title) ?>",
	"body": "<?php echo is_null($entry->body) ? 'null' : urlencode($entry->body) ?>",
	"isPublic": <?php echo $entry->is_public ? 'true' : 'false' ?>,
	"createdAt": "<?php echo $entry->get('created_at', 'd/m/Y H:i:s') ?>",
	"updatedAt": "<?php echo is_null($entry->updated_at) ? 'null' : $entry->get('updated_at', 'd/m/Y H:i:s') ?>",
	"tags": [<?php echo new TagListComponent(['list' => $entry->getTags()]) ?>]
}
<?php endif ?>
