<?php

namespace Form\Inputs;

trait Text
{
    public function text(string $name, string $label, string $placeholder = '', int $col = 6, array $rules = [], array $attributes = []): self
    {
        $this->fields[$name] = [
            'type' => FieldType::TEXT,
            'label' => $label,
            'placeholder' => $placeholder,
            'col' => $col,
            'rules' => $rules,
            'attributes' => $attributes
        ];

        return $this;
    }
}
