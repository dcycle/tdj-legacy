<?php

/**
 * @file
 * All issues a user has created or commented on.
 */

$view = new view;
$view->name = 'project_issue_user_issues';
$view->description = 'Project issues for a specific user';
$view->tag = 'Project issue';
$view->view_php = '';
$view->base_table = 'node';
$view->is_cacheable = FALSE;
$view->api_version = 2;
$view->disabled = FALSE;
$handler = $view->new_display('default', 'Defaults', 'default');
$handler->override_option('relationships', array(
  'assigned' => array(
    'label' => 'Assigned user',
    'required' => 1,
    'id' => 'assigned',
    'table' => 'project_issues',
    'field' => 'assigned',
    'relationship' => 'none',
  ),
  'rid' => array(
    'label' => 'Version',
    'required' => 0,
    'id' => 'rid',
    'table' => 'project_issues',
    'field' => 'rid',
    'relationship' => 'none',
  ),
  'pid' => array(
    'label' => 'Project node',
    'required' => 1,
    'id' => 'pid',
    'table' => 'project_issues',
    'field' => 'pid',
  ),
));
$fields = array(
  'project_issue_queue' => array(
    'label' => 'Project',
    'link_type' => 'issues',
    'exclude' => 0,
    'id' => 'project_issue_queue',
    'table' => 'node',
    'field' => 'project_issue_queue',
    'relationship' => 'pid',
  ),
  'title' => array(
    'label' => 'Summary',
    'link_to_node' => 1,
    'exclude' => 0,
    'id' => 'title',
    'table' => 'node',
    'field' => 'title',
    'relationship' => 'none',
  ),
  'timestamp' => array(
    'label' => '',
    'alter' => array(),
    'link_to_node' => 0,
    'comments' => 0,
    'exclude' => 0,
    'id' => 'timestamp',
    'table' => 'history_user',
    'field' => 'timestamp',
    'relationship' => 'none',
  ),
  'sid' => array(
    'id' => 'sid',
    'table' => 'project_issues',
    'field' => 'sid',
  ),
  'priority' => array(
    'id' => 'priority',
    'table' => 'project_issues',
    'field' => 'priority',
  ),
  'category' => array(
    'label' => 'Category',
    'link_to_node' => 0,
    'exclude' => 0,
    'id' => 'category',
    'table' => 'project_issues',
    'field' => 'category',
    'relationship' => 'none',
  ),
  'version' => array(
    'label' => 'Version',
    'link_to_node' => 0,
    'exclude' => 0,
    'id' => 'version',
    'table' => 'project_release_nodes',
    'field' => 'version',
    'relationship' => 'rid',
  ),
  'comment_count' => array(
    'label' => 'Replies',
    'set_precision' => FALSE,
    'precision' => 0,
    'decimal' => '.',
    'separator' => ',',
    'prefix' => '',
    'suffix' => '',
    'exclude' => 0,
    'id' => 'comment_count',
    'table' => 'node_comment_statistics',
    'field' => 'comment_count',
    'relationship' => 'none',
  ),
  'new_comments' => array(
    'label' => '',
    'set_precision' => FALSE,
    'precision' => 0,
    'decimal' => '.',
    'separator' => ',',
    'prefix' => '',
    'suffix' => ' new',
    'link_to_comment' => 1,
    'no_empty' => 1,
    'exclude' => 0,
    'id' => 'new_comments',
    'table' => 'node',
    'field' => 'new_comments',
    'relationship' => 'none',
  ),
);
if (function_exists('tracker2_views_api')) {
  $last_update_field = 'changed';
  $fields['changed'] = array(
    'label' => 'Last updated',
    'date_format' => 'raw time ago',
    'custom_date_format' => '',
    'exclude' => 0,
    'id' => 'changed',
    'table' => 'tracker2_user',
    'field' => 'changed',
    'relationship' => 'none',
  );
}
else {
  $last_update_field = 'last_comment_timestamp';
  $fields['last_comment_timestamp'] = array(
    'label' => 'Last updated',
    'date_format' => 'raw time ago',
    'custom_date_format' => '',
    'exclude' => 0,
    'id' => 'last_comment_timestamp',
    'table' => 'node_comment_statistics',
    'field' => 'last_comment_timestamp',
    'relationship' => 'none',
  );
}
$fields['name'] = array(
  'label' => 'Assigned to',
  'link_to_user' => 1,
  'overwrite_anonymous' => 1,
  'anonymous_text' => '',
  'exclude' => 0,
  'id' => 'name',
  'table' => 'users',
  'field' => 'name',
  'relationship' => 'assigned',
);
$fields['created'] = array(
  'label' => 'Created',
  'alter' => array(
    'alter_text' => 0,
    'text' => '',
    'make_link' => 0,
    'path' => '',
    'link_class' => '',
    'alt' => '',
    'prefix' => '',
    'suffix' => '',
    'target' => '',
    'help' => '',
    'trim' => 0,
    'max_length' => '',
    'word_boundary' => 1,
    'ellipsis' => 1,
    'html' => 0,
    'strip_tags' => 0,
  ),
  'empty' => '',
  'hide_empty' => 0,
  'empty_zero' => 0,
  'date_format' => 'raw time ago',
  'custom_date_format' => '',
  'exclude' => 0,
  'id' => 'created',
  'table' => 'node',
  'field' => 'created',
  'relationship' => 'none',
);
if (module_exists('search')) {
  $fields['score'] = array(
    'label' => 'Score',
    'alter' => array(),
    'set_precision' => 1,
    'precision' => '3',
    'decimal' => '.',
    'separator' => ',',
    'prefix' => '',
    'suffix' => '',
    'alternate_sort' => $last_update_field,
    'alternate_order' => 'desc',
    'exclude' => 0,
    'id' => 'score',
    'table' => 'search_index',
    'field' => 'score',
    'relationship' => 'none',
  );
}
$handler->override_option('fields', $fields);
$handler->override_option('arguments', array(
  'uid_touch' => array(
    'default_action' => 'default',
    'style_plugin' => 'default_summary',
    'style_options' => array(),
    'wildcard' => 'all',
    'wildcard_substitution' => 'All',
    'title' => 'Issues for %1',
    'default_argument_type' => 'current_user',
    'default_argument' => '',
    'validate_type' => 'user',
    'validate_fail' => 'not found',
    'id' => 'uid_touch',
    'table' => 'node',
    'field' => 'uid_touch',
    'relationship' => 'none',
    'default_options_div_prefix' => '',
    'default_argument_user' => 0,
    'default_argument_fixed' => '',
    'default_argument_php' => '',
    'validate_user_argument_type' => 'either',
    'validate_user_restrict_roles' => 1,
    'validate_user_roles' => array('2' => 2),
  ),
));
$last_update_sort = array();
if (function_exists('tracker2_views_api')) {
  $last_update_sort['changed'] = array(
    'order' => 'DESC',
    'granularity' => 'second',
    'id' => 'changed',
    'table' => 'tracker2_user',
    'field' => 'changed',
    'relationship' => 'none',
  );
}
else {
  $last_update_sort['last_comment_timestamp'] = array(
    'order' => 'DESC',
    'granularity' => 'second',
    'id' => 'last_comment_timestamp',
    'table' => 'node_comment_statistics',
    'field' => 'last_comment_timestamp',
    'relationship' => 'none',
  );
}
$sorts = $last_update_sort;
if (module_exists('search')) {
  $sorts['score'] = array(
    'order' => 'DESC',
    'id' => 'score',
    'table' => 'search_index',
    'field' => 'score',
    'relationship' => 'none',
  );
}
$handler->override_option('sorts', $sorts);
$filters = array(
  'pid' => array(
    'operator' => 'in',
    'value' => '',
    'group' => '0',
    'exposed' => TRUE,
    'expose' => array(
      'operator' => 'pid_op',
      'label' => 'Project',
      'use_operator' => 0,
      'identifier' => 'projects',
      'optional' => 1,
      'single' => 1,
      'remember' => 0,
      'reduce' => 0,
    ),
    'widget' => 'textfield',
    'project_source' => 'participant',
    'project_uid_argument' => 'uid_touch',
    'id' => 'pid',
    'table' => 'project_issues',
    'field' => 'pid',
    'relationship' => 'none',
  ),
  'sid' => array(
    'operator' => 'in',
    'value' => array(
      'Open' => 'Open',
    ),
    'group' => '0',
    'exposed' => TRUE,
    'expose' => array(
      'use_operator' => 0,
      'operator' => 'sid_op',
      'identifier' => 'status',
      'label' => 'Status',
      'optional' => 1,
      'single' => 1,
      'remember' => 0,
      'reduce' => 0,
    ),
    'id' => 'sid',
    'table' => 'project_issues',
    'field' => 'sid',
    'relationship' => 'none',
  ),
  'priority' => array(
    'operator' => 'in',
    'value' => array(),
    'group' => '0',
    'exposed' => TRUE,
    'expose' => array(
      'use_operator' => 0,
      'operator' => 'priority_op',
      'identifier' => 'priorities',
      'label' => 'Priority',
      'optional' => 1,
      'single' => 1,
      'remember' => 0,
      'reduce' => 0,
    ),
    'id' => 'priority',
    'table' => 'project_issues',
    'field' => 'priority',
    'relationship' => 'none',
  ),
  'category' => array(
    'operator' => 'in',
    'value' => array(),
    'group' => '0',
    'exposed' => TRUE,
    'expose' => array(
      'use_operator' => 0,
      'operator' => 'category_op',
      'identifier' => 'categories',
      'label' => 'Category',
      'optional' => 1,
      'single' => 1,
      'remember' => 0,
      'reduce' => 0,
    ),
    'id' => 'category',
    'table' => 'project_issues',
    'field' => 'category',
    'relationship' => 'none',
  ),
);
$published_filter = array();
if (function_exists('tracker2_views_api')) {
  $published_filter['published'] = array(
    'operator' => '=',
    'value' => '1',
    'group' => '0',
    'exposed' => FALSE,
    'expose' => array(
      'operator' => FALSE,
      'label' => '',
    ),
    'id' => 'published',
    'table' => 'tracker2_user',
    'field' => 'published',
    'relationship' => 'none',
  );
}
else {
  $published_filter['status_extra'] = array(
    'operator' => '=',
    'value' => '',
    'group' => '0',
    'exposed' => FALSE,
    'expose' => array(
      'operator' => FALSE,
      'label' => '',
    ),
    'id' => 'status_extra',
    'table' => 'node',
    'field' => 'status_extra',
    'relationship' => 'none',
  );
}
$filters = $published_filter + $filters;
if (module_exists('search')) {
  $search_filter = array();
  $search_filter['keys'] = array(
    'operator' => 'optional',
    'value' => '',
    'group' => '0',
    'exposed' => TRUE,
    'expose' => array(
      'use_operator' => 0,
      'operator' => 'keys_op',
      'identifier' => 'text',
      'label' => 'Search for',
      'optional' => 1,
      'remember' => 0,
    ),
    'id' => 'keys',
    'table' => 'search_index',
    'field' => 'keys',
    'relationship' => 'none',
  );
  $filters = $search_filter + $filters;
}
$handler->override_option('filters', $filters);
$handler->override_option('access', array(
  'type' => 'project_issue_access_per_user_queue',
  'project_issue_user_argument' => 'uid_touch',
));
$handler->override_option('title', 'My issues');
$handler->override_option('empty', 'No issues match your criteria.');
$handler->override_option('empty_format', '1');
$handler->override_option('items_per_page', 50);
$handler->override_option('use_pager', '1');
$handler->override_option('style_plugin', 'project_issue_table');
$handler->override_option('style_options', array(
  'grouping' => '',
  'override' => 1,
  'sticky' => 1,
  'order' => 'desc',
  'columns' => array(
    'project_issue_queue' => 'project_issue_queue',
    'title' => 'title',
    'timestamp' => 'title',
    'sid' => 'sid',
    'priority' => 'priority',
    'category' => 'category',
    'version' => 'version',
    'comment_count' => 'comment_count',
    'new_comments' => 'comment_count',
    $last_update_field => $last_update_field,
    'name' => 'name',
    'created' => 'created',
    'score' => 'score',
  ),
  'info' => array(
    'project_issue_queue' => array(
      'sortable' => 1,
      'separator' => '',
    ),
    'title' => array(
      'sortable' => 1,
      'separator' => ' ',
    ),
    'timestamp' => array(
      'separator' => '',
    ),
    'sid' => array(
      'sortable' => 1,
      'separator' => '',
    ),
    'priority' => array(
      'sortable' => 1,
      'separator' => '',
    ),
    'category' => array(
      'sortable' => 1,
      'separator' => '',
    ),
    'version' => array(
      'sortable' => 1,
      'separator' => '',
    ),
    'comment_count' => array(
      'sortable' => 1,
      'separator' => '<br />',
    ),
    'new_comments' => array(
      'separator' => '',
    ),
    $last_update_field => array(
      'sortable' => 1,
      'separator' => '',
    ),
    'name' => array(
      'sortable' => 1,
      'separator' => '',
    ),
    'created' => array(
      'sortable' => 1,
      'separator' => '',
    ),
    'score' => array(
      'sortable' => 1,
      'separator' => '',
    ),
  ),
  'default' => module_exists('search') ? 'score' : $last_update_field,
));
$handler = $view->new_display('page', 'Page', 'page_1');
$handler->override_option('path', 'project/issues/user');
$handler->override_option('menu', array(
  'type' => 'normal',
  'title' => 'My issues',
  'description' => '',
  'weight' => '0',
  'name' => 'navigation',
));
$handler->override_option('tab_options', array(
  'type' => 'none',
  'title' => '',
  'description' => '',
  'weight' => 0,
));
$handler = $view->new_display('feed', 'Feed', 'feed_1');
$handler->override_option('style_plugin', 'rss');
$handler->override_option('style_options', array(
  'mission_description' => FALSE,
  'description' => '',
));
$handler->override_option('row_plugin', 'node_rss');
$handler->override_option('path', 'project/issues/user/%/feed');
$handler->override_option('menu', array(
  'type' => 'none',
  'title' => '',
  'description' => '',
  'weight' => 0,
  'name' => 'navigation',
));
$handler->override_option('tab_options', array(
  'type' => 'none',
  'title' => '',
  'description' => '',
  'weight' => 0,
));
$handler->override_option('displays', array(
  'page_1' => 'page_1',
  'default' => 0,
));
$handler->override_option('sitename_title', FALSE);
$handler = $view->new_display('block', 'Block', 'block_1');
$handler->override_option('relationships', array(
  'pid' => array(
    'label' => 'Project node',
    'required' => 1,
    'id' => 'pid',
    'table' => 'project_issues',
    'field' => 'pid',
  ),
));
$handler->override_option('fields', array(
  'project_issue_queue' => array(
    'label' => '',
    'alter' => array(
      'alter_text' => 1,
      'text' => '<div class="project-title">[project_issue_queue]</div>',
    ),
    'link_type' => 'issues',
    'id' => 'project_issue_queue',
    'table' => 'node',
    'field' => 'project_issue_queue',
    'relationship' => 'pid',
  ),
  'title' => array(
    'label' => '',
    'link_to_node' => 1,
    'id' => 'title',
    'table' => 'node',
    'field' => 'title',
    'relationship' => 'none',
  ),
  'priority' => array(
    'label' => '',
    'exclude' => 1,
    'id' => 'priority',
    'table' => 'project_issues',
    'field' => 'priority',
    'relationship' => 'none',
  ),
  'category' => array(
    'label' => '',
    'exclude' => 1,
    'id' => 'category',
    'table' => 'project_issues',
    'field' => 'category',
    'relationship' => 'none',
  ),
  'sid' => array(
    'label' => '',
    'alter' => array(
      'alter_text' => 1,
      'text' => '<div class="status-line"><span class="status">[sid]</span> | <span class="priority">[priority]</span> | <span class="category">[category]</span></div>',
    ),
    'id' => 'sid',
    'table' => 'project_issues',
    'field' => 'sid',
    'relationship' => 'none',
  ),
  'new_comments' => array(
    'label' => '',
    'alter' => array(
      'alter_text' => 1,
      'text' => '[new_comments] new',
    ),
    'hide_empty' => 1,
    'link_to_comment' => 1,
    'exclude' => 1,
    'id' => 'new_comments',
    'table' => 'node',
    'field' => 'new_comments',
    'relationship' => 'none',
  ),
  'timestamp' => array(
    'label' => '',
    'alter' => array(),
    'link_to_node' => 0,
    'comments' => 0,
    'exclude' => 1,
    'hide_empty' => 1,
    'id' => 'timestamp',
    'table' => 'history_user',
    'field' => 'timestamp',
    'relationship' => 'none',
  ),
  'comment_count' => array(
    'label' => '',
    'alter' => array(
      'alter_text' => 1,
      'text' => '<div class="comment-info">[comment_count] replies [new_comments] [timestamp]</div>',
    ),
    'id' => 'comment_count',
    'table' => 'node_comment_statistics',
    'field' => 'comment_count',
    'relationship' => 'none',
  ),
));
$handler->override_option('sorts', $last_update_sort);
$filters = $published_filter + array(
  'sid' => array(
    'operator' => 'in',
    'value' => array(
      'Open' => 'Open',
    ),
    'group' => '0',
    'exposed' => FALSE,
    'expose' => array(
      'operator' => FALSE,
      'label' => '',
    ),
    'id' => 'sid',
    'table' => 'project_issues',
    'field' => 'sid',
    'override' => array(
      'button' => 'Use default',
    ),
    'relationship' => 'none',
  ),
);
$handler->override_option('filters', $filters);
$handler->override_option('items_per_page', 10);
$handler->override_option('use_pager', '0');
$handler->override_option('use_more', 1);
$handler->override_option('use_more_always', 0);
$handler->override_option('style_options', array(
  'grouping' => '',
  'override' => 1,
  'sticky' => 0,
  'order' => 'asc',
  'columns' => array(
    'project_issue_queue' => 'project_issue_queue',
    'title' => 'project_issue_queue',
    'priority' => 'project_issue_queue',
    'category' => 'project_issue_queue',
    'sid' => 'project_issue_queue',
    'new_comments' => 'project_issue_queue',
    'timestamp' => 'project_issue_queue',
    'comment_count' => 'project_issue_queue',
  ),
  'info' => array(
    'project_issue_queue' => array(
      'sortable' => 0,
      'separator' => '',
    ),
    'title' => array(
      'sortable' => 0,
      'separator' => '',
    ),
    'priority' => array(
      'sortable' => 0,
      'separator' => '',
    ),
    'category' => array(
      'sortable' => 0,
      'separator' => '',
    ),
    'sid' => array(
      'sortable' => 0,
      'separator' => '',
    ),
    'new_comments' => array(
      'separator' => '',
    ),
    'comment_count' => array(
      'sortable' => 0,
      'separator' => '',
    ),
    'timestamp' => array(
      'separator' => '',
    ),
  ),
  'default' => '-1',
));
$handler->override_option('block_description', '');
$handler->override_option('block_caching', -1);