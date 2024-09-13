<?php
use Osumi\OsumiFramework\App\Component\Model\TagList\TagListComponent;
?>
<?php if (is_null($values['Entry'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['Entry']->get('id') ?>,
	"title": "<?php echo urlencode($values['Entry']->get('title')) ?>",
	"body": "<?php echo is_null($values['Entry']->get('body')) ? 'null' : urlencode($values['Entry']->get('body')) ?>",
	"isPublic": <?php echo $values['Entry']->get('is_public') ? 'true' : 'false' ?>,
	"createdAt": "<?php echo $values['Entry']->get('created_at', 'd/m/Y H:i:s') ?>",
	"updatedAt": "<?php echo is_null($values['Entry']->get('updated_at')) ? 'null' : $values['Entry']->get('updated_at', 'd/m/Y H:i:s') ?>",
	"tags": [<?php echo new TagListComponent(['list' => $values['Entry']->getTags()]) ?>]
}
<?php endif ?>
