<?php
use Osumi\OsumiFramework\App\Component\Model\TagList\TagListComponent;
?>
<?php if (is_null($entry)): ?>
null
<?php else: ?>
{
	"id": <?php echo $entry->get('id') ?>,
	"title": "<?php echo urlencode($entry->get('title')) ?>",
	"body": "<?php echo is_null($entry->get('body')) ? 'null' : urlencode($entry->get('body')) ?>",
	"isPublic": <?php echo $entry->get('is_public') ? 'true' : 'false' ?>,
	"createdAt": "<?php echo $entry->get('created_at', 'd/m/Y H:i:s') ?>",
	"updatedAt": "<?php echo is_null($entry->get('updated_at')) ? 'null' : $entry->get('updated_at', 'd/m/Y H:i:s') ?>",
	"tags": [<?php echo new TagListComponent(['list' => $entry->getTags()]) ?>]
}
<?php endif ?>
