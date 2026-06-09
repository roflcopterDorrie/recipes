<?php

namespace Drupal\recipes\Form;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;

/**
 * Implements an form to generate a Shopping List.
 */
class ShoppingListForm extends FormBase
{
  use DependencySerializationTrait;

  public function __construct(
    protected EntityTypeManagerInterface $entity_type_manager,
    protected AccountProxyInterface $current_user
  ) {
    $this->entity_type_manager = $entity_type_manager;
    $this->current_user = $current_user;
  }

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
    return 'recipes_shopping_list_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['#prefix'] = '<div id="recipe-form-wrapper">';
    $form['#suffix'] = '</div>';

    // Load up the user's list.
    $storage = $this->entity_type_manager->getStorage('recipes_shopping_list');
    $entities = $storage->loadByProperties([
      'uid' => $this->current_user->id(),
    ]);
    $shopping_list = reset($entities) ?: NULL;
    if ($shopping_list) {

      // Load up all the shopping list items.
      $storage = $this->entity_type_manager->getStorage('recipes_shopping_list_item');
      $shopping_list_items = $storage->loadByProperties([
        'recipes_shopping_list_id' => $shopping_list->id(),
      ]);

      $form['shopping_list_items'] = [
        '#type' => 'container',
        '#tree' => TRUE,
        '#prefix' => '<div class="recipes-shopping-list">',
        '#suffix' => '</div>',
      ];

      $grouped = [];

      foreach ($shopping_list_items as $shopping_list_item) {
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
          '#extra' => $ingredient->get('field_recipes_ingredient_extra')->value ?: NULL
        ];
      }

      foreach($grouped as $aisle => $group) {
        $form['shopping_list_items'][$aisle] = [
          '#type' => 'details',
          '#title' => $aisle,
          '#open' => TRUE,
        ];
        foreach($group as $id => $data) {
          $form['shopping_list_items'][$aisle][$id] = $data;
        }
      }
    }

    $form['actions']['save'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#submit' => ['::submitSave'],
    ];

    return $form;
  }

  public function submitSave(array &$form, FormStateInterface $form_state)
  {
    foreach($form_state->getValue('shopping_list_items') as $aisle) {
      foreach($aisle as $shopping_list_item_id => $checked) {
        $shopping_list_item = $this->entity_type_manager->getStorage('recipes_shopping_list_item')->load($shopping_list_item_id);
        $shopping_list_item->set('collected', $checked);
        $shopping_list_item->save();
      }
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {}
}
