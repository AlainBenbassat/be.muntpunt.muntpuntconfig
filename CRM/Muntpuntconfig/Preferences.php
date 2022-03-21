<?php

class CRM_Muntpuntconfig_Preferences {
  public static function set() {
    self::setDateFormat();
    self::setMoneyFormat();
    self::setFromEmailAddress();
    self::setDisablePoweredByCiviCRM();
    self::setDefaultOrgName();
    self::setDefaultOrgEmail();
    self::setDefaultOrgAddress();
    self::setGreeting();
  }

  public static function setSMTP($settings) {
    $mailingBackend = [];
    $mailingBackend['outBound_option'] = CRM_Mailing_Config::OUTBOUND_OPTION_SMTP;
    $mailingBackend['sendmail_args'] = 0;
    $mailingBackend['smtpAuth'] = 1;
    $mailingBackend['smtpServer'] = $settings['smtpServer'];
    $mailingBackend['smtpPort'] = $settings['smtpPort'];
    $mailingBackend['smtpUsername'] = $settings['smtpUsername'];
    $mailingBackend['smtpPassword'] = $settings['smtpPassword'];
    Civi::settings()->set('mailing_backend', $mailingBackend);
  }

  public static function setBackendTheme() {
    Civi::settings()->set('theme_backend', 'finsburypark');
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
      'checkPermissions' => FALSE,
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
      'checkPermissions' => FALSE,
    ]);
  }

  private static function setFromEmailAddress() {
    \Civi\Api4\OptionValue::update(FALSE)
      ->addValue('label', '"Muntpunt" <info@muntpunt.be>')
      ->addValue('name', 'Muntpunt" <info@muntpunt.be>')
      ->addWhere('option_group_id:name', '=', 'from_email_address')
      ->addWhere('value', '=', 1)
      ->execute();
  }

  private static function setDisablePoweredByCiviCRM() {
    \Civi\Api4\Setting::set(FALSE)
      ->addValue('empoweredBy', 0)
      ->setDomainId(1)
      ->execute();
  }

  private static function setDefaultOrgName() {
    \Civi\Api4\Contact::update()
      ->addValue('organization_name', 'Muntpunt')
      ->addValue('legal_name', 'Muntpunt')
      ->addWhere('id', '=', 1)
      ->execute();

    \Civi\Api4\Domain::update()
      ->addValue('name', 'Muntpunt')
      ->addWhere('id', '=', 1)
      ->execute();
  }

  private static function setDefaultOrgEmail() {
    \Civi\Api4\Email::update()
      ->addValue('email', 'info@muntpunt.be')
      ->addWhere('id', '=', 1)
      ->execute();
  }

  private static function setDefaultOrgAddress() {
    \Civi\Api4\Address::create()
      ->addValue('contact_id', 1)
      ->addValue('street_address', 'Munt 6')
      ->addValue('postal_code', 1000)
      ->addValue('city', 'Brussel')
      ->addValue('country_id.name', 'Belgium')
      ->addValue('location_type_id:name', 'Work')
      ->addValue('is_primary', TRUE)
      ->execute();
  }

  private static function setGreeting() {
    $sql = "update civicrm_option_value set name = replace(name, 'Dear ', 'Dag '), label = replace(label, 'Dear ', 'Dag ') where option_group_id in (42,43)";
    CRM_Core_DAO::executeQuery($sql);
  }

}
