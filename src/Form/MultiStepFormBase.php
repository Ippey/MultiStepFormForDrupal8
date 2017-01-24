<?php
/**
 * @file
 * Contains Drupal\multi_step_form\Form\MultiStepFormBase
 */

namespace Drupal\multi_step_form\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Drupal\user\PrivateTempStore;
use Drupal\user\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class MultiStepFormBase extends FormBase
{
  /** @var  PrivateTempStoreFactory */
  protected $tempStoreFactory;

  /** @var  SessionManagerInterface */
  protected $sessionManager;

  /** @var  AccountInterface */
  protected $currentUser;

  /** @var  PrivateTempStore */
  protected $store;

  /**
   * Constructs a MultistepFormBase.
   *
   * @param PrivateTempStoreFactory $temp_store_factory
   * @param SessionManagerInterface $session_manager
   * @param AccountInterface $current_user
   */
  public function __construct(
    PrivateTempStoreFactory $temp_store_factory,
    SessionManagerInterface $session_manager,
    AccountInterface $current_user
  ) {
    $this->tempStoreFactory = $temp_store_factory;
    $this->sessionManager   = $session_manager;
    $this->currentUser      = $current_user;

    $this->store = $this->tempStoreFactory->get('multistep_data');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('user.private_tempstore'),
      $container->get('session_manager'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    // Start a manual session for anonymous users.
    if ($this->currentUser->isAnonymous() && !isset($_SESSION['multistep_form_holds_session'])) {
      $_SESSION['multistep_form_holds_session'] = true;
      $this->sessionManager->start();
    }

    $form                      = [];
    $form['actions']['#type']  = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#button_type' => 'primary',
      '#weight' => 10,
    ];

    return $form;
  }

  /**
   * Saves the data from the multistep form.
   */
  protected function saveData()
  {
    // TODO: データ保存

    // 一次データ削除
    $this->deleteStore();

    drupal_set_message($this->t('The form has been saved.'));
  }

  /**
   * Helper method that removes all the keys from the store collection used for
   * the multistep form.
   */
  protected function deleteStore()
  {
    $keys = ['name', 'email'];
    foreach ($keys as $key) {
      $this->store->delete($key);
    }
  }
}
