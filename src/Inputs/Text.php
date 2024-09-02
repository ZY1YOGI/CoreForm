<?php

namespace Core\Form\Inputs;

trait Text
{
    public function text(string $name, string $label, string $placeholder = '', int $col = 6, array $rules = []): self
    {
        return $this->addInput($name, InputType::TEXT, $label, $placeholder, $col, $rules);
    }
}
