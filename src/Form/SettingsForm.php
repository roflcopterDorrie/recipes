<?php

declare(strict_types=1);

namespace Drupal\recipes\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Recipes settings for this site.
 */
final class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'recipes_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['recipes.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['prompt'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Prompt'),
      '#default_value' => $this->config('recipes.settings')->get('prompt'),
    ];

    $form['use_structured_json_ai_response'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Force AI to use a structured json response'),
      '#default_value' => $this->config('recipes.settings')->get('use_structured_json_ai_response'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // @todo Validate the form here.
    // Example:
    // @code
    //   if ($form_state->getValue('example') === 'wrong') {
    //     $form_state->setErrorByName(
    //       'message',
    //       $this->t('The value is not correct.'),
    //     );
    //   }
    // @endcode
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('recipes.settings')
      ->set('prompt', $form_state->getValue('prompt'))
      ->set('use_structured_json_ai_response', $form_state->getValue('use_structured_json_ai_response'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
