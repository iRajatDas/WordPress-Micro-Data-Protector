<?php
/*
Plugin Name: Protect schema.org Micro Data markup
Description: Easy tool to stop HTML editor from removing schema.org/microdata tags from post or page content.
Author: Rajat Das
Author URI: https://pixiboom.com/
Version: 0.1
*/

function tsm_get_extended_valid_elements()
{
  $elements = array(
    '@' => array(
      'id',
      'class',
      'style',
      'title',
      'itemscope',
      'itemtype',
      'itemprop',
      'datetime',
      'rel'
    ),
    'article', 'div', 'p', 'dl', 'dt', 'dd', 'ul', 'li', 'span',
    'a' => array(
      'href',
      'name',
      'target',
      'rev',
      'charset',
      'lang',
      'tabindex',
      'accesskey',
      'type',
      'class',
      'onfocus',
      'onblur'
    ),
    'img' => array(
      'src',
      'alt',
      'width',
      'height'
    ),
    'meta' => array(
      'content'
    ),
    'link' => array(
      'href'
    ),
    'time' => array(
      'itemprop'
    )
  );

  return apply_filters('tsm_extended_valid_elements', $elements);
}

function tsm_tinymce_init($settings)
{
  if (!empty($settings['extended_valid_elements'])) {
    $settings['extended_valid_elements'] .= ',';
  }

  $result = $settings['extended_valid_elements'];

  $elements = tsm_get_extended_valid_elements();

  foreach ($elements as $key => $element) {
    if (is_array($element) && !empty($key)) {
      $name = $key;
      $attributes = $element;
    } else {
      $name = $element;
      $attributes = array();
    }

    if (!empty($result)) {
      $result .= ',';
    }

    $result .= $name;

    if (!empty($attributes)) {
      $result .= '[' . implode('|', $attributes) . ']';
    }
  }

  $settings['extended_valid_elements'] = $result;
  if (!isset($settings['valid_children'])) {
    $settings['valid_children'] = '';
  }
  $settings['valid_children'] .= '+body[meta],+div[meta],+body[link],+div[link]';

  return $settings;
}

add_filter('tiny_mce_before_init', 'tsm_tinymce_init');
