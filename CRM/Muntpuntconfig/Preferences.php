<?php

class CRM_Muntpuntconfig_Preferences {
  public static function set() {
    self::setDateFormat();
    self::setMoneyFormat();
    self::setFromEmailAddress();
    self::setDisablePoweredByCiviCRM();
  }

  private static function setDateFormat() {
    civicrm_api4('Setting', 'set', [
      'values' => [
        'dateformatDatetime' => '%E %B %Y %k:%M uur',
        'dateformatFull' => '%E %B %Y',
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

  private static function setMoneyFormat() {
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

  private static function setFromEmailAddress() {
    \Civi\Api4\OptionValue::update()
      ->addValue('label', '"Muntpunt" <info@muntpunt.be>')
      ->addValue('name', 'Muntpunt" <info@muntpunt.be>')
      ->addWhere('option_group_id:name', '=', 'from_email_address')
      ->addWhere('value', '=', 1)
      ->execute();
  }

  private static function setDisablePoweredByCiviCRM() {
    \Civi\Api4\Setting::set()
      ->addValue('empoweredBy', 0)
      ->setDomainId(1)
      ->execute();
  }

}
