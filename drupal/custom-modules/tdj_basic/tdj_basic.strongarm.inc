<?php

/**
 * Implementation of hook_strongarm().
 */
function tdj_basic_strongarm() {
  $export = array();

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_anonymous_procesverbal';
  $strongarm->value = '0';
  $export['comment_anonymous_procesverbal'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_anonymous_tdj_projet';
  $strongarm->value = '0';
  $export['comment_anonymous_tdj_projet'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_controls_procesverbal';
  $strongarm->value = '3';
  $export['comment_controls_procesverbal'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_controls_tdj_projet';
  $strongarm->value = '3';
  $export['comment_controls_tdj_projet'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_default_mode_procesverbal';
  $strongarm->value = '4';
  $export['comment_default_mode_procesverbal'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_default_mode_tdj_projet';
  $strongarm->value = '4';
  $export['comment_default_mode_tdj_projet'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_default_order_procesverbal';
  $strongarm->value = '1';
  $export['comment_default_order_procesverbal'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_default_order_tdj_projet';
  $strongarm->value = '2';
  $export['comment_default_order_tdj_projet'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_default_per_page_procesverbal';
  $strongarm->value = '50';
  $export['comment_default_per_page_procesverbal'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_default_per_page_tdj_projet';
  $strongarm->value = '50';
  $export['comment_default_per_page_tdj_projet'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_form_location_procesverbal';
  $strongarm->value = '0';
  $export['comment_form_location_procesverbal'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_form_location_tdj_projet';
  $strongarm->value = '1';
  $export['comment_form_location_tdj_projet'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_preview_procesverbal';
  $strongarm->value = '1';
  $export['comment_preview_procesverbal'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_preview_tdj_projet';
  $strongarm->value = '0';
  $export['comment_preview_tdj_projet'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_procesverbal';
  $strongarm->value = '2';
  $export['comment_procesverbal'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_subject_field_procesverbal';
  $strongarm->value = '1';
  $export['comment_subject_field_procesverbal'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_subject_field_tdj_projet';
  $strongarm->value = '0';
  $export['comment_subject_field_tdj_projet'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_tdj_projet';
  $strongarm->value = '2';
  $export['comment_tdj_projet'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'content_extra_weights_procesverbal';
  $strongarm->value = array(
    'custom_breadcrumbs' => '7',
    'title' => '-5',
    'body_field' => '2',
    'revision_information' => '4',
    'author' => '5',
    'options' => '6',
    'comment_settings' => '9',
    'menu' => '-4',
    'path' => '8',
    'nodewords' => '3',
  );
  $export['content_extra_weights_procesverbal'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'content_extra_weights_tdj_projet';
  $strongarm->value = array(
    'custom_breadcrumbs' => '12',
    'title' => '-5',
    'body_field' => '0',
    'revision_information' => '8',
    'author' => '7',
    'options' => '9',
    'comment_settings' => '11',
    'language' => '1',
    'translation' => '13',
    'menu' => '-4',
    'path' => '10',
    'og_nodeapi' => '0',
    'nodewords' => '4',
  );
  $export['content_extra_weights_tdj_projet'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'language_content_type_procesverbal';
  $strongarm->value = '0';
  $export['language_content_type_procesverbal'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'language_content_type_tdj_projet';
  $strongarm->value = '2';
  $export['language_content_type_tdj_projet'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_options_procesverbal';
  $strongarm->value = array(
    0 => 'status',
    1 => 'revision',
  );
  $export['node_options_procesverbal'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_options_tdj_projet';
  $strongarm->value = array(
    0 => 'status',
    1 => 'promote',
    2 => 'revision',
  );
  $export['node_options_tdj_projet'] = $strongarm;

  return $export;
}
