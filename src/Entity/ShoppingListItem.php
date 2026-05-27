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
  id: 'recipes_shopping_list_item',
  label: new TranslatableMarkup('Shopping List Item'),
  label_collection: new TranslatableMarkup('Shopping List Items'),
  label_singular: new TranslatableMarkup('shopping list item'),
  label_plural: new TranslatableMarkup('shopping list items'),
  entity_keys: [
    'id' => 'id',
    'label' => 'name',
    'owner' => 'uid',
    'uuid' => 'uuid',
  ],
  handlers: [
    'views_data' => EntityViewsData::class,
  ],
  admin_permission: 'administer recipes_recipe_list',
  base_table: 'recipes_shopping_list_item',
  label_count: [
    'singular' => '@count shopping list item',
    'plural' => '@count shopping list items',
  ],
)]
class ShoppingListItem extends ContentEntityBase
{

  use EntityChangedTrait;
  use EntityOwnerTrait;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage): void
  {
    parent::preSave($storage);
    if (!$this->getOwnerId()) {
      // If no owner has been set explicitly, make the anonymous user the owner.
      $this->setOwnerId(0);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array
  {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setRequired(FALSE);

    $fields['recipes_shopping_list_id'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', 'recipes_shopping_list');

    $fields['recipes_ingredient_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Ingredients'))
      ->setDescription(t('Ingredient'))
      ->setSetting('target_type', 'node')
      ->setSetting('handler_settings', [
        'target_bundles' => ['recipes_ingredients'],
      ])
      ->setCardinality(1);

    $fields['collected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Collected'))
      ->setDefaultValue(FALSE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Author'))
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback(self::class . '::getDefaultEntityOwner');

    return $fields;
  }
}
