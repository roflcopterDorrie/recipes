<?php

namespace Drupal\recipes\Plugin\Filter;

use DOMDocument;
use Drupal\Core\Entity\EntityInterface;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Provides a filter to render ingredients dynamically.
 *
 * @Filter(
 * id = "recipes_filter_ingredient",
 * title = @Translation("Render Ingredients"),
 * type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_REVERSIBLE,
 * weight = 0
 * )
 */
class FilterIngredient extends FilterBase implements ContainerFactoryPluginInterface {

  protected $entityTypeManager;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  public function process($text, $langcode) {
    $result = new FilterProcessResult($text);

    if (strpos($text, 'ingredient') === FALSE) {
      return $result;
    }

    // Use DOMDocument to safely parse and swap the nodes
    $dom = new \DOMDocument();
    // Load HTML handling UTF-8 safely
    @$dom->loadHTML(mb_convert_encoding($text, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
    $xpath = new \DOMXPath($dom);
    $elements = $xpath->query('//ingredient');

    foreach ($elements as $element) {
      $id = $element->getAttribute('data-id');
      
      if ($id) {
        // Load ingredient
        if ($ingredient = $this->entityTypeManager->getStorage('node')->load($id)) {
          // Load the term info.
          if ($ingredient_term = $ingredient->get('field_recipes_ingredient')->entity) {
            // Create replacement node.
            $newNode = $dom->createElement('span', htmlspecialchars($ingredient_term->getName()));
            $newNode->setAttribute('class', 'ingredient');
            
            // Swap the placeholder for the real HTML.
            $element->parentNode->replaceChild($newNode, $element);

            // Add ingredients in a list at the bottom of the text.
            $dom = $this->addIngredientToList($dom, $ingredient);
            
            // Add cache tags so if the ingredient changes, the page cache clears.
            $result->addCacheTags($ingredient->getCacheTags());
          }
        }
      }
    }

    $result->setProcessedText($dom->saveHTML());
    return $result;
  }

  private function addIngredientToList(\DOMDocument $dom, EntityInterface $ingredient_data) {
    // Find out if a list has been created yet at the bottom.
    $xpath = new \DOMXPath($dom);
    $elements = $xpath->query("//ul[@class='ingredient-list']");

    // If not, create the list.
    if ($elements->length == 0) {
      $new_list = $dom->createElement('ul');
      $new_list->setAttribute('class', 'ingredient-list');
      $list = $dom->appendChild($new_list);
    } else {
      $list = $elements[0];
    }

    // Add ingredient to list.
    // List element.
    $ingredient = $dom->createElement('li');
    $ingredient->setAttribute('class', 'ingredient');

    // Add a data wrapper for easier styling.
    $ingredient_info = $dom->createElement('div');
    $ingredient_info->setAttribute('class', 'ingredient-data');

    // Amount.
    if ($amount_data = $ingredient_data->get('field_recipes_ingredient_amount')->value) {
      $amount = $dom->createElement('span', htmlspecialchars($amount_data));
      $amount->setAttribute('class', 'ingredient__amount');
      $ingredient_info->appendChild($amount);
    }

    // Name.
    if ($ingredient_term = $ingredient_data->get('field_recipes_ingredient')->entity) {
      $name = $dom->createElement('span', htmlspecialchars($ingredient_term->getName()));
      $name->setAttribute('class', 'ingredient__name');
      $ingredient_info->appendChild($name);
    }

    // Extra.
    if ($extra_data = $ingredient_data->get('field_recipes_ingredient_extra')->value) {
      $extra = $dom->createElement('span', '(' . htmlspecialchars($extra_data) . ')');
      $extra->setAttribute('class', 'ingredient__extra');
      $ingredient_info->appendChild($extra);
    }

    // Add the data to the ingredient element.
    $ingredient->appendChild($ingredient_info);

    // Add the new ingredient to the item.
    $list->appendChild($ingredient);

    return $dom;

  }
}