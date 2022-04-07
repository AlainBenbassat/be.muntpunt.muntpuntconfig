<?php

class CRM_Muntpuntconfig_Preferences {
  public static function set() {
    self::setFromEmailAddress();
    return;
    self::setLanguage();
    self::setCountry();
    self::setDateFormat();
    self::setMoneyFormat();
    self::setFromEmailAddress();
    self::setRedactionAddress();
    self::setDisablePoweredByCiviCRM();
    self::setDefaultOrgName();
    self::setDefaultOrgEmail();
    self::setDefaultOrgAddress();
    self::setGreeting();
    self::enableComponents();
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

  private static function setLanguage() {
    Civi::settings()->set('lcMessages', 'nl_BE');
    Civi::settings()->set('uiLanguages', ['nl_BE']);
  }

  private static function setCountry() {
    Civi::settings()->set('defaultContactCountry', 1020);
    Civi::settings()->set('defaultContactCountry', 1020);
    Civi::settings()->set('countryLimit', [1020]);
  }

  private static function setDateFormat() {
    civicrm_api4('Setting', 'set', [
      'values' => [
        'dateformatDatetime' => '%E %B %Y %k:%M',
        'dateformatFull' => '%E %B %Y',
        'dateformatTime' => '%H:%M',
        'dateformatFinancialBatch' => '%d.%m.%Y',
        'dateformatshortdate' => '%d.%m.%Y',
        'dateInputFormat' => 'dd.mm.yy',
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
        'monetaryThousandSeparator' => '.',
        'monetaryDecimalPoint' => ',',
        'moneyformat' => '%c %a',
        'moneyvalueformat' => '%!i',
        'defaultCurrency' => 'EUR',
      ],
      'checkPermissions' => FALSE,
    ]);

    // delete usd from available currencies
    $optionGroupId = CRM_Core_DAO::singleValueQuery("select id from civicrm_option_group where name = 'currencies_enabled'");
    $sql = "delete from civicrm_option_value where option_group_id = $optionGroupId and value = 'USD'";
    CRM_Core_DAO::executeQuery($sql);
  }

  private static function setFromEmailAddress() {
    \Civi\Api4\OptionValue::update(FALSE)
      ->addValue('label', '"Muntpunt" <info@muntpunt.be>')
      ->addValue('name', 'Muntpunt" <info@muntpunt.be>')
      ->addWhere('option_group_id:name', '=', 'from_email_address')
      ->addWhere('value', '=', 1)
      ->execute();

    $fromAddresses = [
      16 => '"Broodje Brussel" <broodjebrussel@muntpunt.be>',
      17 => '"Muntpunt" <zakelijk@muntpunt.be>',
      18 => '"UiTinBrussel" <info@uitinbrussel.be>',
      30 => '"Paspartoe" <info@paspartoebrussel.be>',
      33 => '"Muntpunt Jobs" <jobs@muntpunt.be>',
    ];

    foreach ($fromAddresses as $fromAddressValue => $fromAddressLabel) {
      \Civi\Api4\OptionValue::create(FALSE)
        ->addValue('value', $fromAddressValue)
        ->addValue('label', $fromAddressLabel)
        ->addValue('name', $fromAddressLabel)
        ->addValue('is_default', 0)
        ->addValue('option_group_id:name', 'from_email_address')
        ->execute();
    }
  }

  private static function setRedactionAddress() {
    \Civi\Api4\LocationType::update(FALSE)
      ->addValue('label', 'Redactie')
      ->addValue('display_name', 'Redactie')
      ->addValue('description', 'Redactieadres')
      ->addWhere('id', '=', 4)
      ->execute();
  }

  private static function setDisablePoweredByCiviCRM() {
    \Civi\Api4\Setting::set(FALSE)
      ->addValue('empoweredBy', 0)
      ->setDomainId(1)
      ->execute();
  }

  private static function setDefaultOrgName() {
    \Civi\Api4\Contact::update(FALSE)
      ->addValue('organization_name', 'Muntpunt')
      ->addValue('legal_name', 'Muntpunt')
      ->addWhere('id', '=', 1)
      ->execute();

    \Civi\Api4\Domain::update(FALSE)
      ->addValue('name', 'Muntpunt')
      ->addWhere('id', '=', 1)
      ->execute();
  }

  private static function setDefaultOrgEmail() {
    \Civi\Api4\Email::update(FALSE)
      ->addValue('email', 'info@muntpunt.be')
      ->addWhere('id', '=', 1)
      ->execute();
  }

  private static function setDefaultOrgAddress() {
    \Civi\Api4\Address::create(FALSE)
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

  private static function enableComponents() {
    $enabledComponents = CRM_Core_Config::singleton()->enableComponents;
    if (!in_array('CiviCampaign', $enabledComponents)) {
      $enabledComponents[] = 'CiviCampaign';
      Civi::settings()->set('enable_components', $enabledComponents);

      self::clearCache();
    }
  }

  private static function clearCache() {
    CRM_Core_Config::clearDBCache();
    Civi::cache('session')->clear();
    CRM_Utils_System::flushCache();
    CRM_Core_Resources::singleton()->resetCacheCode();
  }

}
