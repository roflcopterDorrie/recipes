<?php

namespace Drupal\recipes\EventSubscriber;

use Drupal\core_event_dispatcher\Event\Form\FormAlterEvent;
use Drupal\core_event_dispatcher\Event\Form\FormBaseAlterEvent;
use Drupal\core_event_dispatcher\Event\Form\FormIdAlterEvent;
use Drupal\core_event_dispatcher\FormHookEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class EntityTypeSubscriber.
 *
 * @package Drupal\recipes\RecipesFormSubscriber
 */
class RecipesFormSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      FormHookEvents::FORM_ALTER => 'recipeEditForm',
    ];
  }

  /**
   * Alter form.
   *
   * @param \Drupal\core_event_dispatcher\Event\Form\FormAlterEvent $event
   *   The event.
   */
  public function recipeEditForm(FormAlterEvent $event): void {
    $form = &$event->getForm();
    $form_state = $event->getFormState();

    if ($event->getFormId() == 'node_recipes_recipe_edit_form') {

      /** @var \Drupal\node\NodeInterface $node */
      $node = $form_state->getFormObject()->getEntity();

      if (!$node->hasField('field_recipes_ingredients')) {
        return;
      }

      $ingredients = [];
      foreach ($node->get('field_recipes_ingredients')->referencedEntities() as $ingredient) {
        $ingredients[] = [
          'id' => $ingredient->id(),
          'name' => $ingredient->get('field_recipes_ingredient')->entity->getName() ?? NULL,
          'amount' => $ingredient->get('field_recipes_ingredient_amount')->value ?? NULL,
          'extra' => $ingredient->get('field_recipes_ingredient_extra')->value ? '(' . $ingredient->get('field_recipes_ingredient_extra')->value . ')' : NULL
        ];
      }

      $form['#attached']['drupalSettings']['recipes']['ingredients'] = $ingredients;

    }
  }


}