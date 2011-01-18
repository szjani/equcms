<?php
namespace modules\permission\plugins;

use Equ\Entity\FormBuilder;

class CrudFormBuilder extends FormBuilder {

  protected $ignoredFields = array('role');

  public function postVisit() {
    $this->getForm()->getElement('f_resource')->setOrder(1);
    $this->getForm()->getElement('f_privilege')->setOrder(2);
    $this->getForm()->getElement('f_allowed')->setOrder(3);
    $this->getForm()->getElement('save')->setOrder(10);

    $role = new \Zend_Dojo_Form_Element_FilteringSelect('f_role');
    $role
      ->setOrder(0)
      ->setLabel('entities/RoleResource/f_role');
    $this->getForm()->addElement($role);

    /* @var $role \Zend_Dojo_Form_Element_FilteringSelect */
    $role
      ->setAutocomplete(true)
      ->setStoreId('roleResourceStore')
      ->setStoreType('dojox.data.QueryReadStore')
      ->setStoreParams(array('url' => '/admin/role/autocomplete/format/ajax'))
      ->setAttrib("searchAttr", "role");
    
    if ($this->entity->getRole() instanceof \entities\Role) {
      $entityRole = $this->entity->getRole();
      $role
        ->addMultiOption(array($entityRole->getId(), $entityRole->getRoleId()))
        ->setAttrib('value', $entityRole->getRoleId())
        ->setAttrib('displayedValue', $entityRole->getRoleId());
    }
  }

}