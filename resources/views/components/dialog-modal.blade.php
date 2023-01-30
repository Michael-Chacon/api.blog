@props(['modal'])
<div v-show="{{ $modal }}" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">

    <div v-on:click="{{$modal}} = false" class="fixed cursor-pointer inset-0 bg-gray-900 bg-opacity-75 transition-opacity">
    </div>
  
    <div class="w-full fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full text-center  sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-black" id="modal-title">
                                {{ $title }}
                            </h3>
                            <div class="mt-2">
                                {{ $content }}
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>