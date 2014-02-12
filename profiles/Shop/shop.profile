<?php
/**
 * @file
 * Enables modules and site configuration for a minimal site installation.
 */

/**
 * Implements hook_form_FORM_ID_alter() for install_configure_form().
 *
 * Allows the profile to alter the site configuration form.
 */
function shop_form_install_configure_form_alter(&$form, $form_state) {
  // Pre-populate the site name with the server name.
  $form['site_information']['site_name']['#default_value'] = 'Svetexpo' ;//$_SERVER['SERVER_NAME'];
  $form['site_information']['site_name']['#title'] = 'Название сайта';
  $form['site_information']['site_mail']['#title'] = 'Основной E-mail адрес';
  // Set a default country so we can benefit from it on Address Fields.
  $form['server_settings']['site_default_country']['#default_value'] = 'RU';

  // Use "root" as the default username.
  $form['admin_account']['account']['name']['#default_value'] = 'root';
}
