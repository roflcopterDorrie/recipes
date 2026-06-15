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
use Drupal\recipes\ShoppingListAccessControlHandler;

/**
 * Defines the recipe list entity class.
 */
#[ContentEntityType(
  id: 'recipes_shopping_list',
  label: new TranslatableMarkup('Shopping List'),
  label_collection: new TranslatableMarkup('Shopping Lists'),
  label_singular: new TranslatableMarkup('shopping list'),
  label_plural: new TranslatableMarkup('shopping lists'),
  entity_keys: [
    'id' => 'id',
    'label' => 'name',
    'owner' => 'uid',
    'uuid' => 'uuid',
  ],
  handlers: [
    'views_data' => EntityViewsData::class,
    'access' => ShoppingListAccessControlHandler::class,
  ],
  base_table: 'recipes_shopping_list',
  label_count: [
    'singular' => '@count shopping lists',
    'plural' => '@count shopping lists',
  ],
)]
class ShoppingList extends ContentEntityBase {

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
