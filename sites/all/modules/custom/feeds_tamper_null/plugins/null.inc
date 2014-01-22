<?php

/**
 * @file
 * Clear field if empty value import
 */

$plugin = array(
  'form'     => 'feeds_tamper_null_form',
  'callback' => 'feeds_tamper_null_callback',
  'name'     => 'NULL value field',
  'multi'    => 'direct',
  'category' => 'Other',
);

/**
 * Implements hook_form().
 */
function feeds_tamper_null_form($importer, $element_key, $settings) {
  $form = array();
  $form['help'] = array(
    '#markup' => t('Clear field value if empty import'),
  );
  return $form;
}

/**
 * feeds_tamper_null_callback().
 */
function feeds_tamper_null_callback($source, $item_key, $element_key, &$field, $settings) {
  //dpm($field);
  if (empty($field)) {
      $field = NULL;
  }
}