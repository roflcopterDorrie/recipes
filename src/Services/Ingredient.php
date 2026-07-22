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

    // Find this ingredient in our taxonomy, or if it doesn't exist, create it.
    $ingredient_term_id = null;

    // We only want to save the singular version of the ingredient name to help 
    // control the data. We will display the plural if need when viewing the ingredient.
    $ingredient_singular = Pluralizer::singularize($values['name'], $this->getExcludeWords());

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

    $terms = $ingredient->get('field_recipes_ingredient')->referencedEntities();
    if (!empty($terms)) {
      $ingredient_term = reset($terms);
      $ingredient->ingredient = $ingredient_term->getName();
      
      $ingredient->ingredient = $this->pluralise($ingredient->amount, $ingredient->ingredient);
    }
    return $ingredient;
  }

  public function pluralise(?string $amount, string $ingredient) : string {
    if (!isset($amount)) {
      return $ingredient;
    }

    $singluar = TRUE;

    // Find any numerical numbers and see if they are above 1.
    if (preg_match("/\d+[\.\,]?\d*/", $amount, $matches) === 1) {
      if (isset($matches[0])) {
        if (floatval($matches[0]) > 1.0) {
          $singluar = FALSE;
        }
      }
    }

    // Look for fractions.
    if ($singluar === TRUE && preg_match("/[½,¾,⅓,¼,⅔]/", $amount) === 1) {
      $singluar = FALSE;
    }

    if ($singluar === FALSE) {
      // Take the last word of the ingredient, that is usually the one we need to check.
      $parts = explode(' ', $ingredient);
      $ingredient_part = array_pop($parts);
      
      $ingredient_part = Pluralizer::pluralize($ingredient_part, $this->getExcludeWords());
      array_push($parts, $ingredient_part);
      return implode(' ', $parts);
    }

    return $ingredient;
  }

  private function getExcludeWords() : array
  {
    $exclude = $this->config_factory->get('recipes.settings')->get('exclude_words_from_plurals');
    if (is_string($exclude)) {
      $exclude = preg_split('/\R+/', trim($exclude));
      return $exclude;
    }
    return [];
  }

}
