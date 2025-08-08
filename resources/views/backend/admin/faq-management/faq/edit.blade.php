<x-admin::layout>
    <x-slot name="title">{{ __('Faq ') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Faq ') }}</x-slot>
    <x-slot name="page_slug">faq</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Edit Faq') }}</h2>
                <x-button href="{{ route('fm.faq.index') }}" icon="undo-2" type='info' permission="Faq Edit">
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('fm.faq.update', encrypt($faq->id)) }}" class="space-y-4') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        <div class="space-y-2">
                            <x-inputs.input name="question" label="{{ __('Question') }}" placeholder="Enter Question"
                                value="{{$faq->question}}" :messages="$errors->get('question')" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 mb-4">
                        <div class="space-y-2">
                            <x-inputs.input name="description" label="{{ __('Description') }}"
                                placeholder="Enter Description" value="{{$faq->description}}" :messages="$errors->get('description')" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 mb-4">
                        <div class="space-y-2">
                            <label for="key"
                                class="block text-sm font-medium text-gray-700">{{ __('Select Key') }}</label>
                            <select name="key" id="key" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">-- Select --</option>
                                @foreach (\App\Models\Faq::keyLists() as $key => $label)
                                    <option value="{{ $key }}" {{ old('key') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('key')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                        <div class="justify-end mt-5 items-left">
                            <x-button type="accent" :button="true" icon="save">{{ __('Create') }}</x-button>
                    </div>
                </form>
            </div>

            {{-- documentation will be loded here and add md:col-span-2 class --}}

        </div>
    </section>
</x-admin::layout>
