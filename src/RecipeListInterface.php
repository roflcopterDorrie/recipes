<?php

declare(strict_types=1);

namespace Drupal\recipes;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a recipe list entity type.
 */
interface RecipeListInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
