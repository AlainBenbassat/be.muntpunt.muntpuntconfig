<?php

use CRM_Muntpuntconfig_ExtensionUtil as E;

class CRM_Muntpuntconfig_Form_Admin extends CRM_Core_Form {
  public function buildQuickForm() {
    $this->addFormElements();
    $this->addFormButtons();

    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  public function postProcess() {
    $formAction = $this->getSubmittedFormAction();

    try {
      if ($formAction == 'set_preferences') {
        CRM_Muntpuntconfig_Preferences::set();
      }
      elseif ($formAction == 'load_config_items') {
        CRM_Muntpuntconfig_ConfigItems::load();
      }

      CRM_Core_Session::setStatus('', 'OK', 'success');
    }
    catch (Exception $e) {
      CRM_Core_Session::setStatus($e->getMessage(), 'Error', 'error');
    }

    parent::postProcess();
  }

  private function addFormElements() {
    $formActions = $this->getFormActions();
    $this->addRadio('form_actions', 'Acties:', $formActions, [], '<br>', TRUE);
  }

  private function addFormButtons() {
    $this->addButtons([
      [
        'type' => 'submit',
        'name' =>'Muntpunt configuratie bijwerken',
        'isDefault' => TRUE,
      ],
    ]);
  }

  private function getFormActions() {
    $actions = [
      'set_preferences' => 'Stel voorkeuren in',
      'load_config_items' => 'Laad de config items',
    ];

    return $actions;
  }

  private function getSubmittedFormAction() {
    $values = $this->getSubmitValues();
    if (empty($values['form_actions'])) {
      throw new Exception("Geen geldige actie gevonden!");
    }

    return $values['form_actions'];
  }

  private function getRenderableElementNames() {
    $elementNames = [];
    foreach ($this->_elements as $element) {
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

}
