<?php if (!defined('ABSPATH')) die('No direct access allowed!');

$import = '';

if(defined( 'CT_ALLOW_IMPORT' )
&& ! empty($_POST['ct_import'])
&& check_admin_referer('import') ) {
	$import = stripslashes($_POST['ct_import']);
	eval($import);
}

if ( is_network_admin() ) {
	$post_types = get_site_option('ct_custom_post_types');
	$taxonomies = get_site_option('ct_custom_taxonomies');
	$custom_fields = get_site_option('ct_custom_fields');
} else {
	$post_types = get_option('ct_custom_post_types');
	$taxonomies = get_option('ct_custom_taxonomies');
	$custom_fields = get_option('ct_custom_fields');
}

$post_types = (empty($post_types))? array() : $post_types;
$taxonomies = (empty($taxonomies))? array() : $taxonomies;
$custom_fields = (empty($custom_fields))? array() : $custom_fields;

?>

<div class="wrap">

	<h2><?php esc_html_e('CustomPress Benutzerdefinierte Typen exportieren', 'kleinanzeigen'); ?></h2>
	<form action="#" method="post">

		<div class="ct-table-wrap">
			<div class="ct-arrow"><br /></div>
			<h3 class="ct-toggle"><span><?php esc_html_e('Beitragstypen exportieren', 'kleinanzeigen'); ?></span></h3>
			<div class="inside">
				<table class="form-table">
					<tr>
						<th>
							<?php esc_html_e('Wähle die Beitragstypen, die Du exportieren möchtest.', 'kleinanzeigen'); ?>
						</th>
						<td>
							<?php foreach($post_types as $key => $post_type): ?>
							<label class="ct-list"><input type="checkbox" name="pt[<?php echo esc_attr( $key );?>]" value="1" <?php checked(! empty($_POST['pt'][$key]) ); ?> />&nbsp;<?php echo $key?></label>
							<?php endforeach; ?>
						</td>
					</tr>
					<tr>
						<th>
						</th>
						<td>
							<textarea id="post_export" rows="6" cols="80" ><?php echo esc_textarea(post_types_export($post_types)); ?></textarea>
						</td>
					</tr>
					<tr>
						<th>
						</th>
						<td>
							<input type="submit" class="button" value="<?php esc_html_e('Beitragstyp Export erstellen', 'kleinanzeigen'); ?>" />
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="ct-table-wrap">
			<div class="ct-arrow"><br /></div>
			<h3 class="ct-toggle"><span><?php esc_html_e('Export von Taxonomien', 'kleinanzeigen'); ?></span></h3>
			<div class="inside">
				<table class="form-table">
					<tr>
						<th>
							<?php esc_html_e('Wähle die Taxonomien, die Du exportieren möchtest.', 'kleinanzeigen'); ?>
						</th>
						<td>
							<?php foreach($taxonomies as $key => $taxonomy): ?>
							<label class="ct-list"><input type="checkbox" name="tx[<?php echo esc_attr( $key );?>]" value="1" <?php checked(! empty($_POST['tx'][$key]) ); ?> />&nbsp;<?php echo $key?></label>
							<?php endforeach; ?>
						</td>
					</tr>
					<tr>
						<th>
						</th>
						<td>
							<textarea id="taxonomies_export" rows="6" cols="80" ><?php echo esc_textarea(taxonomies_export($taxonomies)); ?></textarea>
						</td>
					</tr>
					<tr>
						<th>
						</th>
						<td>
							<input type="submit" class="button" value="<?php esc_html_e('Export von Taxonomien erstellen', 'kleinanzeigen'); ?>" />
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="ct-table-wrap">
			<div class="ct-arrow"><br /></div>
			<h3 class="ct-toggle"><span><?php esc_html_e('Export von benutzerdefinierten Feldern', 'kleinanzeigen'); ?></span></h3>
			<div class="inside">
				<table class="form-table">
					<tr>
						<th>
							<?php esc_html_e('Wähle die benutzerdefinierten Felder, die Du exportieren möchten.', 'kleinanzeigen'); ?>
						</th>
						<td>
							<?php foreach($custom_fields as $key => $custom_field): ?>
							<label class="ct-list-cf"><input type="checkbox" name="cf[<?php echo esc_attr( $key );?>]" value="1" <?php checked(! empty($_POST['cf'][$key]) ); ?> />&nbsp;<?php echo $custom_field['field_title'] . ' : ' . $key?></label>
							<?php endforeach; ?>
						</td>
					</tr>
					<tr>
						<th>
						</th>
						<td>
							<textarea id="field_export" rows="6" cols="80" ><?php echo esc_textarea(custom_fields_export($custom_fields)); ?></textarea>
						</td>
					</tr>
					<tr>
						<th>
						</th>
						<td>
							<?php wp_nonce_field('export'); ?>
							<input type="submit" class="button" value="<?php esc_html_e('Export von benutzerdefinierten Feldern erstellen', 'kleinanzeigen'); ?>" />
						</td>
					</tr>
				</table>
			</div>
		</div>

	</form>


	<form action="#" method="post">
		<div class="ct-table-wrap">
			<div class="ct-arrow"><br /></div>
			<h3 class="ct-toggle"><span><?php esc_html_e('Benutzerdefinierte Typen importieren', 'kleinanzeigen'); ?></span></h3>
			<div class="inside">
				<table class="form-table">
					<tr>
						<th>
							<?php esc_html_e('Füge hier Deinen Exportcode ein und klicke auf Importieren, um den benutzerdefinierten Typ zu CustomPress hinzuzufügen', 'kleinanzeigen'); ?>
						</th>
						<td>
							<?php wp_nonce_field('import'); ?>
							<?php if( defined('CT_ALLOW_IMPORT') ): ?>
							<textarea id="ct_import" name="ct_import" rows="6" cols="80" ><?php echo esc_textarea($import); ?></textarea>
							<?php else: ?>
							<span class="description"><?php _e("Der Import ist derzeit auf dieser Seite deaktiviert. Zum Aktivieren füge die Zeile<br /><code>define('CT_ALLOW_IMPORT', true);</code><br />der wp-config.php file hinzu.", 'kleinanzeigen'); ?></span>
							<span class="description"><?php esc_html_e("Entferne die Zeile, wenn sie nicht mehr benötigt wird, um mögliche Sicherheitsprobleme zu vermeiden.", 'kleinanzeigen'); ?></span>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<th>
						</th>
						<td>
							<?php if( defined('CT_ALLOW_IMPORT') ): ?>
							<input type="submit" class="button" value="<?php esc_html_e('Importieren', 'kleinanzeigen'); ?>" />
							<?php endif; ?>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</form>
