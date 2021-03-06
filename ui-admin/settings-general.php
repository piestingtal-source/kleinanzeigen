<?php if (!defined('ABSPATH')) die('Kein direkter Zugriff erlaubt!');

global $wp_roles;
$options = $this->get_options( 'general' );
$default_email = __(
'Hallo TO_NAME, du hast eine Nachricht erhalten von

  Name: FROM_NAME
  Email: FROM_EMAIL
  Betreff: FROM_SUBJECT
  Nachricht:

  FROM_MESSAGE


  Kleinanzeigen-Link: POST_LINK
', 'kleinanzeigen');

?>

<div class="wrap">

	<?php $this->render_admin( 'navigation', array( 'page' => 'kleinanzeigen_settings', 'tab' => 'general' ) ); ?>
	<?php $this->render_admin( 'message' ); ?>

	<h1><?php _e( 'Allgemeine Einstellungen', 'kleinanzeigen' ); ?></h1>

	<form action="#" method="post">
		<div class="postbox">
			<h3 class='hndle'><span><?php _e( 'Kleinanzeigen Mitgliedsrolle', 'kleinanzeigen' ); ?></span></h3>
			<div class="inside">
				<table class="form-table">
					<tr>
						<th>
							<label for="roles"><?php _e( 'Mitgliedsrolle zuweisen', 'kleinanzeigen' ) ?></label>
						</th>
						<td>
							<select id="member_role" name="member_role" style="width:200px;">
							<?php wp_dropdown_roles(@$options['member_role']); ?>
							</select>
							<br /><span class="description"><?php _e('Wähle die Rolle aus, der Du bei der Anmeldung ein Kleinanzeigen-Mitglied zuweisen möchtest.', 'kleinanzeigen'); ?></span>
							<br /><span class="description"><?php _e('Wenn Du mehrere Plugins mit Anmeldungen ausführst, verwende dieselbe Rolle für beide.', 'kleinanzeigen'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label>Mitgliederrollen verwalten</label>
						</th>
						<td>
							<label>Rollennamen hinzufügen</label><br />
							<input type="text" id="new_role" name="new_role" size="30"/>
							<input type="submit" class="button" id="add_role" name="add_role" value="<?php _e( 'Rolle hinzufügen', 'kleinanzeigen' ); ?>" />
							<br /><span class="description"><?php _e('Füge eine neue Rolle hinzu. Nur alphanumerische Zeichen.', 'kleinanzeigen'); ?></span>
							<br /><span class="description"><?php _e('Wenn Du eine neue Rolle hinzufügst, musst Du die entsprechenden Funktionen hinzufügen, damit sie funktionsfähig ist.', 'kleinanzeigen'); ?></span>
							<br /><br />
							<label>Benutzerdefinierte Rollen</label><br />
							<select id="delete_role" name="delete_role"  style="width:200px;">
								<?php
								global $wp_roles;
								$system_roles = array('administrator', 'editor', 'author', 'contributor', 'subscriber');
								$role_names = $wp_roles->role_names;
								foreach ( $role_names as $role => $name ):
								if(! in_array($role, $system_roles) ): //Don't delete system roles.
								?>
								<option value="<?php echo $role; ?>"><?php echo $name; ?></option>
								<?php
								endif;
								endforeach;
								?>
							</select>
							<input type="button" class="button" onclick="jQuery(this).hide(); jQuery('#remove_role').show();" value="<?php _e( 'Eine Rolle entfernen', 'kleinanzeigen' ); ?>" />
							<input type="submit" class="button-primary" id="remove_role" name="remove_role" value="<?php _e( 'Bestätige Diese Rolle entfernen', 'kleinanzeigen' ); ?>" style="display: none;" />

						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="postbox">
			<h3 class='hndle'><span><?php _e( 'Statusoptionen für Kleinanzeigen', 'kleinanzeigen' ) ?></span></h3>
			<div class="inside">
				<table class="form-table">
					<tr>
						<th>
							<label for="moderation"><?php _e('Verfügbare Kleinanzeigen Statusoptionen', 'kleinanzeigen' ) ?></label>
						</th>
						<td>
							<label><input type="checkbox" name="moderation[publish]" value="1" <?php checked( ! empty($options['moderation']['publish']) ) ?> /> <?php _e('Veröffentlichen', 'kleinanzeigen'); ?></label>
							<br /><span class="description"><?php _e('Mitgliedern erlauben, Kleinanzeigen selbst zu veröffentlichen.', 'kleinanzeigen'); ?></span>
							<br /><label><input type="checkbox" name="moderation[pending]" value="1" <?php checked( ! empty($options['moderation']['pending']) ) ?> /> <?php _e('Ausstehende Bewertung', 'kleinanzeigen'); ?></label>
							<br /><span class="description"><?php _e('Kleinanzeige steht zur Überprüfung durch einen Administrator aus.', 'kleinanzeigen' ); ?></span>
							<br /><label><input type="checkbox" name="moderation[draft]" value="1" <?php checked( ! empty($options['moderation']['draft']) ) ?> /> <?php _e('Entwurf', 'kleinanzeigen'); ?></label>
							<br /><span class="description"><?php _e('Mitgliedern erlauben, Entwürfe zu speichern.', 'kleinanzeigen'); ?></span>
						</td>
					</tr>
				</table>
			</div>
		</div>


		<div class="postbox">
			<h3 class='hndle'><span><?php _e( 'Formularfelder', 'kleinanzeigen' ); ?></span></h3>
			<div class="inside">

				<table class="form-table">
					<tr>
						<th><label for="field_image_req"><?php _e( 'Bildfeld:', 'kleinanzeigen' ); ?></label></th>
						<td>
							<input type="hidden" name="field_image_req" value="0" />
							<label>
							<input type="checkbox" id="field_image_req" name="field_image_req" value="1" <?php checked( isset( $options['field_image_req'] ) && 1 == $options['field_image_req'] ); ?> />
							<span class="description"><?php _e( 'nicht benötigt', 'kleinanzeigen' ); ?></span>
							</label>
						</td>
					</tr>
					<tr>
						<th><label for="media_manager"><?php _e( 'Medien-Manager:', 'kleinanzeigen' ); ?></label></th>
						<td>
							<input type="hidden" name="media_manager" value="0" />
							<label>
							<input type="checkbox" id="media_manager" name="media_manager" value="1" <?php checked(isset( $options['media_manager'] ) && 1 == $options['media_manager'] ); ?> />
							<span class="description"><?php _e( 'Vollen Medienmanager für Feature-Image-Uploads aktivieren', 'kleinanzeigen' ); ?></span>
							</label>
						</td>
					</tr>
					<tr>
						<th><label for="field_image_def"><?php _e( 'Standardbild (URL) verwenden:', 'kleinanzeigen' ); ?></label></th>
						<td>
							<input type="text" id="field_image_def" name="field_image_def" size="70" value="<?php echo ( isset( $options['field_image_def'] ) && '' != $options['field_image_def'] ) ? $options['field_image_def'] : ''; ?>" />
							<br />
							<span class="description"><?php _e( 'dieses Bild wird für alle Anzeigen ohne Bilder angezeigt', 'kleinanzeigen' ); ?></span>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="postbox">
			<h3 class='hndle'><span><?php _e( 'Anzeigeoptionen', 'kleinanzeigen' ) ?></span></h3>
			<div class="inside">
				<table class="form-table">
					<tr>
						<th>
							<label for="count_cat"><?php _e( 'Anzahl der Kategorien:', 'kleinanzeigen' ) ?></label>
						</th>
						<td>
							<input type="text" name="count_cat" id="count_cat" value="<?php echo (empty( $options['count_cat'] ) ) ? '10' : $options['count_cat']; ?>" size="2" />
							<span class="description"><?php _e( 'eine Reihe von Kategorien, die in der Liste der Kategorien angezeigt werden.', 'kleinanzeigen' ) ?></span>
						</td>
					</tr>

					<tr>
						<th><label for="display_parent_count"><?php esc_html_e( 'Anzeigeanzahl in übergeordneten Kategorien:', 'kleinanzeigen' ); ?></label></th>
						<td>
							<input type="hidden" name="display_parent_count" value="0" />
							<label>
							<input type="checkbox" id="display_parent_count" name="display_parent_count" value="1" <?php checked( isset( $options['display_parent_count'] ) && 1 == $options['display_parent_count'] ); ?> />
							<span class="description"><?php esc_html_e( 'Zeigt die Anzahl der Einträge für die obersten übergeordneten Kategorien an', 'kleinanzeigen' ); ?></span>
							</label>
						</td>
					</tr>

					<tr>
						<th>
							<label for="count_sub_cat"><?php _e( 'Anzahl der Unterkategorien:', 'kleinanzeigen' ) ?></label>
						</th>
						<td>
							<input type="text" name="count_sub_cat" id="count_sub_cat" value="<?php echo ( empty( $options['count_sub_cat'] ) ) ? '5' : $options['count_sub_cat']; ?>" size="2" />
							<span class="description"><?php _e( 'eine Reihe von Unterkategorien, die für jede Kategorie in der Kategorienliste angezeigt werden.', 'kleinanzeigen' ) ?></span>
						</td>
					</tr>

					<tr>
						<th><label for="display_sub_count"><?php esc_html_e( 'Anzeigeanzahl für Unterkategorien:', 'kleinanzeigen' ); ?></label></th>
						<td>
							<input type="hidden" name="display_sub_count" value="0" />
							<label>
								<input type="checkbox" id="display_sub_count" name="display_sub_count" value="1" <?php checked( !isset( $options['display_sub_count'] ) || 1 == $options['display_sub_count'] ); ?> />
								<span class="description"><?php esc_html_e( 'Zeige die Anzahl der Einträge für Unterkategorien an', 'kleinanzeigen' ); ?></span>
							</label>
						</td>
					</tr>

					<tr>
						<th>
							<?php _e( 'Leere Unterkategorie:', 'kleinanzeigen' ) ?>
						</th>
						<td>
							<label>
							<input type="checkbox" name="hide_empty_sub_cat" id="hide_empty_sub_cat" value="1" <?php checked( empty( $options['hide_empty_sub_cat'] ) ? false : ! empty($options['hide_empty_sub_cat']) ); ?> />
							<span class="description"><?php _e( 'Leere Unterkategorie ausblenden', 'kleinanzeigen' ) ?></span>
							</label>
						</td>
					</tr>
					<?php
					/*
					<tr>
					<th>
					<?php _e( 'Display listing:', 'kleinanzeigen' ) ?>
					</th>
					<td>
					<input type="checkbox" name="display_listing" id="display_listing" value="1" <?php echo ( isset( $options['display_listing'] ) && '1' == $options['display_listing'] ) ? 'checked' : ''; ?> />
					<label for="display_listing"><?php _e( 'add Listings to align blocks according to height while  sub-categories are lacking', 'kleinanzeigen' ) ?></label>
					</td>
					</tr>
					*/
					?>
				</table>
			</div>
		</div>

		<div class="postbox">
			<h3 class='hndle'><span><?php _e( 'Paginierungseinstellungen', 'kleinanzeigen' ); ?></span></h3>
			<div class="inside">
				<table class="form-table">
					<tr>
						<th><label for="field_image_req"><?php _e( 'Paginierungsposition:', 'kleinanzeigen' ); ?></label></th>
						<td>
							<input type="hidden" name="pagination_top" value="0" />
							<label>
							<input type="checkbox" id="pagination_top" name="pagination_top" value="1" <?php echo ( isset( $options['pagination_top'] ) && 1 == $options['pagination_top'] ) ? 'checked' : ''; ?> />
							<span class="description"><?php _e( 'oben auf der Seite anzeigen.', 'kleinanzeigen' ); ?></span>
							</label>
							<br />
							<input type="hidden" name="pagination_bottom" value="0" />
							<label>
							<input type="checkbox" id="pagination_bottom" name="pagination_bottom" value="1" <?php echo ( isset( $options['pagination_bottom'] ) && 1 == $options['pagination_bottom'] ) ? 'checked' : ''; ?> />
							<span class="description"><?php _e( 'Anzeige am Ende der Seite.', 'kleinanzeigen' ); ?></span>
							</label>
						</td>
					</tr>
					<!--
					<tr>
					<th><label for="ads_per_page"><?php _e( 'Anzeigen pro Seite:', 'kleinanzeigen' ); ?></label></th>
					<td>
					<input type="text" id="ads_per_page" name="ads_per_page" size="4" value="<?php echo ( isset( $options['ads_per_page'] ) && '' != $options['ads_per_page'] ) ? $options['ads_per_page'] : '10'; ?>" />
					<br />
					<span class="description"><?php _e( 'Anzahl der auf jeder Seite angezeigten Anzeigen.', 'kleinanzeigen' ); ?></span>
					</td>
					</tr>
					-->
					<tr>
						<th><label for="pagination_range"><?php _e( 'Seitenzahlbereich:', 'kleinanzeigen' ); ?></label></th>
						<td>
							<input type="text" id="pagination_range" name="pagination_range" size="4" value="<?php echo ( isset( $options['pagination_range'] ) && '' != $options['pagination_range'] ) ? $options['pagination_range'] : '4'; ?>" />
							<span class="description"><?php _e( 'Anzahl der Seitenlinks, die gleichzeitig in der Paginierung angezeigt werden sollen', 'kleinanzeigen' ); ?></span>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="postbox">
			<h3 class='hndle'><span><?php _e( 'Benachrichtigungseinstellungen', 'kleinanzeigen' ); ?></span></h3>
			<div class="inside">
				<table class="form-table">
					<tr>
						<th><label for="disable_contact_form"><?php _e( 'Kontaktformular deaktivieren:', 'kleinanzeigen' ); ?></label></th>
						<td>
							<input type="hidden" name="disable_contact_form" value="0" />
							<label>
							<input type="checkbox" id="disable_contact_form" name="disable_contact_form" value="1" <?php checked( isset( $options['disable_contact_form'] ) && 1 == $options['disable_contact_form'] ); ?> />
							<span class="description"><?php _e( 'Kontaktformular deaktivieren', 'kleinanzeigen' ); ?></span>
							</label>
						</td>
					</tr>
					<tr>
						<th><label for="cc_admin"><?php _e( 'CC der Administrator:', 'kleinanzeigen' ); ?></label></th>
						<td>
							<input type="hidden" name="cc_admin" value="0" />
							<label>
							<input type="checkbox" id="cc_admin" name="cc_admin" value="1" <?php checked( isset( $options['cc_admin'] ) && 1 == $options['cc_admin'] ); ?> />
							<span class="description"><?php _e( 'cc der Administrator', 'kleinanzeigen' ); ?></span>
							</label>
						</td>
					</tr>
					<tr>
						<th><label for="cc_sender"><?php _e( 'CC den Absender:', 'kleinanzeigen' ); ?></label></th>
						<td>
							<input type="hidden" name="cc_sender" value="0" />
							<label>
							<input type="checkbox" id="cc_sender" name="cc_sender" value="1" <?php checked( isset( $options['cc_sender'] ) && 1 == $options['cc_sender'] ); ?> />
							<span class="description"><?php _e( 'cc der Absender', 'kleinanzeigen' ); ?></span>
							</label>
						</td>
					</tr>
					<tr>
						<th><label for="email_subject"><?php _e( 'E-Mail Betreff:', 'kleinanzeigen' ); ?></label></th>
						<td>
							<input class="cf-full" type="text" id="email_subject" name="email_subject" value="<?php echo ( isset( $options['email_subject'] ) && '' != $options['email_subject'] ) ? $options['email_subject'] : 'SITE_NAME Kontaktanfrage: FROM_SUBJECT [ POST_TITLE ]'; ?>" />
							<br />
							<span class="description"><?php _e( 'Variablen: TO_NAME, FROM_NAME, FROM_EMAIL, FROM_SUBJECT, FROM_MESSAGE, POST_TITLE, POST_LINK, SITE_NAME', 'kleinanzeigen' ); ?></span>
						</td>
					</tr>
					<tr>
						<th><label for="field_image_req"><?php _e( 'E-Mail-Inhalt:', 'kleinanzeigen' ); ?></label></th>
						<td>
							<textarea class="cf-full" id="email_content" name="email_content" rows="10" wrap="hard" ><?php
								echo esc_textarea( empty($options['email_content']) ? $default_email : $options['email_content'] );
							?></textarea>
							<br />
							<span class="description"><?php _e( 'Variablen: TO_NAME, FROM_NAME, FROM_EMAIL, FROM_SUBJECT, FROM_MESSAGE, POST_TITLE, POST_LINK, SITE_NAME', 'kleinanzeigen' ); ?></span>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<p class="submit">
			<?php wp_nonce_field( 'verify' ); ?>
			<input type="hidden" name="key" value="general" />
			<input type="submit" class="button-primary" name="save" value="<?php _e( 'Änderungen speichern', 'kleinanzeigen' ); ?>" />
		</p>
	</form>

</div>