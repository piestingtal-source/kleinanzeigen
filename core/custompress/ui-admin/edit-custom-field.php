<?php if (!defined('ABSPATH')) die('Kein direkter Zugriff erlaubt!'); ?>

<?php

$post_types = get_post_types('','names');

if(is_network_admin()){
$custom_field = $this->network_custom_fields[$_GET['ct_edit_custom_field']];
} else {
$custom_field = $this->custom_fields[$_GET['ct_edit_custom_field']];
}
?>
<div class="wrap">
	<h3><?php esc_html_e('Benutzerdefiniertes Feld bearbeiten', 'kleinanzeigen'); ?></h3>
	<form action="" method="post" class="ct-custom-fields">
		<?php wp_nonce_field( 'ct_submit_custom_field_verify', 'ct_submit_custom_field_secret' ); ?>
		<div class="ct-wrap-left">
			<div class="ct-table-wrap">
				<div class="ct-arrow"><br></div>
				<h3 class="ct-toggle"><?php esc_html_e('Feldtitel', 'kleinanzeigen') ?></h3>
				<table class="form-table">
					<tr>
						<th>
							<label for="field_title"><?php esc_html_e('Feldtitel', 'kleinanzeigen') ?> <span class="ct-required">( <?php esc_html_e('erforderlich', 'kleinanzeigen'); ?> )</span></label>
						</th>
						<td>
							<input type="text" name="field_title" value="<?php echo esc_attr( $custom_field['field_title'] ); ?>" />
							<br /><span class="description"><?php esc_html_e('Der Titel des benutzerdefinierten Felds.', 'kleinanzeigen'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="field_required"><?php esc_html_e('Pflichtfeld', 'kleinanzeigen') ?></label>
						</th>
						<td>
							<input type="hidden" name="field_required" value="0" >
							<input type="checkbox" name="field_required" value="2" <?php checked( isset( $custom_field['field_required'] ) && ! empty($custom_field['field_required']) ); ?> >
							<span class="description"><?php esc_html_e('Mache dies zu einem Pflichtfeld.', 'kleinanzeigen'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="field_message"><?php esc_html_e('Erforderliche Eingabeaufforderung für Feldfehler', 'kleinanzeigen') ?></label><br />
						</th>
						<td>
							<input type="text" id="field_message" name="field_message" value="<?php if ( isset( $custom_field['field_message'] ) ) echo esc_attr( $custom_field['field_message'] ); ?>" />
							<br /><span class="description"><?php esc_html_e('Benutzerdefiniertes erforderliches Feld Fehleraufforderung für dieses Feld oder leer lassen für Standard.', 'kleinanzeigen') ?></span><br />
						</td>
					</tr>
					<tr>
						<th>
							<label for="field_title"><?php esc_html_e('Für WP/Plugins zulassen', 'kleinanzeigen') ?> <br /><span class="ct-required">(<?php esc_html_e("kann nicht geändert werden", 'kleinanzeigen') ?>)</span></label>
						</th>
						<td>
							<input type="hidden" name="field_wp_allow"  readonly="readonly" value="<?php echo ( isset( $custom_field['field_wp_allow'] ) && 1 == $custom_field['field_wp_allow'] ) ? '2' : '0'; ?>" />
							<input type="checkbox" disabled value="1" <?php checked( isset( $custom_field['field_wp_allow'] ) && 1 == $custom_field['field_wp_allow'] ); ?> />
							<span class="description"><?php esc_html_e('WP und andere Plugins können dieses benutzerdefinierte Feld verwenden.', 'kleinanzeigen'); ?></span>
						</td>
					</tr>
				</table>
			</div>
			<div class="ct-table-wrap">
				<div class="ct-arrow"><br></div>
				<h3 class="ct-toggle"><?php esc_html_e('Feldtyp', 'kleinanzeigen') ?></h3>
				<table class="form-table">
					<tr>
						<th>
							<label for="field_type"><?php esc_html_e('Feldtyp', 'kleinanzeigen') ?> <span class="ct-required">( <?php esc_html_e('erforderlich', 'kleinanzeigen'); ?> )</span></label>
						</th>
						<td>
							<select name="field_type">
								<option value="editor" <?php selected( isset( $_POST['field_type'] ) && $_POST['field_type'] == 'editor' ); ?>><?php esc_html_e('WP Editor', 'kleinanzeigen'); ?></option>
								<option value="text" <?php selected( isset( $custom_field['field_type']) && $custom_field['field_type'] == 'text' ); ?>><?php esc_html_e('Textfeld', 'kleinanzeigen'); ?></option>
								<option value="textarea" <?php selected( isset( $custom_field['field_type'] ) && $custom_field['field_type'] == 'textarea' ); ?>><?php esc_html_e('Mehrzeiliges Textfeld', 'kleinanzeigen'); ?></option>
								<option value="radio" <?php selected( isset( $custom_field['field_type'] ) && $custom_field['field_type'] == 'radio' ); ?>><?php esc_html_e('Radio Buttons', 'kleinanzeigen'); ?></option>
								<option value="checkbox" <?php selected( isset( $custom_field['field_type'] ) && $custom_field['field_type'] == 'checkbox' ); ?>><?php esc_html_e('Checkboxes', 'kleinanzeigen'); ?></option>
								<option value="selectbox" <?php selected( isset( $custom_field['field_type'] ) && $custom_field['field_type'] == 'selectbox' ); ?>><?php esc_html_e('Dropdown-Auswahlfeld', 'kleinanzeigen'); ?></option>
								<option value="multiselectbox" <?php selected( isset( $custom_field['field_type'] ) && $custom_field['field_type'] == 'multiselectbox' ); ?>><?php esc_html_e('Multi Select Box', 'kleinanzeigen'); ?></option>
								<option value="datepicker" <?php selected( isset( $custom_field['field_type'] ) && $custom_field['field_type'] == 'datepicker' ); ?>><?php esc_html_e('Datumsauswahl', 'kleinanzeigen'); ?></option>
								<option value="upload" <?php selected( isset( $custom_field['field_type'] ) && $custom_field['field_type'] == 'upload' ); ?>><?php esc_html_e('Upload', 'kleinanzeigen'); ?></option>
							</select>
							<br /><span class="description"><?php esc_html_e('Wähle den Typ des benutzerdefinierten Felds.', 'kleinanzeigen'); ?></span>

							<div class="ct-text-type-options">
								<h4><?php esc_html_e('Fülle die Optionen für dieses Feld aus', 'kleinanzeigen'); ?>:</h4>
								<p>
									<label for="field_regex"><?php esc_html_e('Validierung regulärer Ausdrücke', 'kleinanzeigen') ?></label><br />
									<textarea name="field_regex" rows="2" cols="50" ><?php if ( isset( $custom_field['field_regex'] ) ) echo esc_textarea($custom_field['field_regex']); ?></textarea>
									<br />
									<label for="field_regex_options"><?php esc_html_e('Optionen:', 'kleinanzeigen') ?></label>
									<input type="text" id="field_regex_options" name="field_regex_options" size="3" value="<?php if ( isset( $custom_field['field_regex_options'] ) ) echo esc_attr( $custom_field['field_regex_options'] ); ?>" />
									<br /><span class="description"><?php esc_html_e('i = Fall ignorieren, g = Global, m = mehrzeilig', 'kleinanzeigen') ?></span>
									<br /><span class="description"><?php esc_html_e('Gib einen regulären Ausdruck ein, um ihn zu validieren, oder lasse ihn leer. Beispiel für E-Mail:', 'kleinanzeigen') ?></span>
									<br /><span class="description"><?php esc_html_e('<code>^[\w.%+-]+@[\w.-]+\.[a-zA-Z]{2,4}$</code> <code>i</code>', 'kleinanzeigen') ?></span>
								</p>
								<p>
									<label for="field_regex_message"><?php esc_html_e('Fehlermeldung Überprüfung regulärer Ausdrücke', 'kleinanzeigen') ?></label><br />
									<input type="text" id="field_message" name="field_regex_message" size="55" value="<?php if ( isset( $custom_field['field_regex_message'] ) ) echo esc_attr( $custom_field['field_regex_message'] ); ?>" />
									<br /><span class="description"><?php esc_html_e('Fehlermeldung zur Überprüfung der benutzerdefinierten regulären Ausdrücke für dieses Feld oder lasse für die Standardeinstellung leer.', 'kleinanzeigen') ?></span><br />
								</p>
								<p>
									<?php esc_html_e('Standardwert', 'kleinanzeigen'); ?>
									<input type="text" name="field_default_option" size="3" value="<?php if ( isset( $custom_field['field_default_option'] ) ) echo esc_attr( $custom_field['field_default_option']); ?>" />
								</p>
							</div>

							<div class="ct-date-type-options">

								<?php


								$date_format = (isset( $custom_field['field_date_format'])) ? $custom_field['field_date_format'] : $this->get_options('date_format');
								$date_format = (is_array($date_format)) ? 'mm/dd/yy' : $date_format;

//								$this->jquery_ui_css(); //Load the current ui theme css

								?>
								<h4><?php esc_html_e('Fülle die Optionen für dieses Feld aus', 'kleinanzeigen'); ?>:</h4>
								<p>
									<input type="text" id="field_date_format" name="field_date_format" size="38" value="<?php echo esc_attr( $date_format ); ?>" onchange="jQuery('#datepicker').datepicker( 'option', 'dateFormat', this.value );"/>
									<br /><span class="description"><?php esc_html_e('Wähle die Option Datumsformat oder gib Deine eigene ein', 'kleinanzeigen') ?></span>
									<br /><br />
									<input class="pickdate" id="datepicker" type="text" size="38" value="" /><br />
									<span class="description"><?php esc_html_e('Datumsauswahl Beispiel', 'kleinanzeigen') ?></span>
								</p>

							</div>
							<div class="ct-field-type-options">
								<h4><?php esc_html_e('Fülle die Optionen für dieses Feld aus', 'kleinanzeigen'); ?>:</h4>
								<p>
									<?php esc_html_e('Ordnen nach', 'kleinanzeigen'); ?> :
									<?php if( empty( $custom_field['field_sort_order']) ) $custom_field['field_sort_order'] = 'default';?>
									<select name="field_sort_order">
										<option value="default" <?php selected($custom_field['field_sort_order'], 'default'); ?> ><?php esc_html_e('Ordnen eingegeben', 'kleinanzeigen'); ?></option>
										<option value="asc" <?php selected($custom_field['field_sort_order'], 'asc'); ?>><?php esc_html_e('Name - Aufsteigend', 'kleinanzeigen'); ?></option>
										<option value="desc"<?php selected($custom_field['field_sort_order'], 'desc'); ?>><?php esc_html_e('Name - Absteigend', 'kleinanzeigen'); ?></option>
									</select>
								</p>

								<?php if ( isset( $custom_field['field_options'] ) && is_array( $custom_field['field_options'] )): ?>
								<?php foreach ( $custom_field['field_options'] as $key => $field_option ): ?>
								<p>
									<?php esc_html_e('Option', 'kleinanzeigen'); ?> <?php echo esc_html( $key ); ?>:
									<input type="text" name="field_options[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $field_option ); ?>" />
									<input type="radio" value="<?php echo esc_attr( $key ); ?>" name="field_default_option" <?php if ( $custom_field['field_default_option'] == $key ) echo ( 'checked="checked"' ); ?> />
									<?php esc_html_e('Standardwert', 'kleinanzeigen'); ?>
									<?php if ( $key != 1 ): ?>
									<a href="#" class="ct-field-delete-option">[x]</a>
									<?php endif; ?>
								</p>
								<?php endforeach; ?>
								<?php else: ?>
								<p><?php esc_html_e('Option', 'kleinanzeigen'); ?> 1:
									<input type="text" name="field_options[1]" value="<?php if ( isset( $custom_field['field_options'][1] ) ) echo esc_attr( $custom_field['field_options'][1] ); ?>" />
									<input type="radio" value="1" name="field_default_option" <?php if ( isset( $custom_field['field_default_option'] ) && $custom_field['field_default_option'] == '1' ) echo 'checked="checked"'; ?> />
									<?php esc_html_e('Standardwert', 'kleinanzeigen'); ?>
								</p>
								<?php endif; ?>

								<div class="ct-field-additional-options"></div>
								<input type="hidden" value="<?php echo ( isset( $custom_field['field_options'] ) ) ? count( $custom_field['field_options'] ) : '1'; ?>" name="track_number" />
								<p><a href="#" class="ct-field-add-option"><?php esc_html_e('Füge eine weitere Option hinzu', 'kleinanzeigen'); ?></a></p>
							</div>
						</td>
					</tr>
				</table>
			</div>
			<div class="ct-table-wrap">
				<div class="ct-arrow"><br></div>
				<h3 class="ct-toggle"><?php esc_html_e('Feld Beschreibung', 'kleinanzeigen') ?></h3>
				<table class="form-table">
					<tr>
						<th>
							<label for="field_description"><?php esc_html_e('Feld Beschreibung', 'kleinanzeigen') ?></label>
						</th>
						<td>
							<textarea name="field_description" cols="52" rows="3" ><?php echo esc_textarea( $custom_field['field_description'] ); ?></textarea>
							<br /><span class="description"><?php esc_html_e('Beschreibung für das benutzerdefinierte Feld.', 'kleinanzeigen'); ?></span>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="ct-wrap-right">
			<div class="ct-table-wrap">
				<div class="ct-arrow"><br></div>
				<h3 class="ct-toggle"><?php esc_html_e('Beitragstyp', 'kleinanzeigen') ?></h3>
				<table class="form-table">
					<tr>
						<th>
							<label for="object_type"><?php esc_html_e('Beitragstyp', 'kleinanzeigen') ?> <span class="ct-required">( <?php esc_html_e('erforderlich', 'kleinanzeigen'); ?> )</span></label>
						</th>
						<td>
							<select name="object_type[]" multiple="multiple" class="ct-object-type">
								<?php if ( !empty( $post_types ) ): ?>
								<?php foreach( $post_types as $post_type ): ?>
								<option value="<?php echo esc_attr( $post_type ); ?>" <?php foreach ( $custom_field['object_type'] as $key => $object_type ) { if ( $object_type == $post_type ) echo( 'selected="selected"' ); } ?>><?php echo esc_html( $post_type ); ?></option>
								<?php endforeach; ?>
								<?php endif; ?>
							</select>
							<br />
							<span class="description"><?php esc_html_e('Wähle einen oder mehrere Beitragstypen aus, denen dieses benutzerdefinierte Feld hinzugefügt werden soll.', 'kleinanzeigen'); ?></span>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="ct-wrap-right">
			<div class="ct-table-wrap">
				<div class="ct-arrow"><br></div>
				<h3 class="ct-toggle"><?php esc_html_e('Eingabe für diesen Beitragstyp ausblenden', 'kleinanzeigen') ?></h3>
				<table class="form-table">
					<tr>
						<th>
							<label for="hide_type"><?php esc_html_e('Beitragstyp', 'kleinanzeigen') ?></label>
						</th>
						<td>
							<select name="hide_type[]" multiple="multiple" class="ct-object-type">
								<?php if ( !empty( $post_types ) ): ?>
								<?php foreach( $post_types as $post_type ): ?>
								<option value="<?php echo esc_attr( $post_type ); ?>" <?php if( isset($custom_field['hide_type']) && is_array( $custom_field['hide_type'] ) ) { foreach ( $custom_field['hide_type'] as $key => $hide_type ) { if ( $hide_type == $post_type ) echo( 'selected="selected"' ); } } ?>><?php echo esc_html( $post_type ); ?></option>
								<?php endforeach; ?>
								<?php endif; ?>
							</select>
							<br />
							<span class="description"><?php esc_html_e('Um dieses Eingabefeld auf der Admin-Bearbeitungsseite für einen Beitragstyp auszublenden, wähle einen oder mehrere auszublendende Beitragstypen aus.', 'kleinanzeigen'); ?></span>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<br style="clear: left" />
		<p class="submit">
			<?php wp_nonce_field( 'submit_custom_field' ); ?>
			<input type="submit" class="button-primary" name="submit" value="<?php esc_html_e('Benutzerdefiniertes Feld aktualisieren', 'kleinanzeigen'); ?>" />
		</p>
		<br /><br /><br /><br />
	</form>

</div>

<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('#datepicker').datepicker({ dateFormat : '<?php echo esc_js( $date_format); ?>' });
		jQuery('#datepicker').attr('value', jQuery.datepicker.formatDate('<?php echo esc_js($date_format); ?>', new Date(), {}) );
	});
</script>

