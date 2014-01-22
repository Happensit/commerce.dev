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


function shop_form_views_form_commerce_cart_form_default_alter(&$form, &$form_state) {
  $form['output']['#markup'] = "<div class='basket1 cart'>".$form['output']['#markup'].'</div>';

  if (is_array($form['edit_delete'])){
    foreach($form['edit_delete'] as $k=> $item) {
      if($item['#type'] == 'submit') {
        $form['edit_delete'][$k] ['#value'] = ' ';
      }
    }

  $form['actions']['submit']['#value'] = 'Пересчитать';

  $form['actions']['checkout']['#value'] = 'Оформить';

  $form['output']['#markup'] .= <<<HTML
  <script>
 (function ($) {
    $(document).ready(function() {
        $("input#edit-submit").replaceWith("<button type='submit' class='button_flex bf2' onclick='window.history.back();return false;'><span><span>Вернуться к покупкам</span></span></button> <button type='submit' name='op' value='Пересчитать' class='button_flex bf2 refresh'><span><span>Пересчитать</span></span></button> ");
        $("input#edit-checkout").replaceWith("<button type='submit' name='op' class='button_flex checkout' value='Оформить'><span><span>Оформить заказ</span></span></button>");
    });
  }(jQuery));
  </script>
HTML;

    //echo ('<p style="color:red; font-weight: bold">По правилам нашего магазина минимальная сумма заказа составляет 3000 рублей.</p>');
  }
}

/**
 * implement hook_preprocess_page().
 */
function shop_preprocess_page(&$variables) {
  if ($variables['is_front']) {
    // Подружаем стили и js для слайдера
    drupal_add_js(drupal_get_path('theme', 'shop') . '/js/jquery.jcarousel.min.js', array('type' => 'file', 'preprocess' => TRUE, 'scope' => 'footer'));
    drupal_add_css(drupal_get_path('theme', 'shop') . '/style/bunners.css', array('group' => CSS_DEFAULT, 'preprocess' => TRUE));
    drupal_add_js(drupal_get_path('theme', 'shop') . '/js/front_bunners.js', array('type' => 'file', 'preprocess' => TRUE, 'scope' => 'footer'));
  }
}


/**
 * @param $variables
 * @return string
 */
function shop_pager($variables) {
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];

  global $pager_page_array, $pager_total, $pager_total_items;
  $pager_middle = ceil($quantity / 2);
  $pager_current = $pager_page_array[$element];
  $pager_first = ($pager_current - $pager_middle)+1;
  $pager_last = ($pager_current + $quantity - $pager_middle);
  // max is the maximum page number
  $pager_max = $pager_total[$element]-1;
  // End of marker calculations.
  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }


  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.

  if($pager_total > 25){
    $tags[4] = $pager_max;
    $tags[0] = 1;
  }

  $li_first = theme('pager_first', array('text' => (isset($tags[0]) ? $tags[0] : t('« first')), 'element' => $element, 'parameters' => $parameters));
  $li_previous = theme('pager_previous', array('text' => (isset($tags[1]) ? $tags[1] : t('‹ previous')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : t('next ›')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : t('last »')), 'element' => $element, 'parameters' => $parameters));

  $count_element = 'Товаров: '. $pager_total_items[0];


  if ($pager_total[$element] > 1) {
    $items[] = array(
      'class'=> array('count_element'),
      'data' => $count_element,
    );

    // if($pager_current == '0') {
    // 		 $items[] = array(
    //       'class' => array('pager-current'),
    //       'data' => 1,
    //     );

    // 		 $i = 2;
    // 	}


    if ($li_first && $pager_total[$element] > 8 && $pager_current - 2 > 1 ) {
      $items[] = array(
        'class' => array('pager-first'),
        'data' => $li_first,
      );
    }
    if ($li_previous) {
      $items[] = array(
        'class' => array('pager-previous'),
        'data' => $li_previous,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1 && $pager_current - 3 > 1) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '…',
          //'data' => '»',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => array('pager-current'),
            'data' => $i,
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '…',
        );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => array('pager-next'),
        'data' => $li_next,
      );
    }
    if ($li_last && $pager_total[$element] > 6 && $pager_current + 3 < $pager_total[$element]) {
      $items[] = array(
        'class' => array('pager-last'),
        'data' => $li_last,
      );
    }

    return theme('item_list', array(
      'items' => $items,
      'attributes' => array('class' => array('pager')),
    ));
  }
}