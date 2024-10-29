<?php if (is_null($user)): ?>
null
<?php else: ?>
{
	"id": <?php echo $user->id ?>,
	"username": "<?php echo urlencode($user->username) ?>",
	"token": "<?php echo $user->getToken() ?>"
}
<?php endif ?>
