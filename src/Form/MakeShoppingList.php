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
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
        '#prefix' => '<div id="recipes-make-shopping-list">',
        '#suffix' => '</div>',
      ];

      foreach($recipe_list->get('recipes')->referencedEntities() as $delta => $recipe) {
        
        $form['recipes'][$recipe->id()] = [
          '#type' => 'fieldset',
          '#title' => $recipe->getTitle(),
          '#open' => TRUE
        ];

        foreach($recipe->get('field_recipes_ingredients')->referencedEntities() as $delta1 => $ingredient) {
          $ingredient_term = $ingredient->get('field_recipes_ingredient')->referencedEntities();

          $form['recipes'][$recipe->id()][$ingredient->id()] = [
            '#type' => 'checkbox',
            '#default_value' => TRUE,
            '#title' => 'checkbox',
            '#form_id' => $this->getFormId(),
            '#amount' => $ingredient->get('field_recipes_ingredient_amount')->value ?: NULL,
            '#ingredient' => reset($ingredient_term)->getName() ?: NULL,
            '#extra' => $ingredient->get('field_recipes_ingredient_extra')->value ?: NULL
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
      // Make sure the user has permission to delete this shopping list.
      if (!$shopping_list->access('delete', $this->current_user)) {
        $this->messenger()->addError('You do not have permission to delete this shopping list.');
        return;
      }
      $shopping_list->delete();
    }

    // Check permissions to create shopping lists.
    $list_access_control_handler = $this->entity_type_manager->getAccessControlHandler('recipes_shopping_list');
    if (!$list_access_control_handler->createAccess(NULL, $this->current_user)) {
      $this->messenger()->addError('You do not have permission to create a new shopping list.');
        return;
    }
    // Create new shopping list.
    $shopping_list = ShoppingList::create([
      'name' => 'Shopping list for ' . $this->current_user->id(),
      'uid' => $this->current_user->id(),
    ]);
    $shopping_list->save();

    // Check permissions to create ShoppingListItems.
    $list_item_access_control_handler = $this->entity_type_manager->getAccessControlHandler('recipes_shopping_list_item');
    if (!$list_item_access_control_handler->createAccess(NULL, $this->current_user)) {
      $this->messenger()->addError('You do not have permission to create a new shopping list item.');
        return;
    }
    // Create all the items in the list.
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
