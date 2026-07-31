<?php

namespace Drupal\recipes\Services;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use roflcopterdorrie\pluralizer\Pluralizer;

class Ingredient
{
  use DependencySerializationTrait;

  public function __construct(
    protected EntityTypeManagerInterface $entity_type_manager,
    protected MessengerInterface $messenger,
    protected ConfigFactoryInterface $config_factory,
  ) {}

  public function create(array $values) : ?Node {

    // Sanity check for values.
    if (!isset($values['name']) || empty($values['name'])) {
      return NULL;
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
      'title' => strtolower($values['name']),
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
    } else {
      // We couldn't find a match for the aisle that the AI has provided, use Unknown instead.
      $ingredient_aisles = $this->entity_type_manager->getStorage('taxonomy_term')->loadByProperties([
        'name' => "Unknown",
        'vid' => 'recipes_ingredient_aisle',
      ]);
      if (!empty($ingredient_aisles)) {
        $ingredient_aisle = reset($ingredient_aisles);
        $ingredient_node->set('field_recipes_ingredient_aisle', $ingredient_aisle->id());
      }
    }

    $ingredient_node->save();

    return $ingredient_node;
  }

  /**
   * @return Node
   */
  public function populate(Node $ingredient) : Node{
    $aisles = $ingredient->get('field_recipes_ingredient_aisle')->referencedEntities();
    if (!empty($aisles)) {
      $ingredient->aisle = reset($aisles);
    } else {
      // Find the unknown aisle.
      $ingredient_aisles = $this->entity_type_manager->getStorage('taxonomy_term')->loadByProperties([
        'name' => 'Unknown',
        'vid' => 'recipes_ingredient_aisle',
      ]);
      
      if (!empty($ingredient_aisles)) {
        $ingredient->aisle = reset($ingredient_aisles);
      }
    }
   
    $ingredient->amount = $ingredient->get('field_recipes_ingredient_amount')->value ?: NULL;
    $ingredient->extra = $ingredient->get('field_recipes_ingredient_extra')->value ?: NULL;
    $ingredient->name = $ingredient->getTitle();

    return $ingredient;
  }

}
