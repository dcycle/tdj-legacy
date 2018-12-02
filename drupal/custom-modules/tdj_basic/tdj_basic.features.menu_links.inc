<?php

/**
 * Implementation of hook_menu_default_menu_links().
 */
function tdj_basic_menu_default_menu_links() {
  $menu_links = array();

  // Exported menu link: primary-links:node/add/tdj-projet
  $menu_links['primary-links:node/add/tdj-projet'] = array(
    'menu_name' => 'primary-links',
    'link_path' => 'node/add/tdj-projet',
    'router_path' => 'node/add/tdj-projet',
    'link_title' => 'Créer un projet',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'alter' => TRUE,
    ),
    'module' => 'menu',
    'hidden' => '0',
    'external' => '0',
    'has_children' => '0',
    'expanded' => '0',
    'weight' => '-44',
  );
  // Exported menu link: primary-links:projets
  $menu_links['primary-links:projets'] = array(
    'menu_name' => 'primary-links',
    'link_path' => 'projets',
    'router_path' => 'projets',
    'link_title' => 'Projets',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'alter' => TRUE,
    ),
    'module' => 'menu',
    'hidden' => '0',
    'external' => '0',
    'has_children' => '0',
    'expanded' => '0',
    'weight' => '0',
  );
  // Translatables
  // Included for use with string extractors like potx.
  t('Créer un projet');
  t('Projets');


  return $menu_links;
}
