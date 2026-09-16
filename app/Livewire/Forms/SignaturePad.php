<?php

namespace App\Livewire\Forms;

use Livewire\Component;

class SignaturePad extends Component
{
    public ?string $existingUrl = null;

    public bool $readOnly = false;

    public function mount(?string $existingUrl = null, bool $readOnly = false): void
    {
        $this->existingUrl = $existingUrl;
        $this->readOnly = $readOnly;
    }

    public function save(string $dataUrl): void
    {
        $this->dispatch('signature-captured', dataUrl: $dataUrl);
    }

    public function removeSignature(): void
    {
        $this->existingUrl = null;
        $this->dispatch('signature-cleared');
    }

    public function render()
    {
        return view('livewire.forms.signature-pad');
    }
}
