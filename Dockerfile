# See https://github.com/dcycle/docker-drupal
FROM dcycle/drupal:6

# Production modules can be downloaded here; download development modules
# in Dockerfile-drupal-dev.
RUN drush dl acl-6.x-1.2
RUN drush dl content_access-6.x-1.2
RUN drush dl adminblock-6.x-1.3
RUN drush dl admin_menu-6.x-1.5
RUN drush dl permission_select-6.x-1.8
RUN drush dl me-6.x-2.9
RUN drush dl active_translation-6.x-1.4
RUN drush dl better_formats-6.x-1.2
RUN drush dl codefilter-6.x-1.0
RUN drush dl comment_upload-6.x-1.0-alpha6
RUN drush dl css_injector-6.x-1.4
RUN drush dl dhtml_menu-6.x-3.5
RUN drush dl diff-6.x-2.1
RUN drush dl fblikebutton-6.x-1.6
RUN drush dl genpass-6.x-1.3
RUN drush dl htmlcomment-6.x-1.0
RUN drush dl image_resize_filter-6.x-1.9
RUN drush dl jqp-6.x-2.5
RUN drush dl lightbox2-6.x-1.11
RUN drush dl logintoboggan-6.x-1.11
RUN drush dl mailhandler-6.x-1.8
RUN drush dl masquerade-6.x-1.4
RUN drush dl mollom-6.x-2.15
RUN drush dl node_convert-6.x-1.6
RUN drush dl prepopulate-6.x-2.2
RUN drush dl skinr-6.x-1.6
RUN drush dl strongarm-6.x-2.2
RUN drush dl tweet-6.x-4.3
RUN drush dl webform-6.x-2.9
RUN drush dl cck-6.x-2.8
RUN drush dl filefield-6.x-3.13
RUN drush dl imagefield-6.x-3.11
RUN drush dl imagefield_zip-6.x-1.2
RUN drush dl link-6.x-2.8
RUN drush dl swfupload-6.x-2.0-beta8
RUN drush dl ctools-6.x-1.15
RUN drush dl chart-6.x-1.2
RUN drush dl content_profile-6.x-1.0-beta4
RUN drush dl custom_breadcrumbs-6.x-2.0-rc1
RUN drush dl date-6.x-2.6
RUN drush dl devel-6.x-1.27
RUN drush dl features-6.x-1.0
RUN drush dl image-6.x-1.1
RUN drush dl imageapi-6.x-1.8
RUN drush dl imagecache-6.x-2.0-beta10
RUN drush dl markdown-6.x-1.4
RUN drush dl countries_api-6.x-1.1
RUN drush dl mail_logger-6.x-1.0
RUN drush dl simplenews-6.x-1.3
RUN drush dl nodewords-6.x-1.11
RUN drush dl i18n-6.x-1.5
RUN drush dl l10n_update-6.x-1.0-beta3
RUN drush dl translation_table-6.x-1.2
RUN drush dl node_export-6.x-3.x-dev
RUN drush dl opengraph_meta-6.x-1.7
RUN drush dl openlayers-6.x-2.0-alpha8
RUN drush dl og-6.x-2.1
RUN drush dl project-6.x-1.0-alpha5
RUN drush dl ed_readmore-6.x-3.0
RUN drush dl honeypot-6.x-1.19
RUN drush dl subscriptions-6.x-1.4
RUN drush dl jcarousel-6.x-2.2
RUN drush dl wysiwyg-6.x-2.3
RUN drush dl views-6.x-2.18
RUN drush dl views_bulk_operations-6.x-1.17
RUN drush dl views-6.x-2.18
RUN drush dl fusion-6.x-1.0
RUN drush dl google_analytics-6.x-3.6

RUN drush dl registry_rebuild-7.x-2.x

RUN cp sites/default/default.settings.php sites/default/settings.php
RUN echo 'require_once "/local-settings/local-settings.php";' >> ./sites/default/settings.php
RUN cd sites && ln -s ../sites/default terredesjeunes.org
