<?php
/**
 * @file
 * Contains \Drupal\stickynav\Form\StickynavSettingsForm.
 */
namespace Drupal\stickynav\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Component\Utility\String;

/**
 * Build Sticky Navigation settings form.
 */
class StickynavSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'stickynav_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, array &$form_state) {
    $config = \Drupal::config('stickynav.settings');
    $themes = \Drupal::service('theme_handler')->listInfo();
    foreach ($themes as $name => $data) {
      // Only getting settings for enabled themes.
      if ($data->status == 1) {
        $form['stickynav-enabled-' . $name] = array(
          '#type' => 'checkbox',
          '#title' => String::checkPlain($data->info['name']),
          '#default_value' => $config->get('stickynav-enabled-' . $name, FALSE),
        );
        // Selector is only visible when you activate sticky nav for the theme.
        $form['stickynav-selector-' . $name] = array(
          '#type' => 'textfield',
          '#title' => t('Selector'),
          '#description' => t('Place your selector for your menu that will be sticky on your theme. Use jquery format.'),
          '#default_value' => $config->get('stickynav-selector-' . $name, ''),
          '#states' => array(
            'visible' => array(
              ':input[name="stickynav-enabled-' . $name . '"]' => array('checked' => TRUE),
            ),
            'invisible' => array(
              ':input[name="stickynav-enabled-' . $name . '"]' => array('checked' => FALSE),
            ),
          ),
        );
      }
    }
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, array &$form_state) {
    $config = \Drupal::config('stickynav.settings');
    foreach ($form_state['values'] as $key => $val) {
      if (strpos($key, 'stickynav-') === 0) {
        $config->set($key, $val);
      }
    }
    $config->save();
    parent::submitForm($form, $form_state);
  }

}
