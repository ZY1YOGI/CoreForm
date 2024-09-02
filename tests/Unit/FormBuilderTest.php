<?php

namespace Core\Form\Tests\Unit;

use Core\Form\Inputs\InputType;
use Core\Form\FormBuilder;
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


    public function testFormSchema(): void
    {
        // Add different types of inputs
        $this->form->text('username', 'Username', 'Enter your username')
            ->password('password', 'Password', 'Enter your password')
            ->text('email', 'Email', 'Enter your email', 12);

        // Get the schema
        $schema = $this->form->getSchema();

        // Define the expected schema output
        $expectedSchema = [
            'username' => [
                'type' => InputType::TEXT,
                'label' => 'Username',
                'placeholder' => 'Enter your username',
                'col' => 6,
            ],
            'password' => [
                'type' => InputType::PASSWORD,
                'label' => 'Password',
                'placeholder' => 'Enter your password',
                'col' => 6,
            ],
            'email' => [
                'type' => InputType::TEXT,
                'label' => 'Email',
                'placeholder' => 'Enter your email',
                'col' => 12,
            ],
        ];

        // Assert that the schema matches the expected output
        $this->assertEquals($expectedSchema, $schema);
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

    public function testUpdateRules(): void
    {
        $this->form->text('username', 'Username', 'Enter your username', 6, ['required', 'string', 'max:255'])
            ->text('email', 'Email', 'Enter your email', 6, ['required', 'email']);

        // Update the rules for 'username' field
        $this->form->updateRules('username', ['required', 'string', 'min:3']);

        $rules = $this->form->getRules();

        $this->assertArrayHasKey('username', $rules);
        $this->assertEquals(['required', 'string', 'min:3'], $rules['username']);
    }

    public function testPerformanceOfAddingInputs(): void
    {
        $startTime = microtime(true);

        $form = FormBuilder::make('performanceForm');

        for ($i = 0; $i < 100000; $i++) {
            $form->text("field{$i}", 'Field Label', 'Field Placeholder');
        }

        $endTime = microtime(true);
        $duration = $endTime - $startTime;

        $this->assertLessThan(0000000001, $duration, 'Performance test failed: Time taken exceeds 1 second.');
    }
}
