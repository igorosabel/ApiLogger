<?php if (is_null($tag)): ?>
null
<?php else: ?>
{
	"id": <?php echo $tag->id ?>,
	"name": "<?php echo urlencode($tag->name) ?>",
	"num": <?php echo $tag->getNum() ?>,
	"createdAt": "<?php echo $tag->get('created_at', 'd/m/Y H:i:s') ?>",
	"updatedAt": "<?php echo is_null($tag->updated_at) ? 'null' : $tag->get('updated_at', 'd/m/Y H:i:s') ?>",
	"isPublic": <?php echo $tag->isPublic() ? 'true' : 'false' ?>
}
<?php endif ?>
