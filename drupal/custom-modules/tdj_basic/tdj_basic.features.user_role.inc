<?php

/**
 * Implementation of hook_user_default_roles().
 */
function tdj_basic_user_default_roles() {
  $roles = array();

  // Exported role: membre TDJ
  $roles['membre TDJ'] = array(
    'name' => 'membre TDJ',
  );

  return $roles;
}
