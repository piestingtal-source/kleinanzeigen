<?php if (!defined('ABSPATH')) die('Kein direkter Zugriff erlaubt!'); ?>

<?php $msg = __( 'Einstellungen gespeichert.', 'kleinanzeigen' ); ?>

<?php if ( !empty($this->message) ): ?>
<div class="updated below-h2" id="message">
	<p><?php echo $this->message; ?></p>
</div>
<?php endif; ?>