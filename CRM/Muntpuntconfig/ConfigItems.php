<?php

class CRM_Muntpuntconfig_ConfigItems {

  public static function load() {
    // create the contact sub types first
   self::createContactSubTypes();

    $resourcePath = Civi::resources()->getPath('be.muntpunt.muntpuntconfig') . '/resources';

    $result = civicrm_api3('Civiconfig', 'load_json', [
      'path' => $resourcePath
    ]);

    // not really a config item, but we set it here as well
    self::addContactIdentity_oldCiviCRMId();
  }

  private static function addContactIdentity_oldCiviCRMId() {
    CRM_Identitytracker_Configuration::add_identity_type(CRM_Muntpuntconfig_Config::ICONTACT_ID_TYPE, 'Oude CiviCRM ID (iContact)');
  }

  private static function createContactSubTypes() {
    try {
      $params = [
        'name' => 'perspartner',
        'label' => 'Perspartner',
        'parent_id' => 3,
      ];
      civicrm_api3('ContactType', 'create', $params);

      $params = [
        'name' => 'persmedewerker',
        'label' => 'Persmedewerker',
        'parent_id' => 1,
      ];
      civicrm_api3('ContactType', 'create', $params);
    }
    catch (Exception $e) {
      // ignore
    }
  }
}
