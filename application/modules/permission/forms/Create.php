<?php
namespace modules\permission\forms;
use
  Equ\Form\IMappedType,
  Equ\Form\IBuilder,
  Equ\Form\OptionFlags;

class Create implements IMappedType {
  
  public function buildForm(IBuilder $builder) {
    $builder
      ->add('role', $this->getAutocompleteRoleField())
      ->add('resource')
      ->add('allowed', 'boolean')
      ->add('privilege');
  }
  
  public function getObjectClass() {
    return 'entities\RoleResource';
  }
  
  protected function getAutocompleteRoleField() {
    $role = new \Zend_Dojo_Form_Element_FilteringSelect('role');
    $role
      ->setOrder(0)
      ->setDijitParam('placeHolder', \Zend_Form::getDefaultTranslator()->translate('role'))
      ->setLabel('role');

    /* @var $role \Zend_Dojo_Form_Element_FilteringSelect */
    $role
      ->setAutocomplete(true)
      ->setStoreId('roleStore')
      ->setStoreType('dojox.data.QueryReadStore')
      ->setStoreParams(array('url' => '/admin/role/autocomplete/format/ajax'))
      ->setAttrib("searchAttr", "role");
    return $role;
  }
}
