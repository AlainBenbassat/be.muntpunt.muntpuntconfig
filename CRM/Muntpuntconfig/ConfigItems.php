<?php

class CRM_Muntpuntconfig_ConfigItems {

  public static function load() {
    $resourcePath = Civi::resources()->getPath('be.muntpunt.muntpuntconfig') . '/resources';

    civicrm_api3('Civiconfig', 'load_json', [
      'path' => $resourcePath
    ]);

    // note really a config item, but we set it here as well
    self::addContactIdentity_oldCiviCRMId();
  }

  private static function addContactIdentity_oldCiviCRMId() {
    CRM_Identitytracker_Configuration::add_identity_type(CRM_Muntpuntconfig_Config::ICONTACT_ID_TYPE, 'Oude CiviCRM ID (iContact)');
  }
}
