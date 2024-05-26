<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ChatInputForm extends Form
{
    #[Validate('required|string')]
    public string $input = '';
}
