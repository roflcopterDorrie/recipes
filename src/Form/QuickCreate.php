<?php

namespace Drupal\recipes\Form;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\recipes\Services\RecipesDataExtractor;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\taxonomy\Entity\Term;
use Doctrine\Inflector\InflectorFactory;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;

/**
 * Implements an example form.
 */
class QuickCreate extends FormBase
{
  use DependencySerializationTrait;

  public function __construct(
    protected RecipesDataExtractor $recipes_data_extractor,
    protected ConfigFactoryInterface $config_factory,
    protected EntityTypeManagerInterface $entity_type_manager,
  ) {
    $this->recipes_data_extractor = $recipes_data_extractor;
    $this->config_factory = $config_factory;
    $this->entity_type_manager = $entity_type_manager;
  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('recipes.data_extractor'),
      $container->get('config.factory'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'quick_create_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    // Determine current step (default to 1)
    $step = $form_state->get('step') ?: 1;

    $form['#prefix'] = '<div id="recipe-form-wrapper">';
    $form['#suffix'] = '</div>';

    if ($step === 1) {

      $form['url'] = [
        '#type' => 'url',
        '#title' => $this->t('Recipe URL'),
        '#required' => TRUE,
      ];

      $form['actions']['extract'] = [
        '#type' => 'submit',
        '#value' => $this->t('Get Recipe'),
        '#submit' => ['::submitExtract'],
      ];
    } else if ($step === 2) {

      $extracted_recipe = $form_state->get('extracted_recipe');

      // $form['preview_recipe_json'] = [
      //   '#markup' => json_encode($extracted_recipe)
      // ];

      // Preview the recipe to the user before it is saved.
      $form['preview_recipe'] = [
        '#theme' => 'recipe_preview',
        '#recipe' => $extracted_recipe,
      ];
      
      // I want to build up a form that the user can edit here in case the AI doesn't quite work.

      $form['edited_recipe'] = [
        '#type' => 'details',
        '#title' => $this->t('Adjust recipe'),
        '#open' => FALSE, // Collapsed by default
      ];


      $form['edited_recipe']['edited_recipe_title'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Title'),
        '#default_value' => $extracted_recipe->title,
      ];

      $form['edited_recipe']['edited_ingredients_list'] = [
        '#type' => 'details',
        '#title' => $this->t('Ingredients'),
        '#tree' => TRUE,
      ];

      foreach ($extracted_recipe->ingredients as $delta => $data) {
        $form['edited_recipe']['edited_ingredients_list'][$delta] = [
          '#type' => 'container',
          '#attributes' => ['class' => ['container-inline']], // Keeps fields on one line
          
          'amount' => [
            '#type' => 'textfield',
            '#title' => $this->t('Amount'),
            '#default_value' => $data->amount ?? '',
            '#size' => 7,
          ],
          'name' => [
            '#type' => 'textfield',
            '#title' => $this->t('Ingredient'),
            '#default_value' => $data->ingredient ?? '',
            '#size' => 7,
          ],
          'extra' => [
            '#type' => 'textfield',
            '#title' => $this->t('Extra info'),
            '#default_value' => $data->extra ?? '',
            '#size' => 7,
          ],
          'category' => [
            '#type' => 'textfield',
            '#title' => $this->t('Category'),
            '#default_value' => $data->category ?? '',
            '#disabled' => true,
            '#size' => 7,
          ],
        ];
      }

      $form['edited_recipe']['edited_steps_list'] = [
        '#type' => 'details',
        '#title' => $this->t('Steps'),
        '#tree' => TRUE,
      ];

      foreach ($extracted_recipe->steps as $delta => $data) {
        $form['edited_recipe']['edited_steps_list'][$delta] = [
          '#type' => 'container',
          'step' => [
            '#type' => 'textarea',
            '#title' => $this->t('Step'),
            '#default_value' => $data ?? '',
          ],
        ];
      }

      $form['actions']['save'] = [
        '#type' => 'submit',
        '#value' => $this->t('Save Recipe'),
        '#submit' => ['::submitSave'],
      ];

      $form['actions']['back'] = [
        '#type' => 'submit',
        '#value' => $this->t('Back'),
        '#submit' => ['::submitBack'],
        '#limit_validation_errors' => [],
      ];
    }

    return $form;
  }


  public function submitExtract(array &$form, FormStateInterface $form_state)
  {
    if ($extracted_recipe = $this->recipes_data_extractor->extractRecipeFromUrl($form_state->getValue('url')) !== FALSE) {
      $form_state->set('extracted_recipe', $extracted_recipe);
      $form_state->set('step', 2);
    }

    return $form_state->setRebuild();
  }


  public function submitSave(array &$form, FormStateInterface $form_state)
  {

    // = $form_state->get('extracted_recipe');

    // Generate and save the Recipe.
    $recipe_node = Node::create([
      'type' => 'recipes_recipe',
      'title' => $form_state->getValue('edited_recipe_title'),
    ]);

    // Ingredients.
    $ingredient_references = [];
    foreach ($form_state->getValue('edited_ingredients_list') as $ingredient) {

      // Find this ingredient in our taxonomy, or if it doesn't exist, create it.
      $ingredient_term_id = null;

      // We only want to save the singular version of the ingredient name to help 
      // control the data. We will display the plural if need when viewing the ingredient.
      $inflector = InflectorFactory::create()->build();
      $ingredient_singular = strtolower($inflector->singularize($ingredient['name']));

      $ingredient_terms = $this->entity_type_manager->getStorage('taxonomy_term')->loadByProperties([
        'name' => $ingredient_singular,
        'vid' => 'recipes_ingredient',
      ]);
      if (!empty($ingredient_terms)) {
        $ingredient_term = reset($ingredient_terms);
        $ingredient_term_id = $ingredient_term->id();
      } else {
        $ingredient_term = Term::create([
          'vid' => 'recipes_ingredient',
          'name' => $ingredient_singular,
        ]);
        $ingredient_term->save();
        $ingredient_term_id = $ingredient_term->id();
      }

      // Convert from imperial to metric for measurements.
      $ingredient_amount = $ingredient['amount'];
      if (isset($ingredient['amount']) && $ingredient_amount !== null) {
        $pattern = '/(\d+(?:\/\d+)?|[\d\.]+)\s*(lb|lbs|pound|pounds|oz|ounce|ounces)\b/i';
        if (preg_match($pattern, $ingredient_amount, $match)) {
          $quantity_text = $match[1];
          $unit = strtolower($match[2]);

          $quantity = new Mass($quantity_text, $unit);
          if ($quantity->toUnit('kg') < 1) {
            $ingredient_amount = number_format($quantity->toUnit('g'), 0) . " grams";
          } else {
            $ingredient_amount = number_format($quantity->toUnit('kg'), 2) . " kgs";
          }
        }
      }

      $ingredient_node = Node::create([
        'type' => 'recipes_ingredient',
        'title' => $ingredient_singular,
        'field_recipes_ingredient' => ['target_id' => $ingredient_term_id],
        'field_recipes_ingredient_amount' => $ingredient_amount,
        'field_recipes_ingredient_extra' => $ingredient['extra'] ?? null,
      ]);

      $ingredient_aisles = $this->entity_type_manager->getStorage('taxonomy_term')->loadByProperties([
        'name' => $ingredient['category'],
        'vid' => 'recipes_ingredient_aisle',
      ]);
      if (!empty($ingredient_aisles)) {
        $ingredient_aisle = reset($ingredient_aisles);
        $ingredient_node->set('field_recipes_ingredient_aisle', $ingredient_aisle->id());
      }

      $ingredient_node->save();
      $ingredient_references[] = ['target_id' => $ingredient_node->id()];
    }
    $recipe_node->set('field_recipes_ingredients', $ingredient_references);

    // Steps.
    $step_data = [];
    $steps =  $form_state->getValue('edited_steps_list');
    foreach($steps as $step){
      $step_data[] = $step['step'];
    }
    $recipe_node->set('field_recipes_steps', $step_data);

    $recipe_node->save();

    $form_state->setRedirect('entity.node.canonical', ['node' => $recipe_node->id()]);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {}
}
