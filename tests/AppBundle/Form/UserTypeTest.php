<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 12/30/17
 * Time: 1:11 PM
 */

use AppBundle\Entity\User;
use AppBundle\Form\User\UserType;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\Validator\ConstraintViolationList;


class UserTypeTest extends \Symfony\Component\Form\Test\TypeTestCase
{
    protected function setUp()
    {
        $validator = $this->createMock('\Symfony\Component\Validator\Validator\ValidatorInterface');
        $validator->method('validate')->will($this->returnValue(new ConstraintViolationList()));
        $formTypeExtension = new FormTypeValidatorExtension($validator);
        $coreExtension = new CoreExtension();

        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getExtensions())
            ->addExtension($coreExtension)
            ->addTypeExtension($formTypeExtension)
            ->getFormFactory();
    }


    public function testSubmitValidData()
    {
        $formData =
            [
            'email' => 'slawomir.grochowski@gmail.com',
            'name' => 'Mario'
        ];

        $form = $this->factory->create(UserType::class);

        $object = new User
        (
            $formData['email'],
            $formData['name']
    );


        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
