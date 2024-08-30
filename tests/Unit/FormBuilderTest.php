<?php

namespace Form\Tests\Unit;

use Form\Inputs\FieldType;
use Form\FormBuilder;

it('can create a form', function () {
    $form = FormBuilder::make('exampleForm');

    expect($form->getFormName())->toBe('exampleForm');
});

it('can add a text field', function () {
    $form = FormBuilder::make('exampleForm')
        ->text('username', 'Username', 'Enter your username');

    $schema = $form->getSchema();

    expect($schema)->toHaveKey('username');
    expect($schema['username']['type'])->toBe(FieldType::TEXT);
});


it('validates data correctly with given rules', function () {
    // Define the form with rules
    $form = FormBuilder::make('exampleForm')
        ->text('username', 'Username', 'Enter your username', 6, ['required', 'string', 'max:255'])
        ->text('email', 'Email', 'Enter your email', 6, ['required', 'email']);

    // Define the data to validate
    $data = [
        'username' => 'JohnDoe',
        'email' => 'john.doe@example.com',
    ];

    // Validate the data
    $validator = $form->formValidation($data);

    // Assert that validation passes
    expect($validator->passes())->toBeTrue();
});

it('fails validation with incorrect data', function () {
    // Define the form with rules
    $form = FormBuilder::make('exampleForm')
        ->text('username', 'Username', 'Enter your username', 6, ['required', 'string', 'max:255'])
        ->text('email', 'Email', 'Enter your email', 6, ['required', 'email']);

    // Define the data to validate (intentionally invalid)
    $data = [
        'username' => '', // Fails required rule
        'email' => 'invalid-email', // Fails email rule
    ];

    // Validate the data
    $validator = $form->formValidation($data);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->get('username'))->toContain('The username field is required.');
    expect($validator->errors()->get('email'))->toContain('The email field must be a valid email address.');
});
