<?php

use CRM_Muntpuntconfig_ExtensionUtil as E;

class CRM_Muntpuntconfig_Form_Admin extends CRM_Core_Form {
  public function buildQuickForm() {
    $this->addFormButtons();

    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  public function postProcess() {
    try {
      $config = new CRM_Muntpuntconfig_Config();
      $config->checkConfig();
      CRM_Core_Session::setStatus('', 'OK', 'success');
    }
    catch (Exception $e) {
      CRM_Core_Session::setStatus($e->getMessage(), 'Error', 'error');
    }

    parent::postProcess();
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
