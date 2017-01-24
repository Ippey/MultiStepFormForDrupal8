<?php
/**
 * @file
 * Contains Drupal\multi_step_form\Form\MultiStepFormOne
 */

namespace Drupal\multi_step_form\Form;


use Drupal\Core\Form\FormStateInterface;

class MultiStepFormOne extends MultiStepFormBase
{
  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'multi_step_form_one';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form         = parent::buildForm($form, $form_state);
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#default_value' => $this->store->get('name')
    ];

    $form['actions']['submit']['#value'] = $this->t('Next');

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $this->store->set('name', $form_state->getValue('name'));

    $form_state->setRedirect('multi_step_form.step_two');
  }
}
