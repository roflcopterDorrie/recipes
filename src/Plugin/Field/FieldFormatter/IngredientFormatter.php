<?php

namespace Drupal\recipes\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * @FieldFormatter(
 *   id = "recipes_ingredient_formatter",
 *   label = @Translation("Ingredient formatter"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class IngredientFormatter extends FormatterBase {

  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $node = $item->entity;

      $elements[$delta] = [
        '#theme' => 'recipes_ingredient_summary',
        '#node' => $node,
      ];
    }

    return $elements;
  }

}
