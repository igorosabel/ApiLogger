<?php if (is_null($values['user'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['user']->get('id') ?>,
	"username": "<?php echo urlencode($values['user']->get('username')) ?>",
	"token": "<?php echo $values['user']->getToken() ?>"
}
<?php endif ?>
