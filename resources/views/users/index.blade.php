<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Store Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-10">
              <a href="{{ route('users.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">+ Create Store Admin</a>
            </div>
            <div class="bg-white">
              <table class="table-auto w-full">
                <thead>
                  <tr>
                    <th class="border px-6 py-4">No</th>
                    <th class="border px-6 py-4">Name</th>
                    <th class="border px-6 py-4">Email</th>
                    <th class="border px-6 py-4">Phone</th>
                    <th class="border px-6 py-4">City</th>
                    <th class="border px-6 py-4">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                      $no = 1;
                  @endphp
                  @forelse ($user as $item)
                    @if ($item->roles == 'admin')
                        <tr>
                          <td class="border px-6 py-4">{{ $no++ }}</td>
                          <td class="border px-6 py-4">{{ $item->name }}</td>
                          <td class="border px-6 py-4">{{ $item->email }}</td>
                          <td class="border px-6 py-4">{{ $item->phone_number }}</td>
                          <td class="border px-6 py-4">{{ Str::upper($item->city) }}</td>
                          <td class="border px-6 py-4 text-center">
                            <a href="{{ route('users.edit', $item->id) }}" class="iniline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mx-2 rounded">Edit</a>
                            <form action="{{ route('users.destroy', $item->id) }}" method="POST" class="inline-block">
                              {!! method_field('delete') .csrf_field() !!}
                              <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 mx-2 rounded">Delete</button>
                            </form>
                          </td>
                        </tr>
                    @endif
                  @empty
                      <tr>
                        <td colspan="6" class="border text-center p-5">Data not found</td>
                      </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
            <div class="text-center mt-5">
              {{ $user->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
