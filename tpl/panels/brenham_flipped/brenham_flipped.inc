<?php
// Plugin definition
$plugin = array(
  'title' => t('Two Column, 70-30'),
  'icon' => 'brenham-flipped.png',
  'category' => t('Columns: 2'),
  'theme' => 'brenham_flipped',
  'css' => 'brenham-flipped.css',
  'regions' => array(
    'header' => t('Header'),
    'sidebar' => t('Content Sidebar'),
    'contentmain' => t('Content'),
  ),
);
