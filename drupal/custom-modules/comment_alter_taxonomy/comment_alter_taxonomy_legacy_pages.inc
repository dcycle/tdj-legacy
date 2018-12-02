<?php
// $Id: comment_alter_taxonomy_legacy_pages.inc,v 1.1 2009/03/24 00:39:32 dww Exp $

/**
 * @file
 * Code used to redirect from the legacy project issue listings for issue tags.
 */

function comment_alter_taxonomy_legacy_project_issue_redirect($term_id, $project = NULL) {

  // First, find the appropriate taxonomy term given the argument.
  $issue_vocabularies = taxonomy_get_vocabularies('project_issue');
  if (is_numeric($term_id)) {
    $terms[] = taxonomy_get_term($term_id);
  }
  else {
    $terms = taxonomy_get_term_by_name($term_id);
  }
  if (empty($terms)) {
    return drupal_not_found();
  }
  $is_issue_term = FALSE;
  foreach ($terms as $term) {
    foreach ($issue_vocabularies as $vid => $vocabulary) {
      if ($term->vid == $vid) {
        $is_issue_term = TRUE;
        break;
      }
    }
  }    
  if (!$is_issue_term) {
    return drupal_not_found();
  }
  $filter_id = project_issue_views_filter_identifier($vocabulary->name);
  $query = array($filter_id => $term->name);

  // If the project argument was set, validate that, too.
  if (!empty($project)) {
    if (is_numeric($project)) {
      $project_uri = project_get_uri_from_nid($project);
    }
    else {
      $project_nid = project_get_nid_from_uri($project);
      if (!empty($project_nid)) {
        $project_uri = $project;
      }
    }
    if (empty($project_uri)) {
      return drupal_not_found();
    }
  }

  // Finally, create the appropriate advanced search URL and redirect
  $url = 'project/issues/search';
  if (!empty($project_uri)) {
    $url .= '/' . $project_uri;
  }

  drupal_goto($url, $query);
}

