<?php
add_action('admin_menu', 'mirakl_plugin_setup_menu');
function mirakl_plugin_setup_menu(){
    add_menu_page( 'Mirakl API', 'Mirakl API', 'manage_options', 'mirakl-api', 'mirakl_admin' );
}
function mirakl_register_settings() {
    register_setting(
        'mirakl_plugin_settings',
        'mirakl_plugin_settings',
        'mirakl_validate_plugin_settings'
    );
    add_settings_section(
        'section_one',
        'API settings',
        'mirakl_section_one_text',
        'mirakl_plugin'
    );

    add_settings_field(
        'mirakl_api_key',
        'API key',
        'mirakl_render_api_field',
        'mirakl_plugin',
        'section_one'
    );
    add_settings_field(
        'mirakl_site_url',
        'API url',
        'mirakl_render_url_field',
        'mirakl_plugin',
        'section_one'
    );
    add_settings_field(
        'mirakl_shop_id',
        'Shop ID',
        'mirakl_render_shop_id',
        'mirakl_plugin',
        'section_one'
    );
    add_settings_field(
        'last_date',
        'Last Mirakl data updated',
        'mirakl_render_date_field',
        'mirakl_plugin',
        'section_one'
    );

    register_setting(
        'mirakl_plugin_settings_2',
        'mirakl_plugin_settings_2',
        'mirakl_update_data'
    );
    add_settings_section(
        'section_two',
        'Update Data',
        'mirakl_section_two_text',
        'mirakl_plugin_2'
    );

    register_setting(
        'mirakl_plugin_settings_3',
        'mirakl_plugin_settings_3',
        'mirakl_clean_date'
    );
    add_settings_section(
        'section_three',
        'Clean last date',
        'mirakl_section_three_text',
        'mirakl_plugin_3'
    );
}
add_action( 'admin_init', 'mirakl_register_settings' );
function mirakl_validate_plugin_settings( $input ) {
    $output['mirakl_api_key']      = sanitize_text_field( $input['mirakl_api_key'] );
    $output['mirakl_site_url'] = sanitize_text_field( $input['mirakl_site_url'] );
    $output['mirakl_shop_id'] = sanitize_text_field( $input['mirakl_shop_id'] );
    $output['last_date'] = sanitize_text_field( $input['last_date'] );
    // ...
    return $output;
}
function mirakl_section_one_text() {
    echo '<p>Enter your API url and key</p>';
}
function mirakl_section_two_text() {
    echo '<p>Update your data manualy</p>';
}
function mirakl_section_three_text() {
    echo '<p>Clean last update date field (needed for manual testing, can be removed later)</p>';
}
function mirakl_render_api_field() {
    $options = get_option( 'mirakl_plugin_settings' );
    printf(
        '<input type="text" name="%s" value="%s" />',
        esc_attr( 'mirakl_plugin_settings[mirakl_api_key]' ),
        esc_attr( $options['mirakl_api_key'] )
    );
}
function mirakl_render_url_field() {
    $options = get_option( 'mirakl_plugin_settings' );
    printf(
        '<input type="text" name="%s" value="%s" />',
        esc_attr( 'mirakl_plugin_settings[mirakl_site_url]' ),
        esc_attr( $options['mirakl_site_url'] )
    );
}
function mirakl_render_shop_id() {
    $options = get_option( 'mirakl_plugin_settings' );
    printf(
        '<input type="text" name="%s" value="%s" />
<div class="subtext">Your shop id is <strong>2092</strong> and <strong>test id is <strong>2913</strong></div>',
        esc_attr( 'mirakl_plugin_settings[mirakl_shop_id]' ),
        esc_attr( $options['mirakl_shop_id'] )
    );
}
function mirakl_render_date_field(){
    $options = get_option( 'mirakl_plugin_settings' );
    printf(
        '<input type="text" name="%s" value="%s" disabled/>',
        esc_attr( 'mirakl_plugin_settings[last_date]' ),
        esc_attr( get_option('mirakl_last_updated') )
    );
}

function mirakl_update_data(){
    synchonize_mirakl_orders();
}
function mirakl_clean_date(){
    update_option( 'mirakl_last_updated', '');
}
function mirakl_admin(){

    require_once HANFGEFLUE_PLUGIN_DIR . '/templates/plugin_page.php';



}
