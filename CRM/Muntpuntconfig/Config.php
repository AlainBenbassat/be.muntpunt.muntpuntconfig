<?php

class CRM_Muntpuntconfig_Config {
  const ICONTACT_ID_TYPE = 'icontact_id';

  public function checkConfig() {
    // general settings
    $this->setDateFormat();
    //$this->setMoneyFormat();

    // extra type for de.systopia.identitytracker
    $this->addContactIdentity_oldCiviCRMId();
  }

  public function addContactIdentity_oldCiviCRMId() {
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
