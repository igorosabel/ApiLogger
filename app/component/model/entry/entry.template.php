<?php
use OsumiFramework\App\Component\Model\TagListComponent;
if (is_null($values['entry'])){
?>
null
<?php
}
else {
?>
{
	"id": <?php echo $values['entry']->get('id') ?>,
	"title": "<?php echo urlencode($values['entry']->get('title')) ?>",
	"body": "<?php echo is_null($values['entry']->get('body')) ? 'null' : urlencode($values['entry']->get('body')) ?>",
	"isPublic": <?php echo $values['entry']->get('is_public') ? 'true' : 'false' ?>,
	"createdAt": "<?php echo $values['entry']->get('created_at', 'd/m/Y H:i') ?>",
	"updatedAt": "<?php echo is_null($values['entry']->get('updated_at')) ? 'null' : $values['entry']->get('updated_at', 'd/m/Y H:i') ?>",
	"tags": [<?php echo new TagListComponent(['list' => $values['entry']->getTags()]) ?>]
}
<?php
}
?>
