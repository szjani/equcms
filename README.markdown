EquCMS
======
This is a Zend Framework and Doctrine 2 based CMS system, which supports CRUD operations.

Changelog
---------

### 2.2.x ###

* Doctrine 2.2 compatibility
* Uses the builtin Paginator instead of beberlei's
* Symfony classloader instead of backported ZF2 classloader (there was a problem with the last one)
* User entity doesn't extends Role anymore. If you need fine-grained permission control you can still create a bunch of groups. All user has to belong to a group.
* Several small modifications in configuration, user and authentication interfaces
* [New screenshot](http://goo.gl/yPxOe)

### 2.0.x ###

Screenshot: [http://bit.ly/fEmkdQ](http://bit.ly/fEmkdQ)

The folowing techniques are used:

* [Symfony Dependency Container](http://components.symfony-project.org/dependency-injection/) instead of ``Zend_Application_Resource`` (there are some resources waiting for porting to DI)
* You can store users, groups and URL-s (module, controller, action trio) as resources in database in tree hierarchy.
  I create a ``Zend_Acl`` object from these datas. It's important that roles are lazy loadable, so application doesn't inject every role
  to the ACL object therefore you can use thousands of users, it won't indicates performance issues.
* ``Zend_Navigation`` object also build from the the previous database.
* You can use the builtin CRUD solution with your own entities:
    * A simple example:
        * Your controller has to extend ``Equ\Crud\AbstractController`` and implement ``getMainForm()`` and ``getFilterForm()`` methods. These should retrieve IMappedType objects for form building.
        * That's all.
    * Of course you have many possibilities to modify and extend basic CRUD process:
        * Now there are 2 form element creator solutions in the system: Builtin and Dojo factory.
        * You can copy CRUD .phtml files into the right directory and it will be used for output rendering.
        * If your entity implements Equ\Object\Validatable, generated form will also contains these validators. In element creators you also can create javascript validators from these objects.
    (For example Dojo creators use required flag, and e-mail validation with regexp).
        * etc.
* After made your clone of the project, you should modify include path in defines.php and see /hu/admin or /en/admin URL-s.
You'll need [beberlei's paginator adapter](https://github.com/beberlei/DoctrineExtensions) and [Gedmo's nested set implementation](https://github.com/l3pp4rd/DoctrineExtensions).

Examples:
<pre>
class Comment {
  
  private $author = null;
  
  private $text = null;
  
  public function __construct(Author $author) {
    $this->setAuthor($author);
  }
  
  public function getText() {
    return $this->text;
  }

  public function setText($text) {
    $this->text = $text;
  }

  /**
   * @return Author
   */
  public function getAuthor() {
    return $this->author;
  }

  public function setAuthor($author) {
    $this->author = $author;
  }
  
}
</pre>

<pre>
class Author implements Validatable {
  
  private $name;
  
  private $email;
  
  public function __construct($name) {
    $this->setName($name);
  }
  
  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
  }
  
  public function getEmail() {
    return $this->email;
  }

  public function setEmail($email) {
    $this->email = $email;
  }

  public static function loadValidators(Validator $validator) {
    $validator
      ->add('email', new \Zend_Validate_NotEmpty())
      ->add('email', new \Zend_Validate_EmailAddress());
  }
}
</pre>

<pre>
class AuthorType implements IMappedType {
  
  public function buildForm(IBuilder $builder) {
    $builder->add('name');
    $builder->add('email');
  }
  
  public function getObjectClass() {
    return 'Author';
  }
  
}
</pre>

<pre>
class CommentType implements IMappedType {
  
  public function buildForm(IBuilder $builder) {
    $builder->add('text');
    $builder->addSub('author', new AuthorType());
  }
  
  public function getObjectClass() {
    return 'Comment';
  }
}
</pre>

<pre>
$builder = $this->_helper->createFormBuilder(new CommentType(), 'Comment');
$form = $builder->getForm();
if ($builder->getMapper()->isValid($this->_request)) {
  $comment = $builder->getMapper()->getObject();
  $author  = $comment->getAuthor();
}
</pre>