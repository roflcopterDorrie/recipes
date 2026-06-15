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

use Drupal\media\Entity\Media;
use Drupal\file\FileRepository;
use Drupal\Core\File\FileSystem;
use Drupal\Core\File\FileExists;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\recipes\Services\Ingredient;

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
    protected FileSystem $file_system,
    protected FileRepository $file_repository,
    protected AccountProxyInterface $current_user,
    protected Ingredient $ingredient
  ) {}

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('recipes.data_extractor'),
      $container->get('config.factory'),
      $container->get('entity_type.manager'),
      $container->get('file_system'),
      $container->get('file.repository'),
      $container->get('current_user'),
      $container->get('recipes.ingredient'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'recipes_quick_create_form';
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
      ];

      $form['or_markup'] = [
        '#type' => 'markup',
        '#markup' => " <h2>OR</h2>"
      ];

      $form['data'] = [
        '#type' => 'textarea',
        '#title' => $this->t('Recipe information'),
      ];

      $form['gen_ai_image'] = [
        '#type' => 'url',
        '#title' => $this->t("Image URL"),
        '#default_value' => TRUE,
      ];

      $form['actions']['extract'] = [
        '#type' => 'submit',
        '#value' => $this->t('Get Recipe'),
        '#submit' => ['::submitExtract'],
      ];
    } else if ($step === 2) {

      $extracted_recipe = $form_state->get('extracted_recipe');

      // Preview the recipe to the user before it is saved.
      $form['preview_recipe'] = [
        '#theme' => 'recipe_preview',
        '#recipe' => $extracted_recipe,
      ];

      // I want to build up a form that the user can edit here in case the AI doesn't quite work.

      $form['edited_recipe'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Adjust recipe'),
        '#open' => FALSE, // Collapsed by default
      ];


      $form['edited_recipe']['edited_recipe_title'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Title'),
        '#default_value' => $extracted_recipe->title,
        '#required' => TRUE,
      ];

      $form['edited_recipe']['edited_recipe_image'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Image URL'),
        '#default_value' => $extracted_recipe->image_url,
      ];

      $form['edited_recipe']['edited_ingredients_list'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Ingredients'),
        '#tree' => TRUE,
        '#open' => TRUE,
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
            '#title' => $this->t('Name'),
            '#default_value' => $data->name ?? '',
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
        '#type' => 'fieldset',
        '#title' => $this->t('Steps'),
        '#tree' => TRUE,
        '#open' => TRUE,
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

      $form['debug'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Debug'),
        '#open' => FALSE, // Collapsed by default
      ];

      $form['debug']['json'] = [
        '#type' => 'item',
        '#title' => $this->t('json'),
        '#markup' => json_encode($extracted_recipe)
      ];
      $form['debug']['prompt'] = [
        '#type' => 'item',
        '#title' => $this->t('Prompt'),
        '#markup' => $form_state->get('debug_prompt')
      ];

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

  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    $step = $form_state->get('step') ?: 1;
    if ($step == 1) {
      // Extract the submitted value using the field's machine name
      $url = $form_state->getValue('url');

      // If $url is empty, then we check if text has been filled out.
      if (empty($url)) {
        $data = $form_state->getValue('data');

        if (empty($data)) {
          $form_state->setErrorByName('url', $this->t('You must fill in either URL or Recipe information.'));
        }
      }
    }
  }


  public function submitExtract(array &$form, FormStateInterface $form_state)
  {

    $url = $form_state->getValue('url');
    $data = $form_state->getValue('data');
    $extracted_recipe = NULL;

    if (!empty($url)) {
      $extracted_recipe = $this->recipes_data_extractor->extractRecipeFromUrl($url);
      // DEBUG.
      $debug_html = $this->recipes_data_extractor->getDataFromUrl($url);
      $debug_recipe_text = $this->recipes_data_extractor->getBodyText($debug_html);
    } elseif (!empty($data)) {
      $extracted_recipe = $this->recipes_data_extractor->extractRecipeFromText($data);
      // DEBUG.
      $debug_recipe_text = $data;
    }

    if (isset($extracted_recipe) && $extracted_recipe !== FALSE) {
      $form_state->set('extracted_recipe', $extracted_recipe);
      $form_state->set('step', 2);

      // DEBUG.
      $prompt = $this->recipes_data_extractor->generatePrompt($debug_recipe_text);

      $form_state->set('debug_prompt', $prompt);
    }

    return $form_state->setRebuild();
  }


  public function submitSave(array &$form, FormStateInterface $form_state)
  {

    // Create Recipe.
    $recipe_node = Node::create([
      'type' => 'recipes_recipe',
      'title' => $form_state->getValue('edited_recipe_title'),
    ]);

    // INGREDIENTS.
    $ingredient_references = [];
    foreach ($form_state->getValue('edited_ingredients_list') as $ingredient) {
      $ingredient_node = $this->ingredient->create($ingredient);
      $ingredient_references[] = ['target_id' => $ingredient_node->id()];
    }
    $recipe_node->set('field_recipes_ingredients', $ingredient_references);

    // STEPS.
    $step_data = [];
    $steps =  $form_state->getValue('edited_steps_list');
    foreach ($steps as $step) {
      $step_data[] = $step['step'];
    }
    $recipe_node->set('field_recipes_steps', $step_data);

    // IMAGE.
    $image_url = $form_state->getValue('edited_recipe_image');
    if (!empty($image_url)) {
      $image_data = $this->recipes_data_extractor->getDataFromUrl($image_url);

      $filename = basename(parse_url($image_url, PHP_URL_PATH));

      $directory = 'public://recipes';
      $this->file_system->prepareDirectory($directory, FileSystem::CREATE_DIRECTORY);

      /** @var \Drupal\file\FileInterface $file */
      $file = $this->file_repository->writeData($image_data, "$directory/$filename", FileExists::Replace);

      if (!$file) {
        return null;
      }

      $media_image = Media::create([
        'bundle' => 'recipes_image',
        'uid' => $this->current_user->id(),
        'name' => $filename,
        'status' => 1,
        'field_recipes_image' => [
          'target_id' => $file->id(),
          'alt' => 'Scraped Recipe Image',
        ],
      ]);

      $media_image->save();
      $recipe_node->set('field_recipes_image', $media_image);
    }

    // Save the recipe.
    $recipe_node->save();

    // Redirect to the newly created recipe.
    $form_state->setRedirect('entity.node.canonical', ['node' => $recipe_node->id()]);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {}
}
