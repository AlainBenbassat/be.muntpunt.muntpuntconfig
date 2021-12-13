<?php

class CRM_Muntpuntconfig_Config {
  const ICONTACT_ID_TYPE = 'icontact_id';

  public function checkConfig() {
    $this->setDateFormat();
    $this->setMoneyFormat();
    $this->addContactIdentity_oldCiviCRMId();
    $this->loadConfigItems();
  }

  private function loadConfigItems() {
    $resourcePath = Civi::resources()->getPath('be.muntpunt.muntpuntconfig') . '/resources';

    civicrm_api3('Civiconfig', 'load_json', [
      'path' => $resourcePath
    ]);
  }

  private function addContactIdentity_oldCiviCRMId() {
    CRM_Identitytracker_Configuration::add_identity_type(self::ICONTACT_ID_TYPE, 'Oude CiviCRM ID (iContact)');
  }

  private function setDateFormat() {
    civicrm_api4('Setting', 'set', [
      'values' => [
        'dateformatDatetime' => '%e %B %Y %H:%M',
        'dateformatFull' => '%e %B %Y',
        'dateformatTime' => '%H:%M',
        'dateformatFinancialBatch' => '%d/%m/%Y',
        'dateformatshortdate' => '%d/%m/%Y',
        'dateInputFormat' => 'dd/mm/yy',
        'timeInputFormat' => '2',
        'weekBegins' => '1',
      ],
      'domainId' => 1,
    ]);
  }

  private function setMoneyFormat() {
    civicrm_api4('Setting', 'set', [
      'values' => [
        'monetaryThousandSeparator' => ' ',
        'monetaryDecimalPoint' => ',',
        'moneyformat' => '%a %c',
        'moneyvalueformat' => '%!i',
        'defaultCurrency' => 'EUR',
      ],
    ]);
  }
}
