<?php

namespace Drupal\habeuk_profile;

use Drupal\generate_style_theme\Entity\ConfigThemeEntity;

/**
 * Nous partons de la logique qu'un site == un profile d'installation.
 * Le profile d'installation standard evolue, on va recuperer certaines
 * informations et les ajouter comme les configurations.
 *
 * @author stephane
 *        
 */
class HabeukProfileApplyConfig {
  
  public static function ApplyConfigProfileStandard() {
    /**
     *
     * @var \Drupal\Core\ProxyClass\Config\ConfigInstaller $configInstaller
     */
    // Installation des configurations du profile standard.
    $configInstaller = \Drupal::service('config.installer');
    $configInstaller->installDefaultConfig('profile', 'standard');
    // Installation des configurations optionnel du profile standard.
    $path_to_module = \Drupal::service('extension.path.resolver')->getPath('profile', 'standard');
    $config_path = $path_to_module . '/config/optional';
    $config_source = new \Drupal\Core\Config\FileStorage($config_path);
    $configInstaller->installOptionalConfig($config_source);
  }
  
  public static function CreationDuTheme() {
    $values = [
      'run_npm' => 1,
      'id' => 1,
      'settheme_as_defaut' => true,
      'hostname' => 'habeuk_theme'
    ];
    $entityTheme = ConfigThemeEntity::create($values);
    $entityTheme->save();
    return $entityTheme->getHostname();
  }
  
}