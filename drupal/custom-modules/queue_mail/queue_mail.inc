<?php
// $Id: queue_mail.inc,v 1.2.2.1 2008/10/27 16:17:44 kbahey Exp $

/**
 * Wrapper function to use job queue
 */
function drupal_mail_wrapper($message) {
  if (function_exists('job_queue_add')) {
    job_queue_add('queue_mail_send', t('Queued email'), array($message));
    return t('Email message queued for delivery.');
  }
  else {
    queue_mail_send($message);
    return t('Queuing unavailable. Email sent directly.');
  }
}
