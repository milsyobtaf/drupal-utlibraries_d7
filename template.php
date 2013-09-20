<?php

/**
 * Override or insert variables into the maintenance page template.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("maintenance_page" in this case.)
 */
function utlibraries_d7_preprocess_maintenance_page(&$vars, $hook) {
  // When a variable is manipulated or added in preprocess_html or
  // preprocess_page, that same work is probably needed for the maintenance page
  // as well, so we can just re-use those functions to do that work here.
  // utlibraries_d7_preprocess_html($variables, $hook);
  // utlibraries_d7_preprocess_page($variables, $hook);

  // This preprocessor will also be used if the db is inactive. To ensure your
  // theme is used, add the following line to your settings.php file:
  // $conf['maintenance_theme'] = 'utlibraries_d7';
  // Also, check $vars['db_is_active'] before doing any db queries.
}

/**
 * Implements hook_modernizr_load_alter().
 *
 * @return
 *   An array to be output as yepnope testObjects.
 */
// function utlibraries_d7_modernizr_load_alter(&$load) {

//   // We will check for touch events, and if we do load the hammer.js script.
//   $load[] = array(
//     'test' => 'Modernizr.touch',
//     'yep'  => array('/'. drupal_get_path('theme','utlibraries_d7') . '/javascripts/hammer.js'),
//   );

//   return $load;
// }

/**
 * Implements hook_preprocess_html()
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("html" in this case.)
 */
/* -- Delete this line if you want to use this function
function utlibraries_d7_preprocess_html(&$vars) {
}
*/
/**
 * Override or insert variables into the page template.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
/* -- Delete this line if you want to use this function */
function utlibraries_d7_preprocess_page(&$vars) {
  $vars['theme_path'] = '/d7/' . drupal_get_path('theme', variable_get('theme_default', NULL));
  if (isset($vars['node']) && $vars['node']->type == 'branch') {
    drupal_add_js('window.fitText( document.getElementsByClassName("frontpage-block-title"), 1.3 );', array('type' => 'inline', 'scope' => 'footer', 'weight' => 5));
    }
}
/**
 * Override or insert variables into the region templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("region" in this case.)
 */
/* -- Delete this line if you want to use this function */
function utlibraries_d7_preprocess_region(&$vars, $hook) {
   $vars['theme_path'] = '/d7/' . drupal_get_path('theme', variable_get('theme_default', NULL));
}


/**
 * Override or insert variables into the block templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
/* -- Delete this line if you want to use this function
function utlibraries_d7_preprocess_block(&$vars, $hook) {

}
// */

/**
 * Override or insert variables into the entity template.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("entity" in this case.)
 */
/* -- Delete this line if you want to use this function
function utlibraries_d7_preprocess_entity(&$vars, $hook) {

}
// */

/**
 * Override or insert variables into the node template.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
/* -- Delete this line if you want to use this function */
function utlibraries_d7_preprocess_node(&$vars) {
  // Get a list of all the regions for this theme
  foreach (system_region_list($GLOBALS['theme']) as $region_key => $region_name) {
    // Get the content for each region and add it to the $region variable
    if ($blocks = block_get_blocks_by_region($region_key)) {
      $vars['region'][$region_key] = $blocks;
      // If the region exists, add it as a class to the node
      $vars['classes_array'][] = str_replace('_', '-', $region_key);
    }
    else {
      $vars['region'][$region_key] = array();
    }
  }
    if($vars['type'] == 'branch') {
    // Add js
    drupal_add_js(drupal_get_path('theme', 'utlibraries_d7') . '/javascripts/fittext.js');
      $variables['scripts'] = drupal_get_js();
  }
}
/* // */

/**
 * Override or insert variables into the field template.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("field" in this case.)
 */
/* -- Delete this line if you want to use this function
function utlibraries_d7_preprocess_field(&$vars, $hook) {

}
// */

/**
 * Override or insert variables into the comment template.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
/* -- Delete this line if you want to use this function
function utlibraries_d7_preprocess_comment(&$vars, $hook) {
  $comment = $vars['comment'];
}
// */

/**
 * Override or insert variables into the views template.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 */
/* -- Delete this line if you want to use this function
function utlibraries_d7_preprocess_views_view(&$vars) {
  $view = $vars['view'];
}
// */


/**
 * Override User Login page for custom theming
 *
 * @param $vars
 *    An array of variables to pass to the theme template.
 */

function utlibraries_d7_theme() {
  $vars = array();
  // create custom user-login.tpl.php
  $vars['user_login'] = array(
  'render element' => 'form',
  'path' => drupal_get_path('theme', 'utlibraries_d7') . '/tpl',
  'template' => 'user--login',
  'preprocess functions' => array(
  'utlibraries-d7_preprocess_user_login'
  ),
 );
return $vars;
}

/**
 * Override or insert css on the site.
 *
 * @param $css
 *   An array of all CSS items being requested on the page.
 */
/* -- Delete this line if you want to use this function
function utlibraries_d7_css_alter(&$css) {

}
// */

/**
 * Override or insert javascript on the site.
 *
 * @param $js
 *   An array of all JavaScript being presented on the page.
 */
/* -- Delete this line if you want to use this function
function utlibraries_d7_js_alter(&$js) {

}
// */
