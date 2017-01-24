<?php
/**
 * @file
 * Contains Drupal\multi_step_form\Form\MultiStepFormTwo
 */

namespace Drupal\multi_step_form\Form;


use Drupal\Core\Form\FormStateInterface;

class MultiStepFormTwo extends MultiStepFormBase
{
  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'multi_step_form_two';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form = parent::buildForm($form, $form_state);
    $form['email'] = [
      '#type' => 'email',
      '#title' => t('Email'),
      '#default_value' => $this->store->get('email')
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $this->store->set('email', $form_state->getValue('email'));

    // データ保存
    parent:$this->saveData();
    $form_state->setRedirect('multi_step_form.step_one');
  }
}
