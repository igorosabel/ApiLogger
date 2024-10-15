<?php if (is_null($user)): ?>
null
<?php else: ?>
{
	"id": <?php echo $user->get('id') ?>,
	"username": "<?php echo urlencode($user->get('username')) ?>",
	"token": "<?php echo $user->getToken() ?>"
}
<?php endif ?>
