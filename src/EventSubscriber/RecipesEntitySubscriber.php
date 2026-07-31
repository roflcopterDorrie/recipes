<?php

namespace Drupal\recipes\EventSubscriber;

use Drupal\core_event_dispatcher\EntityHookEvents;
use Drupal\core_event_dispatcher\Event\Entity\EntityPredeleteEvent;
use Drupal\core_event_dispatcher\Event\Entity\EntityPresaveEvent;
use Drupal\core_event_dispatcher\Event\Entity\EntityViewAlterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\recipes\Services\Ingredient;

/**
 * Class RecipesEntitySubscriber.
 *
 * @package Drupal\recipes\RecipesEntitySubscriber
 */
class RecipesEntitySubscriber implements EventSubscriberInterface
{
  public function __construct(
    protected EntityTypeManagerInterface $entity_type_manager,
    protected AccountProxyInterface $current_user,
    protected Ingredient $ingredient,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      EntityHookEvents::ENTITY_PRE_DELETE => 'onEntityPreDelete',
      EntityHookEvents::ENTITY_VIEW_ALTER => 'entityViewAlter',
      EntityHookEvents::ENTITY_PRE_SAVE => 'onEntityPreSave'
    ];
  }

  /**
   * Entity view alter.
   *
   * @param \Drupal\core_event_dispatcher\Event\Entity\EntityViewAlterEvent $event
   *   The event.
   */
  public function entityViewAlter(EntityViewAlterEvent $event): void {
    if ($event->getEntity()->getEntityTypeId() === 'node') {
      switch ($event->getEntity()->bundle()) {
        case 'recipes_recipe':
          $this->nodeRecipeViewAlter($event);
          break;
      }
    }
  }

  /**
   * Entity pre delete.
   *
   * @param \Drupal\core_event_dispatcher\Event\Entity\EntityPredeleteEvent $event
   *   The event.
   */
  public function onEntityPreDelete(EntityPredeleteEvent $event): void {
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
      $storage = $this->entity_type_manager->getStorage('recipes_recipe_list');
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

    // Remove all shopping list items if a shopping list is deleted. 
  }

  /**
   * Node Recipe view alter.
   *
   * @param \Drupal\core_event_dispatcher\Event\Entity\EntityViewAlterEvent $event
   *   The event.
   */
  public function nodeRecipeViewAlter(EntityViewAlterEvent $event): void {
    // Add an 'Add to list' button at the bottom of Recipe nodes.
    $build = &$event->getBuild();

    if ($build['#view_mode'] == 'full') {

      $build['my_list_button'] = [
        '#lazy_builder' => [
            'recipes.my_list:buildButton',
            [ $event->getEntity()->id() ],
          ],
        '#create_placeholder' => TRUE,
        '#weight' => 100,
      ];
    }
  }

  /**
   * Entity pre save.
   *
   * @param \Drupal\core_event_dispatcher\Event\Entity\EntityPresaveEvent $event
   *   The event.
   */
  public function onEntityPreSave(EntityPresaveEvent $event): void {
    if ($event->getEntity()->getEntityTypeId() === 'node' && $event->getEntity()->bundle() === 'recipes_ingredient') {
      // Check the ingredients amount field and convert any fractions to their character counterpart.
      // eg 1/2, 1/4, 3/4 = ½, ¼, ¾
      $amount = $event->getEntity()->get('field_recipes_ingredient_amount')->value;
      if (!empty($amount)) { // Custom ingredients don't have an amount.
        $amount = str_replace('1/2', '½', $amount);
        $amount = str_replace('3/4', '¾', $amount);
        $amount = str_replace('1/4', '¼', $amount);
        $amount = str_replace('1/3', '⅓', $amount);
        $amount = str_replace('2/3', '⅔', $amount);
        $event->getEntity()->set('field_recipes_ingredient_amount', $amount);
      }
    }
  }

}
