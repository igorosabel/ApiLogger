<?php if (is_null($values['User'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['User']->get('id') ?>,
	"username": "<?php echo urlencode($values['User']->get('username')) ?>",
	"token": "<?php echo $values['User']->getToken() ?>"
}
<?php endif ?>
