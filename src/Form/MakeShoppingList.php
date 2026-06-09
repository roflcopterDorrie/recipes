<?php

namespace Drupal\recipes\Form;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\recipes\Entity\ShoppingList;
use Drupal\recipes\Entity\ShoppingListItem;

/**
 * Implements an form to generate a Shopping List.
 */
class MakeShoppingList extends FormBase
{
  use DependencySerializationTrait;

  public function __construct(
    protected EntityTypeManagerInterface $entity_type_manager,
    protected AccountProxyInterface $current_user
  ) {}

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('current_user'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'recipes_make_shopping_list_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['#prefix'] = '<div id="recipe-form-wrapper">';
    $form['#suffix'] = '</div>';
   
    // Load up the user's list.
    $storage = $this->entity_type_manager->getStorage('recipes_recipe_list');
    $entities = $storage->loadByProperties([
      'uid' => $this->current_user->id(),
    ]);
    $recipe_list = reset($entities) ?: NULL;
    if ($recipe_list) {
      
      $form['recipes'] = [
        '#type' => 'container',
        '#tree' => TRUE,
      ];

      foreach($recipe_list->get('recipes')->referencedEntities() as $delta => $recipe) {
        
        $form['recipes'][$recipe->id()] = [
          '#type' => 'details',
          '#title' => $recipe->getTitle(),
          '#open' => TRUE
        ];

        foreach($recipe->get('field_recipes_ingredients')->referencedEntities() as $delta1 => $ingredient) {
          $ingredient_term = $ingredient->get('field_recipes_ingredient')->referencedEntities();
          $label = array_filter([
            $ingredient->get('field_recipes_ingredient_amount')->value ?: NULL,
            reset($ingredient_term)->getName() ?: NULL,
            $ingredient->get('field_recipes_ingredient_extra')->value ? '(' . $ingredient->get('field_recipes_ingredient_extra')->value . ')' : NULL,
          ]);

          $form['recipes'][$recipe->id()][$ingredient->id()] = [
            '#type' => 'checkbox',
            '#default_value' => TRUE,
            '#title' => implode(" ", $label),
          ];
        }
      }
    } else {
      echo "You must make a list first!";
    }
    
    $form['actions']['save'] = [
      '#type' => 'submit',
      '#value' => $this->t('Make shopping list'),
      '#submit' => ['::submitSave'],
    ];

    return $form;
  }

  public function submitSave(array &$form, FormStateInterface $form_state)
  {
    // Remove any previous shopping list.
    $storage = $this->entity_type_manager->getStorage('recipes_shopping_list');
    $entities = $storage->loadByProperties([
      'uid' => $this->current_user->id(),
    ]);
    $shopping_list = reset($entities) ?: NULL;
    if ($shopping_list) {
      $shopping_list->delete();
    }

    // Create new shopping list.
    $shopping_list = ShoppingList::create([
      'name' => 'Shopping list for ' . $this->current_user->id(),
      'uid' => $this->current_user->id(),
    ]);
    $shopping_list->save();

    foreach($form_state->getValue('recipes') as $recipe_id => $ingredient_ids) {
      foreach($ingredient_ids as $ingredient_id => $checked) {
        if ($checked == 1) {
          $shopping_list_item = ShoppingListItem::create([
            'label' => 'Shopping list item',
            'recipes_shopping_list_id' => ['target_id' => $shopping_list->id()],
            'recipes_ingredient_id' => ['target_id' => $ingredient_id],
            'collected' => FALSE,
            'uid' => $this->current_user->id(),
          ]);
          $shopping_list_item->save();
        }
      }
    }

    

    $form_state->setRedirect('recipes.shopping_list');
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {}
}
