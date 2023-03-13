<?php if (is_null($values['photo'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['photo']->get('id') ?>,
	"createdAt": "<?php echo $values['photo']->get('created_at', 'd/m/Y H:i') ?>",
	"updatedAt": "<?php echo is_null($values['photo']->get('updated_at')) ? 'null' : $values['photo']->get('updated_at', 'd/m/Y H:i') ?>"
}
<?php endif ?>