</div>

<?php

function post_types_export($post_types = array() ){

	if(empty($_POST['pt']) ) return '';

	$output = "// Post Types Export code for CustomPress\n";
	$output .= 'global $CustomPress_Core;';
	$output .= "\n" . '$CustomPress_Core->import=';

	$export = array();

	foreach($_POST['pt'] as $key => $value){

		$export['post_types'][$key] = $post_types[$key];

	}

	$output .= var_export($export, true) . ";" ;

	return $output;

}

function taxonomies_export($taxonomies = array() ){

	if(empty($_POST['tx']) ) return '';

	$output = "// Taxonomies Export code for CustomPress\n";
	$output .= 'global $CustomPress_Core;';
	$output .= "\n" . '$CustomPress_Core->import=';

	$export = array();

	foreach($_POST['tx'] as $key => $value){

		$export['taxonomies'][$key] = $taxonomies[$key];

	}

	$output .= var_export($export, true) . ";" ;

	return $output;

}

function custom_fields_export($custom_fields = array() ){

	if(empty($_POST['cf']) ) return '';

	$output = "// Custom Fields Export code for CustomPress\n";
	$output .= 'global $CustomPress_Core;';
	$output .= "\n" . '$CustomPress_Core->import=';

	$export = array();

	foreach($_POST['cf'] as $key => $value){

		$export['custom_fields'][$key] = $custom_fields[$key];

	}

	$output .= var_export($export, true) . ";" ;

	return $output;

}
