<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Customer &raquo; {{ $item->name }} 
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="w-full rounded overflow-hidden shadow-lg px-6 py-6 bg-white">
            <div class="flex flex-wrap -mx-4 -mb-4 md:mb-0">
              <div class="w-full md:w-1/6 px-4 mb-4 md:mb-0">
              
                @if ($item->profile_photo_path !== NULL)
                  <img src="{{ URL::asset("storage/{$item->profile_photo_path}") }}" alt="" class="w-32 h-32 rounded">
                @else
                  <img src="https://thebattengroup.com/wp-content/uploads/2017/03/no-image-icon.png") }}" alt="" class="w-32 h-32 rounded">
                @endif
              
            </div>
            <div class="w-full md:w-5/6 px-4 mb-4 md:mb-0">
              <div class="flex flex-wrap mb-3">
                <div class="w-2/6">
                  <div class="text-sm">Name</div>
                  <div class="text-xl font-bold">{{ $item->name }}</div>
                </div>
                <div class="w-1/6">
                  <div class="text-sm">Email</div>
                  <div class="text-xl font-bold">{{ $item->email }}</div>
                </div>
                <div class="w-1/6">
                  <div class="text-sm">Phone</div>
                  <div class="text-xl font-bold">{{ $item->phone_number }}</div>
                </div>
                <div class="w-1/6">
                  <div class="text-sm">City</div>
                  <div class="text-xl font-bold">{{ Str::upper($item->city) }}</div>
                </div>
              </div>
              <div class="flex flex-wrap mb-3">
                <div class="w-4/6">
                  <div class="text-sm">Address</div>
                  <div class="text-xl font-bold">{{ $item->address }}</div>
                </div>
              </div>
              {{--  --}}
              
            </div>
            </div>
          </div>
        </div>
    </div>
</x-app-layout>
