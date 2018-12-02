<?php

/**
 * Implementation of hook_imagecache_default_presets().
 */
function tdj_frontpageanimation_imagecache_default_presets() {
  $items = array(
    'tdj_thumb_frontpageanimation' => array(
      'presetname' => 'tdj_thumb_frontpageanimation',
      'actions' => array(
        '0' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale_and_crop',
          'data' => array(
            'width' => '200',
            'height' => '180',
          ),
        ),
      ),
    ),
  );
  return $items;
}

/**
 * Implementation of hook_views_api().
 */
function tdj_frontpageanimation_views_api() {
  return array(
    'api' => '2',
  );
}
