<?php
// $Id: common.inc,v 1.1.2.13 2009/10/24 19:19:55 joachim Exp $

/**
 * @file common.inc
 *   Stuff needed both by module and drush command.
 *
 */

/**
 * Safe version of drush_print that can be called without worrying about 
 * where we are.
 */
function _module_builder_drush_print($message, $indent, $type = 'message') {
  if (MODULE_BUILDER_ENV == 'drush') {
    // Running in a Drush CLI.  
    drush_print($message, $indent);    
  }
  else {
    // Running in Drupal UI. 
    // @TODO?
  }
}

/**
 * Get a path to a resource that is safe to use either on Drupal or Drush.
 *
 * @param $subpath
 *    The subpath inside the module_builder folder. Eg, 'templates'.
 */
function module_builder_get_path($subpath) {
  $mb_path = drupal_get_path('module', 'module_builder');
  if (!$mb_path) {
    $mb_path = dirname(__FILE__) . '/..';
  }
  
  $path = $mb_path . '/' . $subpath;
  
  //print "path: >>$path<<";
  
  return $path;
}

/**
 * Include a version-specific file whether we're on drush or drupal.
 *
 * Files are included from the 'includes' folder inside module_builder.
 *
 * On Drush, this is a wrapper for drush_include().
 * On Drupal, this just goes straight for the current version.
 */
function module_builder_include($name, $extension = 'inc') {
  $path = module_builder_get_path('includes');
  if (MODULE_BUILDER_ENV == 'drush') {
    // In Drush.
    // the NULL means drush_include will try to find the version.
    drush_include($path, $name, NULL, $extension);
  }
  else {
    // In Drupal GUI.
    // Try the versioned file first.
    $file = sprintf("%s/%s_%s.%s", $path, $name, _module_builder_drupal_major_version(), $extension);
    //dsm($file);
    if (file_exists($file)) {
      include_once($file);
      return;
    }
    // Fall back to the regular file.
    $file = sprintf("%s/%s.%s", $path, $name, $extension);
    include_once($file); 
  }
}

/**
 * Returns the Drupal major version number (5, 6, 7 ...)
 *
 * Helper function for module_builder_include. Cribbed and hacked from drush.
 */
function _module_builder_drupal_major_version() {
  list($major_version) = explode('.', VERSION);
  return $major_version;
}


/**
 * Create a directory to store hook files if it does not exist.
 *
 * This logic blatantly ripped off from image.module -- thanks James! :)
 // somewhat obsolete.
 */
function _module_builder_check_settings($directory = NULL) {
  if ($directory) {
    // on drush
    if (!is_dir($directory)) {
      mkdir($directory); 
    }
  }
  else {
    // on module
    // sanity check. need to verify /files exists before we do anything. see http://drupal.org/node/367138
    $files = file_create_path();
    file_check_directory($files, FILE_CREATE_DIRECTORY);
    // check hooks directory exists or create it
    $hooks_path = file_create_path(variable_get('module_builder_hooks_directory', 'hooks'));
    file_check_directory($hooks_path, FILE_CREATE_DIRECTORY, 'module_builder_hooks_directory');
  }
}

/**
 * Check hook data is available in storage file.
 *
 * This allows us to check things are okay at an early stage.
 *
 * @param $directory
 *  (optional) The directory to look in for processed data.
 * @return
 *  TRUE is file exists, FALSE if not.
 */
function _module_builder_check_hook_data($directory = NULL) {
  if (!isset($directory)) {
    //$directory = file_create_path(variable_get('module_builder_hooks_directory', 'hooks'));
    $directory = _module_builder_get_hooks_directory();
  }
  
  return file_exists("$directory/hooks_processed.php");
}

/**
 * Update hook files and process them to our data file.
 *
 * This is the master function to call from either UI, drush or drupal.
 */
function module_builder_update_data() {
  // Update the hook documentation.
  module_builder_include('update');
  $hook_files = module_builder_update_documentation();
  
  // Process the hook files.
  module_builder_include('process');
  module_builder_process_hook_data($hook_files);
  
  // Set the last updated variable
  // TODO: skip this if no bootstrap.
  variable_set('module_builder_last_update', format_date(time()));
    
  return TRUE; // FTW!  
}

/**
 * Get a directory to save or read hook data files.
 *
 * This is either the variable from Drupal, or the --data option.
 * Use of the --data option allows a central store of hook data that needs only
 * be downloaded once for all Drupal sites. 
 * Subdirectories are made for each version.
 *
 * This needs to be safe to use at any bootstrap level.
 * 
 * @return
 *   A directory path either relative to Drupal root or absolute.
 */
function _module_builder_get_hooks_directory() {
  if (MODULE_BUILDER_ENV == 'drupal') {
    // Running in a Drupal UI.  
    $directory = variable_get('module_builder_hooks_directory', 'hooks');
    //return $directory;      
  }
  else {
    // TODO: TIDY UP!
    
    // Running under Drush.
    // The order is:
    // - command --data option
    // - local Drupal variable
    // - default Drupal location in files/hooks
    if (drush_get_option('data')) {
      $directory = drush_get_option('data');
      $common = TRUE;
    }
    if (!$directory) {
      if (function_exists('variable_get')) {
        // We're in a loaded Drupal, but MB might not be installed here.
        $directory = variable_get('module_builder_hooks_directory', 'hooks');
        // No variable: but could still be running MB, so we want files/hooks
        if (!$directory) {
          if (module_exists('module_builder')) {
            $directory = 'hooks';
          }
        }
      }
    }
  }
  if (!$directory) {
     // error! 
  }
  
  // Check and create the directory if necessary.
  if (strpos($directory, '/') === 0) {
    // absolute path
    //print 'starts with /'; 
  }
  else {
    // Relative, and so assumed to be in Drupal's files folder.
    // sanity check. need to verify /files exists before we do anything. see http://drupal.org/node/367138
    $files = file_create_path();
    file_check_directory($files, FILE_CREATE_DIRECTORY);    
    $directory = file_create_path($directory);    
  }
  
  // Note that recursive stuff won't work here!
  // You really should make sure the dir exists yourself.
  file_check_directory($directory, FILE_CREATE_DIRECTORY);
  
  // If the directory is common, append the version number subdir.
  if ($common) {
    $directory = $directory . '/' . _module_builder_drupal_major_version();    
    file_check_directory($directory, FILE_CREATE_DIRECTORY);
  }
    
  return $directory;
}

