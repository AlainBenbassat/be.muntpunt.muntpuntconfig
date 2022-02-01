<?php

class CRM_Muntpuntconfig_Config {
  const ICONTACT_ID_TYPE = 'icontact_id';
  private static $instance = null;

  private $optionGroupIdEvenementStatus;
  private $optionGroupIdMuntpuntZalen;

  private $customFields = [];

  private function __construct() {
  }

  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new CRM_Muntpuntconfig_Config();
    }

    return self::$instance;
  }

  public function getOptionGroupId_EvenementStatus() {
    if (empty($this->optionGroupIdEvenementStatus)) {
      $this->optionGroupIdEvenementStatus = $this->getOptionGroupId('evenement_status');
    }

    return $this->optionGroupIdEvenementStatus;
  }

  public function getOptionGroupId_MuntpuntZalen() {
    if (empty($this->optionGroupIdMuntpuntZalen)) {
      $this->optionGroupIdMuntpuntZalen = $this->getOptionGroupId('muntpunt_zalen');
    }

    return $this->optionGroupIdMuntpuntZalen;
  }

  public function getOptionValues_EvenementStatus($addEmptyItem) {
    $groupId = $this->getOptionGroupId_EvenementStatus();
    return $this->getOptionGroupValues($groupId, $addEmptyItem);
  }

  public function getOptionValues_MuntpuntZalen($addEmptyItem) {
    $groupId = $this->getOptionGroupId_MuntpuntZalen();
    return $this->getOptionGroupValues($groupId, $addEmptyItem);
  }

  public function getCustomValueId($fieldName) {
    if (empty($this->customFields[$fieldName])) {
      $this->customFields[$fieldName] = CRM_Core_DAO::singleValueQuery("select id from civicrm_custom_field where name = %1", [1 => [$fieldName, 'String']]);
    }

    return $this->customFields[$fieldName];
  }

  private function getOptionGroupId($name) {
    $sql = "select id from civicrm_option_group where name = %1";
    $sqlParams = [
      1 => [$name, 'String'],
    ];

    return CRM_Core_DAO::singleValueQuery($sql, $sqlParams);
  }

  private function getOptionGroupValues($optionGroupId, $addEmptyItem = TRUE) {
    if ($addEmptyItem) {
      $optionValues = ['' => '- Elke -'];
    }
    else {
      $optionValues = [];
    }

    $sql = "select value, label from civicrm_option_value where option_group_id = %1 and is_active = 1 order by label";
    $sqlParams = [
      1 => [$optionGroupId, 'Integer'],
    ];

    $dao = CRM_Core_DAO::executeQuery($sql, $sqlParams);
    while ($dao->fetch()) {
      $optionValues[$dao->value] = $dao->label;
    }

    return $optionValues;
  }


}
