<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Advertisements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Showing all ads
                    <a href="advertisements-add" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded align-content: flex-end">
                        New Ad
                    </a>
                </div>
                
                    
                
            </div>
        </div>
    </div>



    <section class="container px-6 py-4 mx-auto">

        @foreach($data['ads'] as $item)

        <div class="grid gap-6 mb-12 md:grid-cols-1 lg:grid-cols-1">
            <!-- Card 1 -->
            <div class="flex items-center p-4 bg-white border-2 border-gray-200 rounded-lg shadow-sm dark:bg-gray-800">
                @if($item['photo_1'])
                <img alt="mountain" class="w-45 rounded-md border-2 border-gray-300" src="{{ $item['photo_1'] }}" />
                @endif
                @if($item['photo_1'])
                <img alt="mountain" class="w-45 rounded-md border-2 border-gray-300" src="{{ $item['photo_2'] }}" />
                @endif
                @if($item['photo_1'])
                <img alt="mountain" class="w-45 rounded-md border-2 border-gray-300" src="{{ $item['photo_3'] }}" />
                @endif

                <div id="body" class="flex flex-col ml-5">
                    <h4 id="name" class="text-xl font-semibold mb-2">Add Title {{ $item['title']}} </h4>
                    <p id="job" class="text-gray-800 mt-2"> {{ $item['description']}} : Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    <div class="flex mt-5">
                        <div class="operations">
                            <a class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                Delete
                            </a>
                            <a href="/advertisement/{{ $item['uuid'] }}" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                Open
                            </a>
                            <a href="/advertisements-edit/{{ $item['uuid'] }}" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                Edit
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <br />
        <div class="center-align">
            <ul class="pagination">
                @foreach($data['advertisements']['links'] as $key => $link)

                @if($key == 1 && $link['label'] == 1 && $data['advertisements']['current_page'] == 1)
                @else
                <li class="waves-effect {{ $link['active'] === true ? 'active' : '' }}"><a href="{{$link['url']}}"> {!! $link['label'] !!} </a></li>

                @endif

                @endforeach
            </ul>
        </div>
    </section>
</x-app-layout>