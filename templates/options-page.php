<?php
/**
 * Live search options page
 *
 * @package Live search options page
 */

defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

?>

<div class="wrap">
	<div class="easy-options-form-wrap clearfix">

		<h1><?php esc_html_e( 'Docs Settings', 'easy-docs' ); ?></h1>
		<form method="post" action="options.php"> 
				<h2 class="title"><?php _e( 'Live Search', 'easy-docs' ); ?></h2>
				<p><?php _e( "Settings to control the live search functionality & it's search area.", 'easy-docs' ); ?></p>
					<?php settings_fields( 'easy-docs-settings-group' ); ?>
					<?php do_settings_sections( 'easy-docs-settings-group' ); ?>

					<table  class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e( 'Enable Live Search', 'easy-docs' ); ?></th>
							<td>
								<?php
								$checked        = '';
								$easy_ls_enabled = get_option( 'easy_ls_enabled' );
								$checked        = ( false === $easy_ls_enabled ) ? " checked='checked' " : ( ( 1 == $easy_ls_enabled ) ? " checked='checked' " : '' );
								?>
								<input type="checkbox" <?php echo $checked; ?> name="easy_ls_enabled" value="1" <?php echo checked( 1, $checked, false ); ?> />
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e( 'Search Within Post Types', 'easy-docs' ); ?></th>
							<td>	
								<fieldset>
									<?php

									$selected_post_types = get_option( 'easy_search_post_types' );

									$selected_post_types = ! $selected_post_types ? array( 'docs' ) : $selected_post_types;

									$post_types = get_post_types(
										array(
											'public'  => true,
											'show_ui' => true,
										),
										'objects'
									);

									unset( $post_types['attachment'] );
									unset( $post_types['fl-builder-template'] );
									unset( $post_types['fl-theme-layout'] );

									foreach ( $post_types as $key => $post_type ) {
										?>
										<input type="checkbox" 
										<?php
										if ( in_array( $key, $selected_post_types ) ) {
											echo "checked='checked' "; }
										?>
name="easy_search_post_types[]" value="<?php echo esc_attr( $key ); ?>" />
										<label>
											<?php echo ucfirst( $post_type->label ); ?>
										</label><br>
									<?php } ?>
								</fieldset>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e( 'Enable built-in single page template', 'easy-docs' ); ?></th>
							<td>
								<?php
								$checked                      = '';
								$easy_docs_override_single_template = get_option( 'easy_docs_override_single_template' );
								$checked                      = ( false === $easy_docs_override_single_template ) ? " checked='checked' " : ( ( 1 == $easy_docs_override_single_template ) ? " checked='checked' " : '' );
								?>
								<input type="checkbox" <?php echo $checked; ?> name="easy_docs_override_single_template" value="1" <?php echo checked( 1, $checked, false ); ?> />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Enable built-in category & tag page template', 'easy-docs' ); ?></th>
							<td>
								<?php
								$checked                        = '';
								$easy_docs_override_category_template = get_option( 'easy_docs_override_category_template' );
								$checked                        = ( false === $easy_docs_override_category_template ) ? " checked='checked' " : ( ( 1 == $easy_docs_override_category_template ) ? " checked='checked' " : '' );
								?>
								<input type="checkbox" <?php echo $checked; ?> name="easy_docs_override_category_template" value="1" <?php echo checked( 1, $checked, false ); ?> />
							</td>
						</tr>	
						<tr valign="top">
							<th scope="row"><?php _e( "Turn Off Doc's Comments", 'easy-docs' ); ?></th>
							<td>
								<?php
								$checked                 = '';
								$easy_search_has_comments = get_option( 'easy_search_has_comments' );
								$checked                 = ( false === $easy_search_has_comments ) ? " checked='checked' " : ( ( 1 == $easy_search_has_comments ) ? " checked='checked' " : '' );

								?>
								<input type="checkbox" <?php echo $checked; ?> name="easy_search_has_comments" value="1" <?php echo checked( 1, $checked, false ); ?> />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Doc Archive Page Title', 'easy-docs' ); ?></th>
							<td>
								<input type="text" class="regular-text code" name="easy_doc_title" value="<?php echo get_option( 'easy_doc_title' ); ?> "/>
							</td>
						</tr>	


					</table>
						<?php submit_button(); ?>
		</form>
	</div>
	<div class="easy-shortcodes-wrap">

		<h2 class="title"><?php _e( 'Shortcodes', 'easy-docs' ); ?></h2>
		<p><?php _e( 'Copy below shortcode and paste it into your post, page, or text widget.', 'easy-docs' ); ?></p>

		<div class="easy-shortcode-container">
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e( 'Display Live Search Box', 'easy-docs' ); ?></th>
					<td>
						<div class="easy-shortcode-container wp-ui-text-highlight">
							[easy_doc_wp_live_search placeholder="Please Type Your Question Here"]
						</div>  
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( "Display Doc's Category List", 'easy-docs' ); ?></th>
					<td>
						<div class="easy-shortcode-container wp-ui-text-highlight">
							[easy_doc_wp_category_list]
						</div>  
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>

