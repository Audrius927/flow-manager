@php
    $apiKey = $getApiKey();
    $statePath = $getStatePath();
    $fieldId = $getId();
    $types = $getTypes();
    $componentRestrictions = $getComponentRestrictions();
    $extraInputAttributes = $getExtraInputAttributeBag();
@endphp

@once
    @push('scripts')
        <script src="https://maps.googleapis.com/maps/api/js?key={{ $apiKey }}&libraries=places&loading=async" async defer></script>
        <script>
            function googleMapsAutocomplete(config) {
                return {
                    apiKey: config.apiKey,
                    fieldId: config.fieldId,
                    types: config.types,
                    componentRestrictions: config.componentRestrictions,
                    autocomplete: null,
                    
                    init() {
                        if (!window.google || !window.google.maps || !window.google.maps.places) {
                            // Jei Google Maps API dar neįkeltas, laukiame
                            const checkGoogle = setInterval(() => {
                                if (window.google && window.google.maps && window.google.maps.places) {
                                    clearInterval(checkGoogle);
                                    this.initAutocomplete();
                                }
                            }, 100);
                            
                            // Timeout po 10 sekundžių
                            setTimeout(() => {
                                clearInterval(checkGoogle);
                                if (!window.google || !window.google.maps || !window.google.maps.places) {
                                    console.error('Google Maps API neįkeltas');
                                }
                            }, 10000);
                        } else {
                            this.initAutocomplete();
                        }
                    },
                    
                    initAutocomplete() {
                        if (!this.$refs.input) {
                            return;
                        }
                        
                        const options = {};
                        
                        // Types turi būti masyvas arba undefined
                        if (this.types) {
                            if (Array.isArray(this.types)) {
                                options.types = this.types;
                            } else if (typeof this.types === 'string') {
                                options.types = [this.types];
                            }
                        }
                        
                        if (this.componentRestrictions) {
                            if (typeof this.componentRestrictions === 'string') {
                                // Jei string, konvertuojame į masyvą (pvz., 'lt' -> ['lt'] arba 'lt,ee' -> ['lt', 'ee'])
                                options.componentRestrictions = { 
                                    country: this.componentRestrictions.split(',').map(c => c.trim())
                                };
                            } else if (typeof this.componentRestrictions === 'object') {
                                // Jei jau objektas, naudojame kaip yra
                                options.componentRestrictions = this.componentRestrictions;
                            }
                        }
                        
                        this.autocomplete = new google.maps.places.Autocomplete(this.$refs.input, options);
                        
                        // Funkcija dropdown uždarymui
                        const closeDropdown = () => {
                            const pacContainer = document.querySelector('.pac-container');
                            if (pacContainer) {
                                pacContainer.style.display = 'none';
                            }
                        };
                        
                        this.autocomplete.addListener('place_changed', () => {
                            const place = this.autocomplete.getPlace();
                            if (place && place.formatted_address) {
                                // Atnaujiname Livewire state per wire:model
                                this.$refs.input.value = place.formatted_address;
                                this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
                                
                                // Uždarome dropdown po pasirinkimo
                                setTimeout(() => {
                                    closeDropdown();
                                }, 100);
                            }
                        });
                        
                        // Uždarome dropdown, kai vartotojas paspaudžia Enter
                        this.$refs.input.addEventListener('keydown', (e) => {
                            if (e.key === 'Enter') {
                                setTimeout(() => {
                                    closeDropdown();
                                }, 100);
                            }
                        });
                        
                        // Uždarome dropdown, kai vartotojas paspaudžia ant pasiūlymo
                        const handlePacClick = (e) => {
                            if (e.target && (e.target.classList.contains('pac-item') || e.target.closest('.pac-item'))) {
                                setTimeout(() => {
                                    closeDropdown();
                                }, 200);
                            }
                        };
                        
                        // Uždarome dropdown, kai vartotojas paspaudžia už input lauko ir dropdown ribų
                        const handleOutsideClick = (e) => {
                            const pacContainer = document.querySelector('.pac-container');
                            const isClickInsideInput = this.$refs.input && this.$refs.input.contains(e.target);
                            const isClickInsideDropdown = pacContainer && pacContainer.contains(e.target);
                            
                            // Jei paspaudė už input lauko ir dropdown ribų, uždaryti dropdown
                            if (!isClickInsideInput && !isClickInsideDropdown && pacContainer) {
                                closeDropdown();
                            }
                        };
                        
                        // Stebime paspaudimus ant dropdown elementų
                        document.addEventListener('mousedown', handlePacClick, true);
                        
                        // Stebime paspaudimus už input lauko ir dropdown ribų
                        document.addEventListener('click', handleOutsideClick, true);
                    }
                };
            }
        </script>
    @endpush
@endonce

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <x-filament::input.wrapper
        :disabled="$isDisabled()"
        :valid="! $errors->has($statePath)"
        :attributes="
            \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                ->class(['fi-fo-google-maps-autocomplete'])
        "
    >
        <div
            x-data="googleMapsAutocomplete({
                apiKey: @js($apiKey),
                fieldId: @js($fieldId),
                types: @js($types),
                componentRestrictions: @js($componentRestrictions)
            })"
            data-google-autocomplete-id="{{ $fieldId }}"
        >
            <input
                x-ref="input"
                type="text"
                {{
                    $extraInputAttributes
                        ->merge([
                            'id' => $fieldId,
                            'placeholder' => $getPlaceholder(),
                            'maxlength' => $getMaxLength(),
                            'disabled' => $isDisabled(),
                            'required' => $isRequired(),
                            'autocomplete' => 'off',
                            $applyStateBindingModifiers('wire:model') => $statePath,
                        ], escape: false)
                        ->class(['fi-input'])
                }}
            />
        </div>
    </x-filament::input.wrapper>
</x-dynamic-component>
