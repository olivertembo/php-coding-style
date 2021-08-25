<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Advertisements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Viewing single Ad
                </div>
            </div>
        </div>
    </div>



    <section class="container px-6 py-4 mx-auto">
        <div class="grid gap-6 mb-12 md:grid-cols-1 lg:grid-cols-1">
            <!-- Card 1 -->
            <div class="flex items-center p-4 bg-white border-2 border-gray-200 rounded-lg shadow-sm dark:bg-gray-800">
                <div class="images-ad">
                    @if($data['photo_1'])
                    <img alt="mountain" class="w-45 rounded-md border-2 border-gray-300 ad-image" src="{{ $data['photo_1'] }}" />
                    @endif
                    @if($data['photo_2'])
                    <img alt="mountain" class="w-45 rounded-md border-2 border-gray-300 ad-image" src="{{ $data['photo_2'] }}" />
                    @endif
                    @if($data['photo_3'])
                    <img alt="mountain" class="w-45 rounded-md border-2 border-gray-300 ad-image" src="{{ $data['photo_3'] }}" />
                    @endif
                </div>

                <div id="body" class="flex flex-col ml-5">
                    <h4 id="name" class="text-xl font-semibold mb-2">Add Title {{ $data['title']}} </h4>
                    <p id="job" class="text-gray-800 mt-2"> {{ $data['description']}} : Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    <div class="flex mt-5">
                    </div>
                </div>
            </div>
        </div>

    </section>
</x-app-layout>