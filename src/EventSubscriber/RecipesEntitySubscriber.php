<?php

namespace Drupal\recipes\EventSubscriber;

use Drupal\core_event_dispatcher\EntityHookEvents;
use Drupal\core_event_dispatcher\Event\Entity\EntityPredeleteEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RecipesEntitySubscriber.
 *
 * @package Drupal\recipes\RecipesEntitySubscriber
 */
class RecipesEntitySubscriber implements EventSubscriberInterface  {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      EntityHookEvents::ENTITY_PRE_DELETE => 'onEntityDelete',
    ];
  }

  /**
   * Entity pre delete.
   *
   * @param \Drupal\core_event_dispatcher\Event\Entity\EntityPredeleteEvent $event
   *   The event.
   */
  public function onEntityDelete(EntityPredeleteEvent $event): void {
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