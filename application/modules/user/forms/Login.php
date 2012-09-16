<?php
namespace modules\user\forms;

use Equ\Form\IBuilder;
use Equ\Form\IMappedType;
use entities\User;

/**
 * Login form
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    modules
 * @package     user
 * @subpackage  forms
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class Login implements IMappedType
{
    public function buildForm(IBuilder $builder)
    {
        $builder
            ->add('email')
            ->add('password', 'password');
        $builder->getForm()->password->setRequired();
    }

    public function getObjectClass()
    {
        return User::className();
    }

}