<?php

namespace Drupal\recipes\EventSubscriber;

use Drupal\Core\Form\FormEvents;
use Drupal\Core\Form\Event\FormAlterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class EntityTypeSubscriber.
 *
 * @package Drupal\recipes\RecipesFormSubscriber
 */
class RecipesFormSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() : array {
    return [
      FormEvents::ALTER => 'recipeEditForm',
    ];
  }

  /**
   * Get Recipe ingredients for use in Add Ingredient widget.
   *
   * @param \Drupal\Core\Entity\FormAlterEvent $event
   *   Entity type event.
   */
  public function recipeEditForm(FormAlterEvent $event) {
    $form = &$event->getForm();
    $form_state = $event->getFormState();

    if ($event->getFormId() == 'node_recipe_form') {

      /** @var \Drupal\node\NodeInterface $node */
      $node = $form_state->getFormObject()->getEntity();

      if (!$node->hasField('field_recipes_ingredients')) {
        return;
      }

      $ingredients = [];
      foreach ($node->get('field_recipes_ingredients')->referencedEntities() as $ingredient) {
        // Create a nice label.
        $parts = array_filter([
          $ingredient->get('field_recipes_ingredient_amount')->value ?? NULL,
          $ingredient->get('field_recipes_ingredient_ingredient')->value ?? NULL,
          $ingredient->get('field_recipes_ingredient_extra')->value ?? NULL,
        ]);

        $label = implode(' ', $parts);
        $ingredients[] = [
          'id' => $ingredient->id(),
          'label' => $label,
        ];
      }

      $form['#attached']['drupalSettings']['recipes']['ingredients'] = $ingredients;

    }
  }


}