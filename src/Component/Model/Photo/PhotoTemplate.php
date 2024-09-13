<?php if (is_null($values['Photo'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['Photo']->get('id') ?>,
	"createdAt": "<?php echo $values['Photo']->get('created_at', 'd/m/Y H:i:s') ?>",
	"updatedAt": "<?php echo is_null($values['Photo']->get('updated_at')) ? 'null' : $values['Photo']->get('updated_at', 'd/m/Y H:i:s') ?>"
}
<?php endif ?>
