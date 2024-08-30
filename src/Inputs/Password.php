<?php

namespace Form\Inputs;

trait Password
{
    public function password(string $name, string $label, string $placeholder = '', int $col = 6, array $rules = [], array $attributes = []): self
    {
        $this->fields[$name] = [
            'type' => FieldType::PASSWORD,
            'label' => $label,
            'placeholder' => $placeholder,
            'col' => $col,
            'rules' => $rules,
            'attributes' => $attributes
        ];
        return $this;
    }
}
