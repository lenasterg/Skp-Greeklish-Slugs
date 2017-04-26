<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;

/**
 * Add option page at settings menu
 *
 */
function skp_greeklish_slugs_add_page() {
    add_options_page(
        __( 'Greeklish Slugs', 'skp_greeklish_slugs' ),
        __( 'Greeklish Slugs', 'skp_greeklish_slugs' ),
        'manage_options',
        'skp-greeklish-slugs',
        'skp_greeklish_slugs_cb'
    );
}
add_action( 'admin_menu', 'skp_greeklish_slugs_add_page' );

/**
 * Add the settings form
 *
 */
function skp_greeklish_slugs_cb() {
    ?>
    <div>
        <form action="options.php" method="post">
            <?php settings_fields( 'skp_greeklish_slugs' ); ?>
            <?php do_settings_sections( 'skp-greeklish-slugs' ); ?>
            <input name="Submit" type="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Changes', 'skp_greeklish_slugs' ); ?>" />
        </form>
    </div>
    <?php
}


add_action( 'update_option_skp_greeklish_slugs', 'skp_greeklish_slugs_runonce', 10, 2 );
/**
*@author lenasterg
*@todo as array of postsId use users defined posts
*/
function skp_greeklish_slugs_runonce( $old_value, $new_value ) {
	$old_runOnce = $old_value['runonceforall'];
	$new_runOnce = $new_value['runonceforall'];
    if ($old_runOnce==$new_runOnce) {
		return false;
	}
	else {
	if ($new_runOnce=='1' ) {
        $postsId=array('55','2');
		skp_greeklish_slugs_update_all($postsId);
		}
	}
}

/**
 * Register Settings
 *
 */
add_action( 'admin_init', 'register_settings_skp_greeklish_slugs' );
function register_settings_skp_greeklish_slugs() {
    register_setting( 'skp_greeklish_slugs', 'skp_greeklish_slugs', 'skp_greeklish_slugs_options_validate' );
    add_settings_section(
         'skp_greeklish_slugs_main_section',
         __( 'Greeklish Slugs Options', 'skp_greeklish_slugs' ),
         'skp_greeklish_slugs_main_section_cb',
         'skp-greeklish-slugs'
    );
    add_settings_field(
        'skp_greeklish_slugs_char',
        __( 'Remove one letter words', 'skp_greeklish_slugs' ),
        'skp_greeklish_slugs_char_cb',
        'skp-greeklish-slugs',
        'skp_greeklish_slugs_main_section'
    );
    add_settings_field(
        'skp_greeklish_slugs_chars',
        __( 'Remove two letter words', 'skp_greeklish_slugs' ),
        'skp_greeklish_slugs_chars_cb',
        'skp-greeklish-slugs',
        'skp_greeklish_slugs_main_section'
    );
    add_settings_field(
        'skp_greeklish_slugs_stopw',
        __( 'Stop words<br/> <p style="font-weight: normal;">Enter words that you want to remove from slugs.</p>', 'skp_greeklish_slugs' ),
        'skp_greeklish_slugs_stopw_cb',
        'skp-greeklish-slugs',
        'skp_greeklish_slugs_main_section'
    );
    add_settings_field(
        'skp_greeklish_slugs_runonceforall',
        __( 'Run once for every posts<br/> ', 'skp_greeklish_slugs' ),
        'skp_greeklish_slugs_runonceforall_cb',
        'skp-greeklish-slugs',
        'skp_greeklish_slugs_main_section'
    );
    add_settings_field(
        'skp_greeklish_slugs_delete_options',
        __( 'Delete options on plugin uninstall?', 'skp_greeklish_slugs' ),
        'skp_greeklish_slugs_delete_options_cb',
        'skp-greeklish-slugs',
        'skp_greeklish_slugs_main_section'
    );
}

/**
 * Settings section
 *
 */
function skp_greeklish_slugs_main_section_cb() {
    echo '<b>' . __( 'Options', 'skp_greeklish_slugs' ) . '</b><hr>';
}

/**
 * Add one character checkbox
 *
 */
