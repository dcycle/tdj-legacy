<?php

/**
 * Implementation of hook_user_default_permissions().
 */
function tdj_basic_user_default_permissions() {
  $permissions = array();

  // Exported permission: create tdj_projet content
  $permissions['create tdj_projet content'] = array(
    'name' => 'create tdj_projet content',
    'roles' => array(
      '0' => 'administrateur',
      '1' => 'membre TDJ',
    ),
  );

  // Exported permission: delete any tdj_projet content
  $permissions['delete any tdj_projet content'] = array(
    'name' => 'delete any tdj_projet content',
    'roles' => array(
      '0' => 'administrateur',
    ),
  );

  // Exported permission: delete own tdj_projet content
  $permissions['delete own tdj_projet content'] = array(
    'name' => 'delete own tdj_projet content',
    'roles' => array(
      '0' => 'administrateur',
      '1' => 'membre TDJ',
    ),
  );

  // Exported permission: edit any tdj_projet content
  $permissions['edit any tdj_projet content'] = array(
    'name' => 'edit any tdj_projet content',
    'roles' => array(),
  );

  // Exported permission: edit own tdj_projet content
  $permissions['edit own tdj_projet content'] = array(
    'name' => 'edit own tdj_projet content',
    'roles' => array(),
  );

  return $permissions;
}
