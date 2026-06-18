<?php

namespace Drupal\recipes\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an Ingredient Search Block.
 *
 * @Block(
 * id = "recipes_ingredient_search",
 * admin_label = @Translation("Recipes - Ingredient Search"),
 * category = @Translation("Custom")
 * )
 */
class RecipesIngredientSearch extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'ingredient_search',
      '#title' => $this->t('Ingredient search'),
      '#attached' => [
        'library' => [
          'recipes/ingredient_search',
        ],
      ],
    ];
  }
}
