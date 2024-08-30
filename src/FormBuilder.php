<?php

namespace Form;

use Form\Inputs;
use Illuminate\Support\Facades\Validator;

class FormBuilder
{

    use Inputs\Text;
    use Inputs\Password;

    protected array $fields = [];

    protected string $status = "CREATE";

    public function __construct(protected string $formName) {}

    public static function make(string $formName): self
    {
        return new self($formName);
    }

    public function getSchema(): array
    {
        return array_map(function ($field) {
            unset($field['rules']);
            return $field;
        }, $this->fields);
    }

    public function getRules(): array
    {
        return array_map(function ($field) {
            return $field['rules'] ?? [];
        }, $this->fields);
    }

    public function getFormName(): string
    {
        return $this->formName;
    }

    public function formValidation(array $data): \Illuminate\Validation\Validator
    {
        return Validator::make($data, $this->getRules());
    }

    public function updateRules(string $name, array $rules): self
    {
        if (isset($this->fields[$name])) {
            $this->fields[$name]['rules'] = $rules;
        }
        return $this;
    }
}
