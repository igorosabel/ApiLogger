<?php if (is_null($values['tag'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['tag']->get('id') ?>,
	"name": "<?php echo urlencode($values['tag']->get('name')) ?>",
	"createdAt": "<?php echo $values['tag']->get('created_at', 'd/m/Y H:i') ?>",
	"updatedAt": "<?php echo is_null($values['tag']->get('updated_at')) ? 'null' : $values['tag']->get('updated_at', 'd/m/Y H:i') ?>",
	"isPublic": <?php echo $values['tag']->isPublic() ? 'true' : 'false' ?>
}
<?php endif ?>
