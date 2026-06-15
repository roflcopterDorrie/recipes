<?php

namespace Drupal\recipes\Form;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\MessageCommand;
use Drupal\recipes\Services\ShoppingList;
use Drupal\recipes\Services\Ingredient;

/**
 * Implements an form to generate a Shopping List.
 */
class ShoppingListForm extends FormBase
{
  use DependencySerializationTrait;

  public function __construct(
    protected EntityTypeManagerInterface $entity_type_manager,
    protected AccountProxyInterface $current_user,
    protected Ingredient $ingredient,
    protected ShoppingList $shopping_list,
  ) {}

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('current_user'),
      $container->get('recipes.ingredient'),
      $container->get('recipes.shopping_list'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'recipes_shopping_list_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['#prefix'] = '<div id="recipe-form-wrapper">';
    $form['#suffix'] = '</div>';

    // Load up the user's list.
    $shopping_list = $this->shopping_list->load($this->current_user);
    if ($shopping_list) {
      $form['shopping_list_items'] = [
        '#type' => 'container',
        '#tree' => TRUE,
        '#prefix' => '<div id="recipes-shopping-list">',
        '#suffix' => '</div>',
      ];

      $grouped = [];

      foreach ($shopping_list->ingredients as $shopping_list_item) {
        $ingredient = $shopping_list_item->get('recipes_ingredient_id')->referencedEntities();
        $ingredient = reset($ingredient);
        $ingredient_term = $ingredient->get('field_recipes_ingredient')->referencedEntities();

        $aisle = $ingredient->get('field_recipes_ingredient_aisle')->referencedEntities();

        $grouped[reset($aisle)->getName()][$shopping_list_item->id()] = [
          '#type' => 'checkbox',
          '#title' => 'checkbox',
          '#default_value' => $shopping_list_item->get("collected")->value,
          '#form_id' => $this->getFormId(),
          '#amount' => $ingredient->get('field_recipes_ingredient_amount')->value ?: NULL,
          '#ingredient' => reset($ingredient_term)->getName() ?: NULL,
          '#extra' => $ingredient->get('field_recipes_ingredient_extra')->value ?: NULL,
          '#id' => $shopping_list_item->id(),
          '#ajax' => [
            'callback' => '::updateShoppingListItem',
            'event' => 'change',
            'progress' => [
              'type' => 'none',
              'message' => NULL,
            ],
          ]
        ];
      }

      foreach($grouped as $aisle => $group) {
        $form['shopping_list_items'][$aisle] = [
          '#type' => 'fieldset',
          '#title' => $aisle,
          '#open' => TRUE,
        ];
        foreach($group as $id => $data) {
          $form['shopping_list_items'][$aisle][$id] = $data;
        }
      }
    }

    // Create an ad hoc list that the user can add ingredients to.
    $form['ad_hoc_ingredients'] = [
      '#type' => 'textarea',
      '#title' => 'Extra'
    ];

    $form['actions']['save'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#submit' => ['::submitSave'],
    ];

    return $form;
  }

  public function updateShoppingListItem(array &$form, FormStateInterface $form_state)
  {
    $response = new AjaxResponse();

    $triggering_element = $form_state->getTriggeringElement();
    $shopping_list_item = $this->entity_type_manager->getStorage('recipes_shopping_list_item')->load($triggering_element['#id']);
    if (!$shopping_list_item->access('update', $this->current_user)) {
      $response->addCommand(new MessageCommand(
        "You do not have permissions to update this item",
        NULL,
        ['type' => 'error']
      ));
      return $response;
    }

    if ($shopping_list_item) {
      $shopping_list_item->set('collected', $triggering_element['#value']);
      $shopping_list_item->save();
    } else {
      $response->addCommand(new MessageCommand(
        "Could not find your shopping list.",
        NULL,
        ['type' => 'error']
      ));
    }
    return $response;
  }

  public function submitSave(array &$form, FormStateInterface $form_state)
  {
    foreach($form_state->getValue('shopping_list_items') as $aisle) {
      foreach($aisle as $shopping_list_item_id => $checked) {
        $shopping_list_item = $this->entity_type_manager->getStorage('recipes_shopping_list_item')->load($shopping_list_item_id);
        if (!$shopping_list_item->access('update', $this->current_user)) {
          $this->messenger()->addError('You do not have permission to update this item.');
          return;
        }
        $shopping_list_item->set('collected', $checked);
        $shopping_list_item->save();
      }
    }

    // Handle extra ingredients.
    $extra = $form_state->getValue('ad_hoc_ingredients');
    if (!empty($extra)) {
      $ingredient_ids = [];
      // Split up ingredients per line.
      $ingredients = explode("\n", $extra);
      foreach ($ingredients as $ingredient) {
        $ingredient_node = $this->ingredient->create(['name' => $ingredient, 'category' => "Custom"]);
        $ingredient_ids[] = $ingredient_node->id();
      }

      $shopping_list = $this->shopping_list->load($this->current_user);
      $shopping_list->addIngredients($ingredient_ids);
    }

  }

  public function submitForm(array &$form, FormStateInterface $form_state) {}
}
