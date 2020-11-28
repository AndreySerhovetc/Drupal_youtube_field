<?php

/**
 * @file
 * Contains \Drupal\youtube\Plugin\Block\Youtube_Display.
 */

namespace Drupal\youtube\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;




/**
 * @Block(
 *   id = "display_my_video",
 *   admin_label = @Translation("Output my video"),
 * )
 */


class YoutubeDisplay extends BlockBase
{
  /**
   * Добавляем наши конфиги по умолчанию.
   *
   * {@inheritdoc}
   */

  public function blockForm($form, FormStateInterface $formState)
  {
    //получаем ориганальною форму для блока
    $form = parent::blockForm($form, $formState);
    // Получаем конфиги для данного блока.
    $config = $this->getConfiguration();

    //Добавляем поле для ввода сообщения

    $form['url'] = array(
      '#type' => 'textfield',
      '#title' => t('Enter URL'),
      '#default_value' => $config['url'],
    );

    $form['weight'] = array(
      '#type' => 'number',
      '#min' => 1,
      '#title' => t('Enter weight field'),
      '#default_value' => $config['weight'],
    );

    $form['height'] = array(
      '#type' => 'number',
      '#min' => 1,
      '#title' => t('Enter height field'),
      '#default_value' => $config['height'],
    );
    //Добавляем поле для ввода сообщения
    return $form;
  }

  /**
   * Валидируем значения на наши условия.
   * Количество должно быть >= 1,
   * Сообщение должно иметь минимум 5 символов.
   *
   * {@inheritdoc}
   */

  public function blockValidate($form, FormStateInterface $form_state)
  {
    $url = $form_state->getValue('url');
    $weight = $form_state->getValue('weight');
    $height = $form_state->getValue('height');
    if (strlen($url) == 0) {
      $form_state->setValueForElement($form, '');
      return;
    }
    if (!is_numeric($weight) || $weight < 1) {
      $form_state->setErrorByName('count', t('Needs to be an interger and more or equal 1.'));
    }
    if (!is_numeric($height) || $height < 1) {
      $form_state->setErrorByName('count', t('Needs to be an interger and more or equal 1.'));
    }
    if (!preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches)) {
      $form_state->setError($form, t("Youtube video URL is not correct."));
    }
  }


  public function blockSubmit($form, FormStateInterface $form_state)
  {
    $this->configuration['weight'] = $form_state->getValue('weight');
    $this->configuration['height'] = $form_state->getValue('height');
    $this->configuration['url'] = $form_state->getValue('url');
  }

  /**
   * Генерируем и выводим содержимое блока.
   *
   * {@inheritdoc}
   */

  public function build()
  {
    $config = $this->getConfiguration();
    $url = $config['url'];
    $weight = $config['weight'];
    $height = $config['height'];
    preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);

    $video_id = $matches[0];
    $iframe = [];
    $iframe = array(
      '#type' => 'inline_template',
      '#template' => '<iframe width="'.$weight.'" height="'.$height.'" src="https://www.youtube.com/embed/'.$video_id.'"
       frameborder="0" allowfullscreen></iframe>',

    );
    return $iframe;
  }
}









