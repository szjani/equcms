<?php
namespace plugins;
use Equ\Entity\FormBuilder;

class RoleResourceFormBuilder extends FormBuilder {

  protected $ignoredFields = array('role');

  public function postVisit() {
    $role = new \Zend_Dojo_Form_Element_FilteringSelect('role');
    $role
      ->setOrder(2)
      ->setLabel('entities/RoleResource/role');
    $this->getForm()->addElement($role);
//    $role = $this->getForm()->getElement('role');
    /* @var $role \Zend_Dojo_Form_Element_FilteringSelect */
    if ($role) {
      $role
        ->setAutocomplete(true)
        ->setStoreId('roleResourceStore')
        ->setStoreType('dojox.data.QueryReadStore')
        ->setStoreParams(array('url' => '/admin/role/autocomplete'))
        ->setAttrib("searchAttr", "role");
    }
    
    if ($this->entity->getRole() instanceof \entities\Role) {
      $entityRole = $this->entity->getRole();
      $role
        ->addMultiOption(array($entityRole->getId(), $entityRole->getRoleId()))
        ->setAttrib('value', $entityRole->getRoleId())
        ->setAttrib('displayedValue', $entityRole->getRoleId());
    }
  }

}