function skp_greeklish_slugs_char_cb() {
    $options = get_option( 'skp_greeklish_slugs' );
    $checked = esc_attr( $options['char'] );
    ?>
    <input type="checkbox"
        name="skp_greeklish_slugs[char]"
        id="skp_greeklish_slugs_char"
        value="1"
        <?php checked( $checked, 1 ); ?>
    />
    <?php
}

/**
 * Add two characters checkbox
 *
 */
function skp_greeklish_slugs_chars_cb() {
    $options = get_option( 'skp_greeklish_slugs' );
    $checked = esc_attr( $options['chars'] );
    ?>
    <input type="checkbox"
        name="skp_greeklish_slugs[chars]"
        id="skp_greeklish_slugs_chars"
        value="1"
        <?php checked( $checked, 1 ); ?>
    />
    <?php
}

/**
 * Add stopwords textarea
 *
 */
function skp_greeklish_slugs_stopw_cb() {
    $options = get_option( 'skp_greeklish_slugs' );
    $stopw = $options['stopw'] != '' ? esc_attr( $options['stopw'] ) : '';
    ?>
    <textarea name="skp_greeklish_slugs[stopw]" id="skp_greeklish_slugs_stopw" cols="60" rows="4"><?php echo esc_textarea( $stopw ); ?></textarea>
    <?php
}

/**
 * Add RunOnce checkbox
 *@author lenasterg
 *
 */
function skp_greeklish_slugs_runonceforall_cb() {
    $options = get_option( 'skp_greeklish_slugs' );
    $checked = esc_attr( $options['runonceforall'] );
	if ('1' == $checked ) { ?>
	You have done it already once. 
	<input type="hidden"   name="skp_greeklish_slugs[runonceforall]"
        id="skp_greeklish_slugs_runonceforall" value="<?php echo $checked;?>"/>
    <?php   } else {?>
	<p style="font-weight: normal;">Caution: This is NOT REVERSABLE.</p>
    <input type="checkbox"
        name="skp_greeklish_slugs[runonceforall]"
        id="skp_greeklish_slugs_runonceforall"
        value="1"  />
	<?php }
}

/**
 * Delete options on uninstall
 *
 */
function skp_greeklish_slugs_delete_options_cb() {
    $options = get_option( 'skp_greeklish_slugs' );
    $checked = esc_attr( $options['delete_option_data'] );
    ?>
    <input type="checkbox"
        name="skp_greeklish_slugs[delete_option_data]"
        id="skp_greeklish_slugs_delete_option_data"
        value="1"
        <?php checked( $checked, 1 ); ?>
    />
    <?php
}

/**
 * Validation
 */
function skp_greeklish_slugs_options_validate($input) {
    $options = get_option( 'skp_greeklish_slugs' );
    $options['char']               = filter_var( $input['char'], FILTER_SANITIZE_NUMBER_INT );
    $options['chars']              = filter_var( $input['chars'], FILTER_SANITIZE_NUMBER_INT );
    $options['stopw']              = sanitize_text_field( $input['stopw'] );
    $options['runonceforall']      = sanitize_text_field( $input['runonceforall'] );
    $options['delete_option_data'] = filter_var( $input['delete_option_data'], FILTER_SANITIZE_NUMBER_INT );

    return $options;
}

/**
*@author lenasterg
*/
function skp_greeklish_slugs_update_all($arrayOfPostsIds) {
	$args = array(
	'include'=>$arrayOfPostsIds);
    $posts = get_posts($args);
	
	 foreach ( $posts as $post ) {
		$my_post=$post;

		$my_post->post_name=sanitize_title($post->post_title);

		 wp_update_post( $my_post,true );
	
	    }
	add_action( 'admin_notices', 'skp_greeklish_slugs_update_all_done_notice' );
}

/**
*@author lenasterg
*/
function skp_greeklish_slugs_update_all_done_notice() {
	$class = 'notice notice-success';
	$message = __( 'Done! All Slugs are updated', 'skp_greeklish_slugs' );
	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
}
