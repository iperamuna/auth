<?php

use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Devdojo\Auth\Helper;
use Winter\LaravelConfigWriter\ArrayFile;

name('auth.setup.appearance');

new class extends Component
{
    public $appearance;
    public $descriptions;
    private $config;

    public function mount(){
        $this->appearance = (object)config('devdojo.auth.appearance');
        $this->logo = $this->appearance->logo;
        //dd($this->logo);
        $this->descriptions = (object)config('devdojo.auth.descriptions');
    }

    public function update($key, $value){
        $this->config = ArrayFile::open(base_path('config/devdojo/auth/appearance.php'));
        $this->config->set($key, $value);
        $this->config->write();
        $this->appearance = (object)config('devdojo.auth.appearance');
        $this->js('savedMessageOpen()');
    }
};

?>

<x-auth::layouts.setup>

    @volt('auth.setup.appearance')
        <section x-data="{ 
                'tab': new URLSearchParams(window.location.search).get('tab') || 'logo',
                addQueryParam(key, value) {
                    // Create a URL object based on the current document URL
                    let url = new URL(window.location.href);

                    // Set or replace the query parameter
                    url.searchParams.set(key, value);

                    // Update the URL in the address bar without reloading the page
                    window.history.pushState({ path: url.toString() }, '', url.toString());
                }
            }"
            x-init="
                $watch('tab', function(value){
                    if (value !== null) {
                        addQueryParam('tab', value);
                    }
                });
            " class="relative px-4 py-5 mx-auto w-full max-w-screen-lg">
            <x-auth::setup.full-screen-loader wire:target="update" />
            <x-auth::setup.heading title="Appearance" description="Change the appearance of your auth screens, add a logo, modify the color, and more." />
            
            <div>
                <div class="sm:hidden">
                    <label for="tabs" class="sr-only">Select a tab</label>
                    <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
                    <select id="tabs" name="tabs" class="block py-2 pr-10 pl-3 w-full text-base rounded-md border-gray-300 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                    <option>My Account</option>
                    <option>Company</option>
                    <option selected>Team Members</option>
                    <option>Billing</option>
                    </select>
                </div>
                <div class="hidden sm:block">
                    <div class="border-b border-gray-200">
                    @php
                        $tabs = ['logo' => 'Logo', 'background' => 'Background', 'colors' => 'Colors', 'alignment' => 'Alignment', 'favicon' => 'Favicon'];
                    @endphp
                    <nav class="flex -mb-px space-x-8" aria-label="Tabs">
                        @foreach($tabs as $slug => $tab)
                            <a href="#_" @click.prevent="tab = '{{ $slug }}'" 
                                :class="{ 'border-indigo-500 text-indigo-600' : tab == '{{ $slug }}', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' : tab != '{{ $slug }}' }"
                                class="px-1 py-4 text-sm font-medium whitespace-nowrap border-b-2">{{ $tab }}</a>
                        @endforeach
                        <!-- Current: "border-indigo-500 text-indigo-600", Default: "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700" -->
                    </nav>
                    </div>
                </div>
            </div>
            <div class="mt-10">
                <div x-show="tab == 'logo'" class="w-full h-auto" x-cloak>
                    <livewire:auth.setup.logo />
                </div>
                <div x-show="tab == 'background'" class="w-full h-auto" x-cloak>
                    <livewire:auth.setup.background />
                </div>
                <div x-show="tab == 'alignment'" class="w-full h-auto" x-cloak>
                    align
                </div>
            </div>
            
        </section>
    @endvolt
    

</x-auth::layouts.setup>
