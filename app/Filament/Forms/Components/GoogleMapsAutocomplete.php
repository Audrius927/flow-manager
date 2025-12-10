<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Field;

class GoogleMapsAutocomplete extends Field
{
    use HasExtraInputAttributes;

    protected string $view = 'filament.forms.components.google-maps-autocomplete';

    protected string | \Closure | null $placeholder = null;

    protected int | \Closure | null $maxLength = null;

    protected string | \Closure | null $apiKey = null;

    protected string | \Closure | null $types = null;

    protected string | \Closure | null $componentRestrictions = 'lt';

    public function placeholder(string | \Closure | null $placeholder): static
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function maxLength(int | \Closure | null $length): static
    {
        $this->maxLength = $length;

        return $this;
    }

    public function apiKey(string | \Closure | null $key): static
    {
        $this->apiKey = $key;

        return $this;
    }

    public function types(string | \Closure | null $types): static
    {
        $this->types = $types;

        return $this;
    }

    public function componentRestrictions(string | \Closure | null $restrictions): static
    {
        $this->componentRestrictions = $restrictions;

        return $this;
    }

    public function getPlaceholder(): ?string
    {
        return $this->evaluate($this->placeholder);
    }

    public function getMaxLength(): ?int
    {
        return $this->evaluate($this->maxLength);
    }

    public function getApiKey(): ?string
    {
        return $this->evaluate($this->apiKey) ?? config('services.google.maps_api_key') ?? env('GOOGLE_MAPS_API_KEY');
    }

    public function getTypes(): ?string
    {
        return $this->evaluate($this->types);
    }

    public function getComponentRestrictions(): ?string
    {
        return $this->evaluate($this->componentRestrictions);
    }
}
