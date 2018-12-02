<?php
// $Id: petition-signatures-admin.inc,v 1.1.2.6 2009/06/11 18:01:34 gauvain Exp $

/**
 * Menu callback: petition_signatures_admin_page.
 */
function petition_signatures_admin_page($node) {
  
  $output = drupal_get_form('administer_signatures', $node);
  
  return $output;
}

function administer_signatures($form_state, $node) {
  if (isset($form_state['values']['operation']) && $form_state['values']['operation'] ==  'delete') {
    return signatures_multiple_delete_confirm($form_state, array_filter($form_state['values']['signatures']));
  }
  $form['admin'] = petition_admin_signatures($node);

  return $form;
}


/**
 * Validate petition_admin_signatures form submissions.
 * 
 * Check if any nodes have been selected to perform the chosen
 * 'Update option' on.
 */
function administer_signatures_validate($form, &$form_state) {
  $signatures = array_filter($form_state['values']['signatures']);
  if (count($signatures) ==  0) {
    form_set_error('', t('No items selected.'));
  }
}

/**
 * Process petition_admin_signatures form submissions.
 * 
 * Rebuild the form for the confirmation step
 */
function petition_admin_signatures_submit($form, &$form_state) {
  
  $signatures = array_filter($form_state['values']['signatures']);
  $form_state['rebuild'] = TRUE;
}

function signatures_multiple_delete_confirm(&$form_state, $signatures) {

  $form['signatures'] = array('#prefix' => '<ul>', '#suffix' => '</ul>', '#tree' => TRUE);
  // array_filter returns only elements with TRUE values
  foreach ($signatures as $cid => $value) {
    $result = db_query('SELECT * FROM {petition_signatures} WHERE cid = %d', $cid);
    $signature = db_fetch_object($result);
      $form['signatures'][$cid] = array(
        '#type' => 'hidden', 
        '#value' => $cid, 
        '#prefix' => '<li>', 
        '#suffix' => check_plain($signature->name . ', ' .$signature->firstname . " from ". $signature->zip . "  " . $signature->town) ."</li>\n", 
      );
  }
  $form['operation'] = array('#type' => 'hidden', '#value' => 'delete');
  $form['#submit'][] = 'signatures_multiple_delete_confirm_submit';
  return confirm_form($form, 
                      t('Are you sure you want to delete these signatures?'), 
                      check_plain($_GET['q']), t('This action cannot be undone.'), 
                      t('Delete all'), t('Cancel'));
}

function signatures_multiple_delete_confirm_submit($form, &$form_state) {
  if ($form_state['values']['confirm']) {
    foreach ($form_state['values']['signatures'] as $cid => $value) {
      $nid = db_result(db_query("SELECT nid FROM {petition_signatures} WHERE cid = %d", $cid));
      db_query("DELETE FROM {petition_signatures} WHERE cid = %d", $cid);
    }
    _update_sigature_position($nid);
    drupal_set_message(t('The items have been deleted.'));
  }
  $form_state['redirect'] = check_plain($_GET['q']);
  return;
}

/*
 *petition_admin_signatures form
 */

