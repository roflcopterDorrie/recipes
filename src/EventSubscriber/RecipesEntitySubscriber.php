<?php

namespace Drupal\recipes\EventSubscriber;

use Drupal\core_event_dispatcher\EntityHookEvents;
use Drupal\core_event_dispatcher\Event\Entity\EntityPredeleteEvent;
use Drupal\core_event_dispatcher\Event\Entity\EntityViewAlterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class RecipesEntitySubscriber.
 *
 * @package Drupal\recipes\RecipesEntitySubscriber
 */
class RecipesEntitySubscriber implements EventSubscriberInterface
{


  protected EntityTypeManagerInterface $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }


  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array
  {
    return [
      EntityHookEvents::ENTITY_PRE_DELETE => 'onEntityDelete',
      EntityHookEvents::ENTITY_VIEW_ALTER => 'onEntityViewAlter'
    ];
  }

  /**
   * Entity pre delete.
   *
   * @param \Drupal\core_event_dispatcher\Event\Entity\EntityPredeleteEvent $event
   *   The event.
   */
  public function onEntityDelete(EntityPredeleteEvent $event): void
  {
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

      // Remove recipe from any lists.
      $storage = $this->entityTypeManager->getStorage('recipes_recipe_list');
      $lists = $storage->loadMultiple();
      foreach ($lists as $list) {
        foreach ($list->get('recipes')->referencedEntities() as $delta => $recipe) {
          if ($recipe->id() == $event->getEntity()->id()) {
            $list->get('recipes')->removeItem($delta);
          }
        }
        $list->save();
      }
    }
  }

  /**
   * Entity view alter.
   *
   * @param \Drupal\core_event_dispatcher\Event\Entity\EntityViewAlterEvent $event
   *   The event.
   */
  public function onEntityViewAlter(EntityViewAlterEvent $event): void
  {
    // Add an 'Add to list' button at the bottom of Recipe nodes.
    if ($event->getEntity()->getEntityTypeId() === 'node' && $event->getEntity()->bundle() === 'recipes_recipe') {
      // Check that this recipe isn't already in the list.

      $build = &$event->getBuild();

      $build['add_to_list_button'] = [
        '#theme' => 'add_to_list_button',
        '#label' => 'Add to list',
        '#id' => $event->getEntity()->id(),
        '#weight' => 100
      ];
      
    }
  }
}
