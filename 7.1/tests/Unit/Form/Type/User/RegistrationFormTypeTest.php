<?php

namespace App\Tests\Unit\Form\Type\User;

use App\Entity\User;
use App\Form\Type\User\RegistrationFormType;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationFormTypeTest extends TypeTestCase
{
    private MockObject&UserPasswordHasherInterface $userPasswordHasher;

    protected function setUp(): void
    {
        $this->userPasswordHasher = $this->createMock(UserPasswordHasherInterface::class);

        parent::setUp();
    }

    /**
     * @return PreloadedExtension[]
     */
    protected function getExtensions(): array
    {
        $type = new RegistrationFormType($this->userPasswordHasher);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            'username' => 'MyTestUsername',
            'password' => 'MyTestPassword',
        ];

        $form = $this->factory->create(RegistrationFormType::class, new User());

        $expected = new User();
        $expected->username = 'MyTestUsername';

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $form->getData());
    }
}