function petition_admin_signatures($node) {
  
  // submit button with 'delete' value
   $form['options'] = array(
    '#type' => 'fieldset', 
    '#title' => t('Delete Signatures'), 
    '#prefix' => '<div class = "container-inline">', 
    '#suffix' => '</div>', 
  );
  
  $form['options']['operation'] = array(
    '#type' => 'value',  
    '#default_value' => 'delete', 
  );
  $form['options']['submit'] = array(
    '#type' => 'submit', 
    '#value' => t('Delete Selected Signatures'), 
    '#submit' => array('petition_admin_signatures_submit'), 
  );
 
  $destination = drupal_get_destination();
  
  //pager information
  $max=variable_get('petition_signature_number', 50);
  $result = db_query("SELECT * FROM {node} WHERE status = 0 AND (tnid = nid OR tnid = 0) ORDER BY created ASC");
  
  $fields = variable_get('petition_report_fields_fields', array('position', 'name', 'firstname', 'title', 'zip', 'town', 'country'));
  $field_array = array('position' => t('Signature position'), 'name' => t('Surname'), 'firstname' => t('First name'), 'title' => t('Self-description'), 'address' => t('Address'), 'zip' => t('ZIP code'), 'town' => t('Town'), 'country' => t('Country'), 'tel' => t('Phone number'), 'email' => t('Email'));
 
 $header = array();
 $i=0;
 foreach ($fields as $field) {
  if(!empty($field)){
   $header[$field] = $i <1 ? array('data' =>  $field_array[$field], 'field' => $field, 'sort' => 'asc') : array('data' =>  $field_array[$field], 'field' => $field);
   $i++;
   if ($field == 'country') {
    $header['flag'] = '';
    $i++;
   }
  }
 }
 
  $sql = "SELECT * FROM {petition_signatures} WHERE nid = %d AND confirmed = 1";
 $tablesort = tablesort_sql($header);
 
 $result = pager_query($sql . tablesort_sql($header), $max, 0, NULL, $node->nid);
 $signatures = array();
 
  
  while ($signature = db_fetch_object($result)) {
   $signatures[$signature->cid] = '';
   foreach($fields as $field) {
    if (!empty($field)) {
      if ($field == 'country') {
        if (module_exists("ip2cc") AND module_exists("countries_api")) {
          $src = url(drupal_get_path('module', 'ip2cc') ."/flags/". strtolower($signature->$field) .".png");
          $country = db_result(db_query("SELECT name from {countries_api_countries} where iso2 = '%s'", $signature->$field));
          $attribs = array('width' => 14, 'height' => 14, 'alt' => "$cc", 'title' => "$country");
          $flag = "<img src=\"$src\" ". drupal_attributes($attribs) ." />";
        }
        else {
         $country = $signature->country; $flag='';
        }
        $form['country'] [$signature->cid] = array('#value' => $country);
        $form['flag'] [$signature->cid] = array('#value' => $flag);
       }
     else {
      $form[$field] [$signature->cid] = array('#value' => check_plain($signature->$field));
     }
    }
   }
    
  }  
  $form['signatures'] = array('#type' => 'checkboxes', '#options' => $signatures);
  $form['pager'] = array('#value' => theme('pager', NULL, $max, 0));
  $form['#theme'] = 'petition_admin_signatures';
  return $form;
    
}

/**
 * Theme signature administration overview.
 * @ingroup themeable
 */
function theme_petition_admin_signatures($form) {
  
   $fields = variable_get('petition_report_fields_fields', array('position', 'name', 'firstname', 'title', 'zip', 'town', 'country'));
  $field_array = array('position' => t(''), 'name' => t('Surname'), 'firstname' => t('First name'), 'title' => t('Self-description'), 'address' => t('Address'), 'zip' => t('ZIP code'), 'town' => t('Town'), 'country' => t('Country'), 'tel' => t('Phone number'), 'email' => t('Email'), 'canbe_informed' => t('Accept to be informed of the campaign'), 'language' => t('language'));
 
 $header = array('box' => '');
 $i=0;
 foreach ($fields as $field) {
  if(!empty($field)){
   $header[$field] = $i <1 ? array('data' =>  $field_array[$field], 'field' => $field, 'sort' => 'asc') : array('data' =>  $field_array[$field], 'field' => $field);
   if (isset($form[$field]) && is_array($form[$field])) {
    $has_posts = TRUE;
   }
   if (isset($form[$field]) && is_array($form[$field]) && $i <1) {
    $firstcol = $field;
   }
    $i++;
   if ($field == 'country') {
    $header['flag'] = '';
    $i++;
   }
  }
 }
  
  $output = '';

  $output .=  drupal_render($form['options']);
  $i = 0;
  if ($has_posts) {
    foreach (element_children($form[$firstcol]) as $key) {
      $row = array();
      foreach ($header as $col => $col_title) {
       $row[] = $col == 'box' ? drupal_render($form['signatures'][$key]) : drupal_render($form[$col][$key]);

    //   $row[] = drupal_render($form[$col][$key]);
      }
      $rows[$i]['data'] = $row;
      $i++;  
    }
  }
  else {
    $rows[] = array(array('data' => t('No signatures available.'), 'colspan' => '6'));
  }

  $output .=  theme('table', $header, $rows);

  $output .=  drupal_render($form);
  $output .= drupal_render($form['pager']);

  return $output;
}

/**
 * Helper function to update the signatures posiion after deletion of a signature.
 * @param
 * nid of the node
 */
function _update_sigature_position($nid) {
     $signatures = db_query("SELECT * FROM {petition_signatures} WHERE nid = %d AND confirmed = 1 ORDER BY cid ASC", $nid);
     $position = 0;
     while ($signature = db_fetch_object($signatures)) {
       $position += 1;
       db_query("UPDATE {petition_signatures} SET position = %d WHERE nid = %d AND cid = %d", $position, $signature->nid, $signature->cid);
     }
     
     $signature_count = db_result(db_query("SELECT COUNT(cid) FROM {petition_signatures} WHERE nid = %d AND confirmed = 1", $nid));
     $signature_count = is_numeric($signature_count) ? $signature_count : 0;
     db_query("UPDATE {petition} SET signatures = %d WHERE nid = %d", $signature_count, $nid);
}