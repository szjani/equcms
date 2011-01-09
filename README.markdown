EquCMS
======
This is a Zend Framework and Doctrine 2 based MVC system, which supports CRUD operations.

Screenshot: [http://bit.ly/fEmkdQ](http://bit.ly/fEmkdQ)

The folowing techniques are used:

* [Symfony Dependency Container](http://components.symfony-project.org/dependency-injection/) instead of ``Zend_Application_Resource`` (there are some resources waiting for porting to DI)
* You can store users, groups and URL-s (module, controller, action trio) as resources in database in tree hierarchy.
  I create a ``Zend_Acl`` object from these datas. It's important that roles are lazy loadable, so application doesn't inject every role
  to the ACL object therefore you can use thousands of users, it won't indicates performance issues.
* ``Zend_Navigation`` object also build from the the previous database.
* You can use the builtin CRUD solution with your own entities:
    * A simple example:
        * Your controller has to extend ``Equ\Crud\Controller`` and implement ``getService()`` method. Usually it retrieves
    a service object from dependency container: ``return $this->_helper->serviceContainer('user');``
        * Service class has to extend ``Equ\Crud\Service`` and implement ``getEntityClass()`` method. It retrieves the class
     name of your entitie, like: ``return 'entities\User';``
        * That's all.
    * Of course you have many possibilities to modify and extend basic CRUD process:
        * It is possible to inject form builder objects to your controller class to modify form building process (remove, add, modify elements etc.).
        * It is possible to inject an entity builder object to your service class to modify entity building process (basically system try to call setter method on entities).
        * Now there are 2 form element creator solutions in the system: Builtin and Dojo factory. You can inject any of these
    (or your own factory instance) into form builder object.
        * You can copy CRUD .phtml files into the right directory and it will be used for output rendering.
        * If your entity extends ``\Equ\Entity`` and you use ``addFieldValidator()`` to validate fields before persisting, generated
    form will also contains these validators. In element creators you also can create javascript validators from these objects.
    (For example Dojo creators use required flag, and e-mail validation with regexp).
        * etc.