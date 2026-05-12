<?php

namespace Drupal\recipes\EventSubscriber;

use Drupal\Core\Entity\EntityTypeEvent;
use Drupal\Core\Entity\EntityEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class EntityTypeSubscriber.
 *
 * @package Drupal\recipes\RecipesEntitySubscriber
 */
class RecipesEntitySubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() : array {
    return [
      EntityEvents::PREDELETE => 'recipeDelete',
    ];
  }

  /**
   * React to a recipe being deleted.
   *
   * @param \Drupal\Core\Entity\EntityTypeEvent $event
   *   Entity type event.
   */
  public function recipeDelete(EntityTypeEvent $event) {
    // Remove all the ingredients associated with a recipe.

    // Only act if we are deleting a 'recipe' node.
    if ($event->getEntity()->getEntityTypeId() === 'node' && $event->getEntity()->bundle() === 'recipes_recipe') {
      
      // Find all 'ingredient' nodes that reference this recipe.
      $ingredients = $event->getEntity()->get('field_recipes_ingredients')->referencedEntities();

      if (!empty($ingredients)) {
        // Delete the referenced ingredient nodes.
        foreach ($ingredients as $ingredient) {
          $ingredient->delete();
        }
      }
    }
  }


}