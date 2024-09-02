<?php

namespace Core\Form\Inputs;

trait Password
{
    public function password(string $name, string $label, string $placeholder = '', int $col = 6, array $rules = []): self
    {
        return $this->addInput($name, InputType::PASSWORD, $label, $placeholder, $col, $rules);
    }
}
