<?php
// Plugin definition
$plugin = array(
  'title' => t('Two Column, 30-70'),
  'icon' => 'brenham.png',
  'category' => t('Columns: 2'),
  'theme' => 'brenham',
  'css' => 'brenham.css',
  'regions' => array(
    'header' => t('Header'),
    'sidebar' => t('Content Sidebar'),
    'contentmain' => t('Content'),
  ),
);
