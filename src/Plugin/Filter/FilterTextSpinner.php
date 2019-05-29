<?php

namespace Drupal\text_spinner\Plugin\Filter;

use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Drupal\text_spinner\Spinner\TextSpinner;

/**
 * @Filter(
 *   id = "filter_text_spinner",
 *   title = @Translation("Text Spinner Filter"),
 *   description = @Translation("Filter to spin text"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 * )
 */
class FilterTextSpinner extends FilterBase {

  /**
   * The filter id as const.
   */
  const FILTER_ID = 'filter_text_spinner';

  /**
   * {@inheritdoc}
   */
  public function process($text , $langcode) {
    $text_spinned = TextSpinner::spin($text);
    return new FilterProcessResult($text_spinned);
  }

}