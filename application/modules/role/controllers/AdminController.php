<?php
use entities\Role;

class Role_AdminController extends \Zend_Controller_Action
{
    public $em;

    public function init()
    {
        parent::init();

        $contextSwitch = $this->_helper->getHelper('contextSwitch');
        $contextSwitch
            ->addActionContext('lookup', 'json')
            ->initContext();
    }

    public function lookupAction()
    {
        $this->_helper->contextSwitch->setAutoJsonSerialization(false);
        $repo = $this->em->getRepository(Role::className());
        echo \Zend_Json::encode($this->_helper->lookUp($repo));
    }

    public function autocompleteAction()
    {
        $role = $this->_getParam("role", null);
        $role = substr($role, 0, -1); //fix : remove * at the end of the ID.

        $results = $this->em->createQueryBuilder()
            ->select('r.id, r.role')
            ->from(Role::className(), 'r')
            ->where("r.role LIKE :role")
            ->setParameter('role', '%' . $role . '%')
            ->setMaxResults(3)
            ->getQuery()
            ->getArrayResult();
        if ($role == null) {
            array_unshift($results, array('id' => 0, 'role' => ''));
        }
        $data = new Zend_Dojo_Data('id', $results);

        // Send our output
        $this->_helper->autoCompleteDojo($data);
    }

}