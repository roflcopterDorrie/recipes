<?php

namespace Drupal\recipes\Services;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Doctrine\Inflector\InflectorFactory;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use Drupal\Core\Messenger\MessengerInterface;

class Ingredient
{
  use DependencySerializationTrait;

  public function __construct(
    protected EntityTypeManagerInterface $entity_type_manager,
    protected MessengerInterface $messenger,
  ) {}

  public function create(array $values) : ?Node {

    // Sanity check for values.
    if (!isset($values['name'])) {
      $this->messenger->addError('To create an ingredient you must provide a "name".');
      return NULL;
    }

    // Find this ingredient in our taxonomy, or if it doesn't exist, create it.
    $ingredient_term_id = null;

    // We only want to save the singular version of the ingredient name to help 
    // control the data. We will display the plural if need when viewing the ingredient.
    $inflector = InflectorFactory::create()->build();
    $ingredient_singular = strtolower($inflector->singularize($values['name']));

    $ingredient_terms = $this->entity_type_manager->getStorage('taxonomy_term')->loadByProperties([
      'name' => $ingredient_singular,
      'vid' => 'recipes_ingredient',
    ]);
    if (!empty($ingredient_terms)) {
      $ingredient_term = reset($ingredient_terms);
      $ingredient_term_id = $ingredient_term->id();
    } else {
      $ingredient_term = Term::create([
        'vid' => 'recipes_ingredient',
        'name' => $ingredient_singular,
      ]);
      $ingredient_term->save();
      $ingredient_term_id = $ingredient_term->id();
    }

    // Convert from imperial to metric for measurements.
    $amount = NULL;
    if (isset($values['amount']) && $values['amount'] !== null) {
      $amount = $values['amount'];
      $pattern = '/(\d+(?:\/\d+)?|[\d\.]+)\s*(lb|lbs|pound|pounds|oz|ounce|ounces)\b/i';
      if (preg_match($pattern, $amount, $match)) {
        $quantity_text = $match[1];
        $unit = strtolower($match[2]);

        $quantity = new Mass($quantity_text, $unit);
        if ($quantity->toUnit('kg') < 1) {
          $amount = number_format($quantity->toUnit('g'), 0) . " grams";
        } else {
          $amount = number_format($quantity->toUnit('kg'), 2) . " kgs";
        }
      }
    }

    $ingredient_node = Node::create([
      'type' => 'recipes_ingredient',
      'title' => $ingredient_singular,
      'field_recipes_ingredient' => ['target_id' => $ingredient_term_id],
      'field_recipes_ingredient_amount' => $amount,
      'field_recipes_ingredient_extra' => $values['extra'] ?? null,
    ]);

    $ingredient_aisles = $this->entity_type_manager->getStorage('taxonomy_term')->loadByProperties([
      'name' => $values['category'],
      'vid' => 'recipes_ingredient_aisle',
    ]);
    if (!empty($ingredient_aisles)) {
      $ingredient_aisle = reset($ingredient_aisles);
      $ingredient_node->set('field_recipes_ingredient_aisle', $ingredient_aisle->id());
    }

    $ingredient_node->save();

    return $ingredient_node;
  }

  

}
