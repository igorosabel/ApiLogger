<?php if (is_null($values['Tag'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['Tag']->get('id') ?>,
	"name": "<?php echo urlencode($values['Tag']->get('name')) ?>",
	"num": <?php echo $values['Tag']->getNum() ?>,
	"createdAt": "<?php echo $values['Tag']->get('created_at', 'd/m/Y H:i:s') ?>",
	"updatedAt": "<?php echo is_null($values['Tag']->get('updated_at')) ? 'null' : $values['Tag']->get('updated_at', 'd/m/Y H:i:s') ?>",
	"isPublic": <?php echo $values['Tag']->isPublic() ? 'true' : 'false' ?>
}
<?php endif ?>
