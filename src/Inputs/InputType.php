<?php

namespace Core\Form\Inputs;

enum InputType: string
{
    case TEXT = 'text';
    case EMAIL = 'email';
    case NUMBER = 'number';
    case DATE = 'date';
    case PASSWORD = 'password';
}
