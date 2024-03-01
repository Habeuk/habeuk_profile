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
    self::instaLLNewTheme($entityTheme->getHostname());
    return $entityTheme;
  }
  
  /**
   *
   * @param string $themename
   */
  protected static function instaLLNewTheme($themename) {
    /**
     *
     * @var \Drupal\Core\Extension\ThemeExtensionList $ExtLitThemes
     */
    $ExtLitThemes = \Drupal::service('extension.list.theme');
    $ExtLitThemes->reset();
    $listThemeVisible = $ExtLitThemes->getList();
    if (!empty($listThemeVisible[$themename])) {
      /**
       *
       * @var \Drupal\Core\Extension\ThemeInstaller $themeInstaller
       */
      $themeInstaller = \Drupal::service("theme_installer");
      $listThemesInstalled = $listThemesInstalled = \Drupal::config("core.extension")->get('theme');
      if (empty($listThemesInstalled[$themename])) {
        $theme_list = [
          $themename => $themename
        ];
        if ($themeInstaller->install($theme_list))
          \Drupal::messenger()->addStatus("Le theme '$themename' a été installé ");
        else
          \Drupal::messenger()->addError("Le theme '$themename' n'a pa pu etre installé ");
      }
      else {
        \Drupal::messenger()->addStatus("Le theme '$themename' est deja installé");
      }
    }
    else {
      \Drupal::messenger()->addStatus("Le theme '$themename' n'est pas vu par drupal ");
    }
  }
  
}

