<?php

namespace Drupal\text_spinner\Spinner;

/**
 * Class TextSpinner
 * @package Drupal\text_spinner\Spinner
 */
class TextSpinner {

  /**
   * String used to escape special characters used by the spinner.
   */
  const ESCAPE_STRING = '\\';

  /**
   * Open string.
   */
  const OPEN_STRING = '{';

  /**
   * Close string.
   */
  const CLOSE_STRING = '}';

  /**
   * Delimiter between options.
   */
  const DELIMITER = '|';

  /**
   * @param string $text
   * @return string
   */
  public static function spin(string $text): string {
    if (empty($text)) {
      return '';
    }
    $spinned_text = '';
    $pieces = static::split($text);
    if (count($pieces) === 1 && static::findOpenPosition(reset($pieces)) === FALSE) {
      $spinned_text = static::spinPiece(reset($pieces));
    }
    else {
      foreach ($pieces as $piece) {
        $spinned_text .= static::spin($piece);
      }
    }
    return $spinned_text;
  }

  /**
   * @param string $piece
   * @return string
   */
  protected static function spinPiece(string $piece): string {
    $parts = static::getParts($piece);
    return static::choosePiece($parts);
  }

  /**
   * This function look for pipes "|" out of curly brackets "{}" to split a string.
   * @param string $text
   * @return array
   */
  protected static function getParts(string $text): array {
    // If no curly brackets are then the process is simple.
    if (static::findOpenPosition($text) === FALSE) {
      $parts = explode(static::DELIMITER, $text);
    }
    else {
      $parts = static::getPartsFromStringWithCurlBrackets($text);
    }
    return $parts;
  }

  /**
   * Check if the previous position of an array of characters is the special character
   * used to escape the current one from a position.
   *
   * @param array $array
   * @param int $position
   * @return bool
   */
  protected static function isCharacterToEscape(array $array, int $position): bool {
    return (isset($array[$position-1]) && $array[$position-1] === static::ESCAPE_STRING);
  }

  /**
   * @param string $text
   * @return array
   */
  protected static function getPartsFromStringWithCurlBrackets(string $text): array {
    $parts = [];
    $array_str = str_split($text);
    $temporal_str = '';
    $open = FALSE;
    $open_count = 0;
    foreach ($array_str as $key => $item) {
      if (static::isCharacterToEscape($array_str, $key)) {
        $temporal_str .= $item;
      }
      elseif (!$open && $item !== static::DELIMITER && $item !== static::OPEN_STRING) {
        $temporal_str .= $item;
      }
      elseif (!$open && $item === static::DELIMITER) {
        $parts[] = $temporal_str;
        $temporal_str = '';
      }
      elseif (!$open && $item === static::OPEN_STRING) {
        $open = TRUE;
        $temporal_str .= $item;
      }
      elseif ($open) {
        if ($item !== static::OPEN_STRING && $item !== static::CLOSE_STRING) {
          $temporal_str .= $item;
        }
        elseif ($item === static::OPEN_STRING) {
          $open_count++;
          $temporal_str .= $item;
        }
        elseif ($item === static::CLOSE_STRING && $open_count > 0) {
          $open_count--;
          $temporal_str .= $item;
        }
        elseif ($item === static::CLOSE_STRING && $open_count === 0) {
          $open = FALSE;
          $temporal_str .= $item;
        }
      }
    }
    if (!empty($temporal_str)) {
      $parts[] = $temporal_str;
      $temporal_str = '';
    }
    return $parts;
  }

  /**
   * @param array $pieces
   * @return string
   */
  protected static function choosePiece(array $pieces): string {
    $index = array_rand($pieces);
    return $pieces[$index];
  }

  /**
   * @param string $text
   * @return array
   */
  protected static function split(string $text): array {
    // If we can found pipes "|" out of curly brackets "{}", we can already choose a part at this point.
    $pieces_split = static::getParts($text);
    if (count($pieces_split) > 1) {
      $split = [static::choosePiece($pieces_split)];
    }
    // no pipes "|" found out of curly brackets "{}"
    else {
      $split = static::splitUniquePart($text);
    }

    return $split;
  }

  /**
   * Split a text with no pipes "|" found out of curly brackets "{}"
   * @param string $text
   * @return array
   */
  protected static function splitUniquePart(string $text): array {
    $split = [];
    $array_str = str_split($text);
    $temporal_str = '';
    $open = FALSE;
    $open_count = 0;

    foreach ($array_str as $key => $item) {
      if (static::isCharacterToEscape($array_str, $key)) {
        $temporal_str .= $item;
      }
      elseif (!$open && $item !== static::OPEN_STRING) {
        $temporal_str .= $item;
      }
      elseif (!$open && $item === static::OPEN_STRING) {
        $open = TRUE;
        $split[] = $temporal_str;
        $temporal_str = '';
      }
      elseif ($open) {
        if ($item !== static::OPEN_STRING && $item !== static::CLOSE_STRING) {
          $temporal_str .= $item;
        }
        elseif ($item === static::OPEN_STRING) {
          $open_count++;
          $temporal_str .= $item;
        }
        elseif ($item === static::CLOSE_STRING && $open_count > 0) {
          $open_count--;
          $temporal_str .= $item;
        }
        elseif ($item === static::CLOSE_STRING && $open_count === 0) {
          $open = FALSE;
          $split[] = $temporal_str;
          $temporal_str = '';
        }
      }
    }

    if (!empty($temporal_str)) {
      $split[] = $temporal_str;
      $temporal_str = '';
      $open = FALSE;
    }

    foreach ($split as $split_key => $split_item) {
      if (empty($split_item)) {
        unset($split[$split_key]);
      }
    }
    if (count($split) === 3 && array_slice($split, 1, 1)[0] === static::DELIMITER) {
      $pieces_split = [
        0 => reset($split),
        1 => end($split)
      ];
      $split = [static::choosePiece($pieces_split)];
    }

    return $split;
  }

  /**
   * @param string $text
   * @return bool|int
   */
  protected static function findOpenPosition(string $text) {
    return strpos($text, static::OPEN_STRING);
  }

  /**
   * @param string $text
   * @return bool|int
   */
  protected static function findDelimiterPosition(string $text) {
    return strpos($text, static::DELIMITER);
  }

}
