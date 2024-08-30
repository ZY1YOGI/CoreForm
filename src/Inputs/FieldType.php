<?php

namespace Form\Inputs;

enum FieldType: string
{
    case TEXT = 'text';
    case EMAIL = 'email';
    case NUMBER = 'number';
    case DATE = 'date';
    case PASSWORD = 'password';
}
