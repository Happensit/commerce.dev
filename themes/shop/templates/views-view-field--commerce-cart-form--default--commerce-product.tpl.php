<?php
/**
 * Created by PhpStorm.
 * User: Happensit (http://happensit.com)
 * Date: 22.01.14
 * Time: 2:46
 */
module_load_include('inc', 'pathauto', 'pathauto');
$name = mb_strtolower($row->field_field_brand[0]['rendered']['#markup']);
$name = pathauto_cleanstring($name);
$sku = $row->field_commerce_product[0]['rendered']['#markup'];
$img = str_replace(array('/', '.', '(', ')', ' '), '_', mb_strtolower($sku));
print '<div style = "display: table-cell; text-align: center; width: 100px; vertical-align: middle;"><img src = "http://img.svetexpo.ru/goods/images/'.$name.'/'.$img.'.jpg" style ="height: 80px;" /></div>';
?>
<?php
// @todo Протестировать на сервере! Добавить в views поле brand

?>