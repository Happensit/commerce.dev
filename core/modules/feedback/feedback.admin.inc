<?php

function feedback_settings($form, &$form_state){
	$data = variable_get('feedback_addr');
	$form['text'] = array(
		'#type' => 'text_format',
		'#title' => 'Текст',
		'#rows' => 20,
		'#format' => 'full_html',
		'#default_value' => isset($data) ? $data : '',
	);
	$form['submit'] = array(
		'#type' => 'submit',
		'#value' => t('Send'),
	);
	return $form;
}

function feedback_settings_submit($form, &$form_state){
	variable_set('feedback_addr', $form_state['values']['text']['value']);
	drupal_set_message('Настройки сохранены');
}
function feedback_settings_mail($form, &$form_state) {
	$path = drupal_get_path('module', 'feedback');
	/*  drupal_add_js($path . '/scripts/j.js', 'file');*/
  drupal_add_css($path . '/css/feedback.css', 'file'); 
	$form['mail'] = array(
		'#type' => 'textfield',
		'#title' => t('E-mail for Notification'),
		'#size' => 40,
		'#maxlength' => 45,
		'#default_value' => (variable_get('feedback_mail_notification')) ? variable_get('feedback_mail_notification') : '',
		'#required' => TRUE,
	);
	$form['fields'] = array(
		'#type' => 'fieldset',
		'#title' => 'Поля',
		'#collapsible' => TRUE,
		'#collapsed' => TRUE,
	);
	$form['fields']['page_title'] = array(
		'#type' => 'textfield',
		'#title' => 'Заголовок формы',
		'#default_value' => variable_get('feedback_title', 'Задать вопрос'),
	);
	$form['fields']['page_text'] = array(
		'#type' => 'checkbox',
		'#title' => 'Выводить текст перед формой',
		'#default_value' => variable_get('feedback_page_text', 1),
	);
	$form['fields']['field_name'] = array(
		'#type' => 'checkbox',
		'#title' => variable_get('feedback_field_1_name', 'Имя'),
		'#default_value' => variable_get('feedback_1_enabled', 1),
		'#description' => 'Включить поле в форму',
		'#prefix' => '<div class="fields_box">',
	);
	$form['fields']['field_name_req'] = array(
		'#type' => 'checkbox',
		'#title' => 'Обязательное',
		'#default_value' => variable_get('feedback_1_required', 1),
	);
	$form['fields']['field_name_text'] = array(
		'#type' => 'textfield',
		'#title' => 'Название поля',
		'#default_value' => variable_get('feedback_1_name', 'Имя'),
		'#suffix' => '</div>',
	);
	$form['fields']['field_mail'] = array(
		'#type' => 'checkbox',
		'#title' => variable_get('feedback_field_2_name', 'E-mail'),
		'#description' => 'Включить поле в форму',
		'#default_value' => variable_get('feedback_2_enabled', 1),
		'#prefix' => '<div class="fields_box">',
	);
	$form['fields']['field_mail_req'] = array(
		'#type' => 'checkbox',
		'#title' => 'Обязательное',
		'#default_value' => variable_get('feedback_2_required', 1),
	);
	$form['fields']['field_mail_valid'] = array(
		'#type' => 'checkbox',
		'#title' => 'Валидация E-mail',
		'#default_value' => variable_get('feedback_2_valid', 1),
	);
	$form['fields']['field_mail_text'] = array(
		'#type' => 'textfield',
		'#title' => 'Название поля',
		'#default_value' => variable_get('feedback_2_name', 'E-mail'),
		'#suffix' => '</div>',
	);

	$form['fields']['field_mess_text'] = array(
		'#type' => 'textfield',
		'#title' => 'Название поля ' . variable_get('feedback_4_name', 'Сообщение'),
		'#default_value' => variable_get('feedback_4_name', 'Сообщение'),
		'#prefix' => '<div class="fields_box">',
		'#suffix' => '</div>',
	);
	$form['block'] = array(
		'#type' => 'fieldset',
		'#title' => 'Блок',
		'#collapsible' => TRUE,
		'#collapsed' => TRUE,
	);
	$form['block']['block_title'] = array(
		'#type' => 'textfield',
		'#title' => 'Заголовок блока',
		'#default_value' => variable_get('feedback_block_title', 'Обратная связь'),
	);
	$form['block']['block_text'] = array(
		'#type' => 'checkbox',
		'#title' => 'Выводить текст перед формой',
		'#default_value' => variable_get('feedback_block_text', 1),
	);
	$form['submit'] = array(
		'#type' => 'submit',
		'#value' => t('Save'),
	);
	return $form;
}
function feedback_settings_mail_validate($form, &$form_state) {
	if(!valid_email_address($form_state['values']['mail'])){
		form_set_error('mail', 'Mail is invalid.');
	}
}
function feedback_settings_mail_submit($form, &$form_state) {
	variable_set('feedback_mail_notification', $form_state['values']['mail']);
	variable_set('feedback_1_enabled', $form_state['values']['field_name']);
	variable_set('feedback_2_enabled', $form_state['values']['field_mail']);
	variable_set('feedback_1_name', $form_state['values']['field_name_text']);
	variable_set('feedback_2_name', $form_state['values']['field_mail_text']);
	variable_set('feedback_1_required', $form_state['values']['field_name_req']);
	variable_set('feedback_2_required', $form_state['values']['field_mail_req']);
	variable_set('feedback_2_valid', $form_state['values']['field_mail_valid']);
	variable_set('feedback_block_title', $form_state['values']['block_title']);
	variable_set('feedback_title', $form_state['values']['page_title']);
	variable_set('feedback_4_name', $form_state['values']['field_mess_text']);
	variable_set('feedback_page_text', $form_state['values']['page_text']);
	variable_set('feedback_block_text', $form_state['values']['block_text']);

	drupal_set_message(t('Settings have saved'));
}
