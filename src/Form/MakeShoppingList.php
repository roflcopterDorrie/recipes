<?php

namespace Drupal\recipes\Form;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\recipes\Services\ShoppingList as ServicesShoppingList;

/**
 * Implements an form to generate a Shopping List.
 */
class MakeShoppingList extends FormBase
{
  use DependencySerializationTrait;

  public function __construct(
    protected EntityTypeManagerInterface $entity_type_manager,
    protected AccountProxyInterface $current_user,
    protected ServicesShoppingList $shopping_list,
  ) {}

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('current_user'),
      $container->get('recipes.shopping_list'),
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
    $shopping_list = $this->shopping_list->load($this->current_user);

    $ingredient_ids = [];
    foreach($form_state->getValue('recipes') as $recipe_ingredient_ids) {
      
      $ingredient_ids += array_filter($recipe_ingredient_ids, function ($value) {
        return $value === 1;
      });
      
    }

    $shopping_list->clear();
    $shopping_list->addIngredients(array_keys($ingredient_ids));

    $form_state->setRedirect('recipes.shopping_list');
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {}
}
