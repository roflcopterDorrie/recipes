<?php

namespace Drupal\recipes\Theme;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Theme\ThemeNegotiatorInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

class FormThemeNegotiator implements ThemeNegotiatorInterface
{

  protected ConfigFactoryInterface $config_factory;

  /**
   * Inject the configuration factory service.
   */
  public function __construct(ConfigFactoryInterface $config_factory)
  {
    $this->config_factory = $config_factory;
  }

  /**
   * Determine if this negotiator should apply to the current route.
   */
  public function applies(RouteMatchInterface $route_match)
  {
    // Check for the exact route machine name of your custom form page
    if ($route_match->getRouteName() === 'recipes.quick_create') {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Use the admin theme for these forms.
   */
  public function determineActiveTheme(RouteMatchInterface $route_match)
  {
    // Read the system theme configuration
    $theme_config = $this->config_factory->get('system.theme');

    // Get the machine name of the assigned admin theme
    return $theme_config->get('admin');
  }
}
