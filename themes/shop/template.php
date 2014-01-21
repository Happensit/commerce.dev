<?php
/**
 * Created by PhpStorm.
 * User: Happensit (http://happensit.com)
 * Date: 21.01.14
 * Time: 12:53
 */

/**
 * Implements html_head_alter().
 */
function shop_html_head_alter(&$head_elements) {
  foreach (preg_grep('/^drupal_add_html_head_link:shortlink:/', array_keys($head_elements)) as $key) {
    unset($head_elements[$key]);
  }
  if(drupal_is_front_page()){
    foreach (preg_grep('/^drupal_add_html_head_link:canonical:/', array_keys($head_elements)) as $key) {
      unset($head_elements[$key]);
    }
  }
}

function shop_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];
  if (!empty($breadcrumb)) {
    $breadcrumb[0] = l('<img src="'.base_path() . drupal_get_path('theme', 'shop').'/img/home.gif" alt="Главная страница">'
      ,NULL,array('html'=>true));
    return '<div class="breadcrumb">'. implode('&nbsp;&nbsp;-&nbsp;&nbsp;', $breadcrumb) .'</div>';
  }
}

/**
 * блок с корзиной полный
 */
function shop_commerce_cart_block($variables = array()) {
  global $user;
  $suff = "ов";
  // First check to enure there are products in the shopping cart.
  if ($order = commerce_cart_order_load($user->uid)) {
        $wrapper = entity_metadata_wrapper('commerce_order', $order);
        $line_items = $wrapper->commerce_line_items;
        $total = commerce_line_items_total($line_items);
        $quantity = commerce_line_items_quantity($line_items, commerce_product_line_item_types());
        if($quantity == 1) $suff = null;
        if($quantity > 1 && $quantity < 5) $suff = "а";
        $cart_link = t("<span style = 'color: #0275c6;'>{$quantity}</span> товар".$suff);
        return "{$cart_link} на сумму <span style = 'color: #0275c6;'>".commerce_currency_format($total['amount'], $total['currency_code'])."</span>";
  }
}