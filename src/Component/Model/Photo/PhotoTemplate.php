<?php if (is_null($photo)): ?>
null
<?php else: ?>
{
	"id": <?php echo $photo->id ?>,
	"createdAt": "<?php echo $photo->get('created_at', 'd/m/Y H:i:s') ?>",
	"updatedAt": "<?php echo is_null($photo->updated_at) ? 'null' : $photo->get('updated_at', 'd/m/Y H:i:s') ?>"
}
<?php endif ?>
