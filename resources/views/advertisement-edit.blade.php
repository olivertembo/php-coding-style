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
                    Edit Ad
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
                    <form action="" method="POST">
                        @csrf
                        <h4 id="name" class="text-xl font-semibold mb-2">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                Title
                            </label>
                            <input name="title" maxlength="225" value="{{ $data['title']}}" required class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-last-name" type="text" placeholder="Doe">

                        </h4>
                        <p id="job" class="text-gray-800 mt-2">


                            <label class="block text-left" style="max-width: 400px;">
                                <span class="text-gray-700">Description</span>
                                <textarea name="description" maxlength="1023" required class="form-textarea mt-1 block w-full" rows="3" placeholder="Enter some long form content.">{{ $data['description']}}
                                </textarea>
                            </label>

                        </p>
                        <div class="flex mt-5">
                            <div class="mt-5"><button name="submit" type="submit" class="btn bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded align-content: flex-end" href="#">Save</button></div>

                        </div>
                    </form>

                </div>
            </div>
        </div>


    </section>


</x-app-layout>