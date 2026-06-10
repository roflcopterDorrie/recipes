<?php

namespace Drupal\recipes;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Access controller for the Recipe List entity.
 */
class RecipeListAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   * Handles Edit (update) and Delete operations on existing entities.
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    // 1. If they have the master admin permission, let them through instantly
    if ($account->hasPermission('administer recipe lists')) {
      return AccessResult::allowed()->cachePerPermissions();
    }

    // 2. Check granular operations for regular creators
    switch ($operation) {
      case 'view':
      case 'update':
      case 'delete':
        $is_owner = ($account->id() === $entity->getOwnerId());
        return AccessResult::allowedIf($account->hasPermission('use own recipe lists') && $is_owner)
          ->cachePerPermissions()
          ->cachePerUser()
          ->addCacheableDependency($entity);
    }

    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   * Handles the "Create" button/link checks.
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIf(
      $account->hasPermission('administer recipe lists') || 
      $account->hasPermission('use own recipe lists')
    )->cachePerPermissions();
  }

}