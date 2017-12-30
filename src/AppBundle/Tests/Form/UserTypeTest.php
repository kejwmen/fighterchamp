<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 12/30/17
 * Time: 1:11 PM
 */

use AppBundle\Entity\User;
use AppBundle\Form\User\UserType;

class UserTypeTest extends \Symfony\Component\Form\Test\TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'email' => 'slawomir.grochowski@gmail.com',
        );

        $form = $this->factory->create(UserType::class);

        $object = new User($formData['email']);


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
