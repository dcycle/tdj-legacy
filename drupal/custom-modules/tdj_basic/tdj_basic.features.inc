<?php

/**
 * Implementation of hook_ctools_plugin_api().
 */
function tdj_basic_ctools_plugin_api() {
  list($module, $api) = func_get_args();
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => 1);
  }
}

/**
 * Implementation of hook_imagecache_default_presets().
 */
function tdj_basic_imagecache_default_presets() {
  $items = array(
    'images_sur_nodes' => array(
      'presetname' => 'images_sur_nodes',
      'actions' => array(
        '0' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale',
          'data' => array(
            'width' => '',
            'height' => '160',
            'upscale' => 0,
          ),
        ),
      ),
    ),
    'large_image_for_lightbox' => array(
      'presetname' => 'large_image_for_lightbox',
      'actions' => array(
        '0' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale',
          'data' => array(
            'width' => '800',
            'height' => '',
            'upscale' => 0,
          ),
        ),
      ),
    ),
    'my_thumb_logo_large' => array(
      'presetname' => 'my_thumb_logo_large',
      'actions' => array(
        '0' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale',
          'data' => array(
            'width' => '',
            'height' => '150',
            'upscale' => 1,
          ),
        ),
      ),
    ),
    'my_thumb_logo_small' => array(
      'presetname' => 'my_thumb_logo_small',
      'actions' => array(
        '0' => array(
          'weight' => '-10',
          'module' => 'imagecache',
          'action' => 'imagecache_scale',
          'data' => array(
            'width' => '',
            'height' => '75',
            'upscale' => 1,
          ),
        ),
        '1' => array(
          'weight' => '-9',
          'module' => 'imagecache',
          'action' => 'imagecache_scale_and_crop',
          'data' => array(
            'width' => '75',
            'height' => '75',
          ),
        ),
      ),
    ),
    'tdj_full_lightbox_target' => array(
      'presetname' => 'tdj_full_lightbox_target',
      'actions' => array(
        '0' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale',
          'data' => array(
            'width' => '1000',
            'height' => '',
            'upscale' => 1,
          ),
        ),
      ),
    ),
    'tdj_image_ressource' => array(
      'presetname' => 'tdj_image_ressource',
      'actions' => array(
        '0' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale',
          'data' => array(
            'width' => '300',
            'height' => '',
            'upscale' => 1,
          ),
        ),
        '1' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale_and_crop',
          'data' => array(
            'width' => '100',
            'height' => '75',
          ),
        ),
      ),
    ),
    'tdj_portrait' => array(
      'presetname' => 'tdj_portrait',
      'actions' => array(
        '0' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale',
          'data' => array(
            'width' => '',
            'height' => '300',
            'upscale' => 1,
          ),
        ),
        '1' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale_and_crop',
          'data' => array(
            'width' => '138',
            'height' => '150',
          ),
        ),
      ),
    ),
    'tdj_thumb_forummembers' => array(
      'presetname' => 'tdj_thumb_forummembers',
      'actions' => array(
        '0' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale',
          'data' => array(
            'width' => '200',
            'height' => '',
            'upscale' => 1,
          ),
        ),
        '1' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale_and_crop',
          'data' => array(
            'width' => '40',
            'height' => '40',
          ),
        ),
      ),
    ),
    'tdj_thumb_small_sidemenu' => array(
      'presetname' => 'tdj_thumb_small_sidemenu',
      'actions' => array(
        '0' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale',
          'data' => array(
            'width' => '',
            'height' => '21',
            'upscale' => 1,
          ),
        ),
      ),
    ),
  );
  return $items;
}

/**
 * Implementation of hook_node_info().
 */
function tdj_basic_node_info() {
  $items = array(
    'procesverbal' => array(
      'name' => t('Procès verbal'),
      'module' => 'features',
      'description' => t('<p>Les proc&egrave;s verbaux des conseils d\'administration seront plac&eacute;s ici.</p>'),
      'has_title' => '1',
      'title_label' => t('Titre'),
      'has_body' => '1',
      'body_label' => t('Corps'),
      'min_word_count' => '0',
      'help' => '',
    ),
    'tdj_projet' => array(
      'name' => t('Projet'),
      'module' => 'features',
      'description' => t('Un projet'),
      'has_title' => '1',
      'title_label' => t('Titre'),
      'has_body' => '1',
      'body_label' => t('Corps'),
      'min_word_count' => '0',
      'help' => '',
    ),
  );
  return $items;
}

/**
 * Implementation of hook_views_api().
 */
function tdj_basic_views_api() {
  return array(
    'api' => '2',
  );
}
