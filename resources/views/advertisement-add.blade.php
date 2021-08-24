<x-app-layout>
    @section('head')
    <link rel='stylesheet' href='https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css' />
    <link rel='stylesheet' href='https://unpkg.com/filepond/dist/filepond.min.css' />

    <script src='https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js'></script>
    <script src='https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js'></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src='https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js'></script>
    <script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script>
    <script src='https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js'></script>
    <script src='https://unpkg.com/filepond/dist/filepond.min.js'></script>
    @endsection
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Advertisements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    New Add
                </div>
            </div>
        </div>
    </div>



    <section class="container px-6 py-4 mx-auto">
        <form class="w-full max-w-lg">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                        Title
                    </label>
                    <input name="title" required class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-last-name" type="text" placeholder="Doe">
                </div>
            </div>

            <div>
                <label class="block text-left" style="max-width: 400px;">
                    <span class="text-gray-700">Description</span>
                    <textarea name="description" required class="form-textarea mt-1 block w-full" rows="3" placeholder="Enter some long form content."></textarea>
                </label>
                <p>
                    Images
                </p>
                <div>
                    <div class="col s12 m8">

                        <input type="file" class="filepond" name="filepond[]" multiple data-max-file-size="6MB" data-max-files="5" />
                        <input type="hidden" value="Upload Photo(s)" name="B1" class="btn btn-info" />
                        <input type="hidden" name="sentPhotos" value="1" />

                        <script>
                            //FOR MORE INFORMATION VISIT
                            //https://medium.com/web-design-web-developer-magazine/leveraging-js-file-uploader-libraries-in-php-forms-example-using-filepond-754cdc9a8b48

                            // register desired plugins...
                            FilePond.registerPlugin( // encodes the file as base64 data...
                                FilePondPluginFileEncode, // validates the size of the file...
                                FilePondPluginFileValidateSize,

                                // validates the file type...
                                FilePondPluginFileValidateType, // corrects mobile image orientation...
                                FilePondPluginImageExifOrientation,

                                // calculates & dds cropping info based on the input image dimensions and the set crop ratio
                                FilePondPluginImageCrop,

                                //  calculates & adds resize information
                                FilePondPluginImageResize,

                                // applies the image modifications supplied by the Image crop and Image resize plugins before the image is uploaded
                                FilePondPluginImageTransform, // previews dropped images...
                                FilePondPluginImagePreview); // Select the file input and use create() to turn it into a pond
                            FilePond.create(document.querySelector('.filepond'), {

                                allowMultiple: true,
                                allowFileEncode: true,
                                maxFiles: 5,
                                required: true,
                                maxParallelUploads: 5,
                                instantUpload: false,
                                acceptedFileTypes: ['image/*'],
                                imageResizeTargetWidth: 100,
                                //imageResizeMode: 'contain',
                                imageCropAspectRatio: '4:3',
                                imageTransformVariants: {
                                    'v2_500px': transforms => {
                                        transforms.resize.size.width = 500;
                                        return transforms;
                                    }
                                },
                                imageTransformOutputQuality: 80,
                                imageTransformOutputMimeType: 'image/jpeg',

                                onaddfile: (err, fileItem) => {
                                    console.log(err, fileItem.getMetadata('resize'));
                                },

                                // alter the output property
                                onpreparefile: (fileItem, outputFiles) => {
                                    // loop over the outputFiles array
                                    outputFiles.forEach(output => {
                                        const img = new Image(); // output now is an object containing a `name` and a `file` property, we only need the `file`
                                        img.src = URL.createObjectURL(output.file);
                                        //document.body.appendChild(img);
                                    })
                                }

                            });
                        </script>
                        <br />
                        <div class="mt-5"><button name="submit" type="submit" class="btn" href="#">Publish property</button></div>
                        <br /><br />
                    </div>
                    
                </div>
            </div>
        </form>

    </section>

    @section('scripts')
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script type="text/javascript" src="/js/pages/add-property.js"></script>
    @endsection
</x-app-layout>