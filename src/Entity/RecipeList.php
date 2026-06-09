<?php

declare(strict_types=1);

namespace Drupal\recipes\Entity;

use Drupal\Core\Entity\Attribute\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\user\EntityOwnerTrait;
use Drupal\views\EntityViewsData;

/**
 * Defines the recipe list entity class.
 */
#[ContentEntityType(
  id: 'recipes_recipe_list',
  label: new TranslatableMarkup('Recipe List'),
  label_collection: new TranslatableMarkup('Recipe Lists'),
  label_singular: new TranslatableMarkup('recipe list'),
  label_plural: new TranslatableMarkup('recipe lists'),
  entity_keys: [
    'id' => 'id',
    'label' => 'name',
    'recipes' => 'recipes',
    'owner' => 'uid',
    'uuid' => 'uuid',
  ],
  handlers: [
    'views_data' => EntityViewsData::class,
  ],
  admin_permission: 'administer recipes_recipe_list',
  base_table: 'recipes_recipe_list',
  label_count: [
    'singular' => '@count recipe lists',
    'plural' => '@count recipe lists',
  ],
)]
class RecipeList extends ContentEntityBase {

  use EntityChangedTrait;
  use EntityOwnerTrait;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage): void {
    parent::preSave($storage);
    if (!$this->getOwnerId()) {
      // If no owner has been set explicitly, make the anonymous user the owner.
      $this->setOwnerId(0);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    
    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setRequired(FALSE);


    $fields['recipes'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Recipes'))
      ->setDescription(t('Recipes in this meal plan.'))
      ->setSetting('target_type', 'node')
      ->setSetting('handler', 'default')
      ->setSetting('handler_settings', [
        'target_bundles' => ['recipes_recipe'],
      ])
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 10,
      ])
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Author'))
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback(self::class . '::getDefaultEntityOwner')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
