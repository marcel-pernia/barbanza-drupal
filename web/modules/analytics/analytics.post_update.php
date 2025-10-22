<?php

/**
 * @file
 * Post-update hooks for the analytics module.
 */

/**
 * Remove the disable_floc setting since Drupal core now provides the header.
 */
function analytics_post_update_remove_disable_floc() {
  $config = \Drupal::configFactory()->getEditable('analytics.settings');
  $config->clear('privacy.disable_floc');
  $config->save();
}
