<?php

namespace Form;

use Form\Inputs;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class FormBuilder
{
    use Inputs\Text;
    use Inputs\Password;

    protected array $inputs = [];
    protected string $status = "CREATE";

    public function __construct(protected string $formName) {}

    public static function make(string $formName): self
    {
        return new self($formName);
    }

    public function getFormName(): string
    {
        return $this->formName;
    }

    /**
     * Adds an input to the form.
     *
     * @param string $name The name of the input.
     * @param Inputs\InputType $type The type of the input.
     * @param string $label The label for the input.
     * @param string $placeholder The placeholder for the input.
     * @param int $col The column size for the input.
     * @param array $rules Validation rules for the input.
     * @param array $custom Additional custom properties.
     *
     * @return self
     *
     * @throws InvalidArgumentException if an input with the same name already exists.
     */
    protected function addInput(string $name, Inputs\InputType $type, string $label, string $placeholder = '', int $col = 6, array $rules = [], array ...$custom): self
    {
        if (array_key_exists($name, $this->inputs)) {
            throw new InvalidArgumentException("An input with the name '{$name}' already exists.");
        }

        $this->inputs[$name] = [
            'type' => $type,
            'label' => $label,
            'placeholder' => $placeholder,
            'col' => $col,
            'rules' => $rules,
            ...$custom
        ];

        return $this;
    }

    public function getSchema(): array
    {
        return array_map(function ($input) {
            unset($input['rules']);
            return $input;
        }, $this->inputs);
    }

    public function getRules(): array
    {
        return array_map(function ($input) {
            return $input['rules'] ?? [];
        }, $this->inputs);
    }

    public function formValidation(array $data): \Illuminate\Validation\Validator
    {
        return Validator::make($data, $this->getRules());
    }

    public function updateRules(string $name, array $rules): self
    {
        if (!isset($this->inputs[$name])) {
            throw new InvalidArgumentException("Input with the name '{$name}' does not exist.");
        }

        $this->inputs[$name]['rules'] = $rules;
        return $this;
    }
}
