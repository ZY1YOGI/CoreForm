<?php

namespace Form\Tests\Unit;

use Form\Inputs\InputType;
use Form\FormBuilder;
use Orchestra\Testbench\TestCase;
use InvalidArgumentException;

class FormBuilderTest extends TestCase
{
    protected FormBuilder $form;

    protected function setUp(): void
    {
        parent::setUp();
        $this->form = FormBuilder::make('exampleForm');
    }

    public function testCanCreateForm(): void
    {
        $this->assertEquals('exampleForm', $this->form->getFormName());
    }

    public function testCanAddTextInput(): void
    {
        $this->form->text('username', 'Username', 'Enter your username');

        $schema = $this->form->getSchema();

        $this->assertArrayHasKey('username', $schema);
        $this->assertEquals(InputType::TEXT, $schema['username']['type']);
    }

    public function testValidatesDataCorrectlyWithGivenRules(): void
    {
        $this->form->text('username', 'Username', 'Enter your username', 6, ['required', 'string', 'max:255'])
            ->text('email', 'Email', 'Enter your email', 6, ['required', 'email']);

        $data = [
            'username' => 'JohnDoe',
            'email' => 'john.doe@example.com',
        ];

        $validator = $this->form->formValidation($data);

        $this->assertTrue($validator->passes());
    }

    public function testFailsValidationWithIncorrectData(): void
    {
        $this->form->text('username', 'Username', 'Enter your username', 6, ['required', 'string', 'max:255'])
            ->text('email', 'Email', 'Enter your email', 6, ['required', 'email']);

        $data = [
            'username' => '', // Fails required rule
            'email' => 'invalid-email', // Fails email rule
        ];

        $validator = $this->form->formValidation($data);

        $this->assertTrue($validator->fails());
        $this->assertContains('The username field is required.', $validator->errors()->get('username'));
        $this->assertContains('The email field must be a valid email address.', $validator->errors()->get('email'));
    }

    public function testCannotAddTwoTextInputsWithSameName(): void
    {
        $this->form->text('username', 'Username', 'Enter your username');

        $this->expectException(InvalidArgumentException::class);
        $this->form->text('username', 'Username', 'Enter your username');
    }

}
