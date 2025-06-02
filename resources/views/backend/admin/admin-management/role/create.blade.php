<x-admin::layout>
    <x-slot name="title">{{ __('Create Role') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Create Role') }}</x-slot>
    <x-slot name="page_slug">role</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Create Role') }}</h2>
                <x-admin.primary-link href="{{ route('am.role.index') }}">{{ __('Back') }}
                </x-admin.primary-link>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('am.role.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="space-y-2 col-span-2">
                            <p class="label">{{ __('Role Name') }}</p>
                            <input type="text" class="input" placeholder="Role" value="{{ old('name') }}"
                                name="name" class="flex-1" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                        <div class="col-span-2">
                            <h2 class="text-lg font-bold mb-3">{{ __('Permissions') }}</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                                @foreach ($groupedPermissions->chunk(1) as $chunks)
                                    <div class="space-y-2 glass-card rounded-2xl p-6">
                                        @foreach ($chunks as $prefix => $permissions)
                                            <label class="flex items-center gap-2">
                                                <input type="checkbox"
                                                    class="!checkbox !checkbox-xs bg-transparent checkbox-accent prefix-checkbox"
                                                    id="prefix-checkbox-{{ $prefix }}"
                                                    data-prefix="{{ $prefix }}" />
                                                <span class="label"
                                                    for="prefix-checkbox-{{ $prefix }}">{{ $prefix }}</span>
                                            </label>
                                            <div class="divider m-0"></div>
                                            <ul class="list p-0">
                                                @foreach ($permissions as $permission)
                                                    <li class="list-row items-center py-1">
                                                        <input type="checkbox" name="permissions[]"
                                                            id="permission-checkbox-{{ $permission->id }}"
                                                            value="{{ $permission->id }}"
                                                            class="ml-2 bg-transparent permission-checkbox !checkbox !checkbox-xs checkbox-success">
                                                        <label
                                                            for="permission-checkbox-{{ $permission->id }}">{{ slugToTitle($permission->name) }}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-5">
                        <x-admin.primary-button>{{ __('Create') }}</x-admin.primary-button>
                    </div>
                </form>
            </div>

            {{-- documentation will be loded here and add md:col-span-2 class --}}

        </div>
    </section>

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Toggle all permission checkboxes under this prefix
                function updatePrefixCheckbox($prefix) {
                    const $group = $prefix.closest('.glass-card');
                    const $permissions = $group.find('.permission-checkbox');
                    const allChecked = $permissions.length === $permissions.filter(':checked').length;
                    $prefix.prop('checked', allChecked);
                }

                // Function to toggle all permission checkboxes under a prefix
                function togglePermissions($prefix, isChecked) {
                    const $group = $prefix.closest('.glass-card');
                    $group.find('.permission-checkbox').prop('checked', isChecked);
                }

                // On change of a prefix checkbox, toggle all associated permission checkboxes
                $('.prefix-checkbox').on('change', function() {
                    togglePermissions($(this), $(this).prop('checked'));
                });

                // On change of a permission checkbox, update its prefix checkbox
                $('.permission-checkbox').on('change', function() {
                    const $group = $(this).closest('.glass-card');
                    const $prefix = $group.find('.prefix-checkbox');
                    updatePrefixCheckbox($prefix);
                });
            });
        </script>
    @endpush


</x-admin::layout>
