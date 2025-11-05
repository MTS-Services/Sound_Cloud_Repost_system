<x-admin::layout>
    <x-slot name="title">{{ __('User List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('User List') }}</x-slot>
    <x-slot name="page_slug">user</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('User List') }}</h2>
                <div class="flex items-center gap-2">
                    <x-button href="{{ route('um.user.trash') }}" icon="trash-2" type='secondary' permission="user-trash">
                        {{ __('Trash') }}
                    </x-button>
                </div>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th width="5%">{{ __('SL') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Profile Link') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Banned Status') }}</th>
                        <th>{{ __('Last Synced At') }}</th>
                        <th>{{ __('Created By') }}</th>
                        <th>{{ __('Created Date') }}</th>
                        <th width="10%">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </section>

    {{-- Modals Start Here --}}

    {{-- Add Credit Modal --}}
    <dialog id="add_credit_modal"
        class="modal modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm animate-fade-in flex items-center justify-center min-h-screen p-4">
        <div
            class="modal-box glass-card relative w-full max-w-2xl mx-auto rounded-2xl shadow-2xl animate-slide-up bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg border border-white/20 dark:border-gray-700/30">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <i data-lucide="layout-list" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <h3 id="modal-title" class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ __('Add Credit') }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Add credit to user') }}
                        </p>
                    </div>
                </div>

                <form method="dialog">
                    <button
                        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </form>
            </div>

            <!-- Content -->
            <div class="modal-action p-6">
                <form action="" method="POST" id="add_credit_form" class="space-y-4 w-full">
                    @csrf
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">{{ __('Credit') }}</span>
                        </label>
                        <input type="number" name="credit" id="credit"
                            class="input input-bordered text-gray-800 dark:text-gray-200 w-full"
                            placeholder="{{ __('Enter credit') }}" required>
                    </div>
                    {{-- <div class="flex items-center justify-end w-full mt-4">
                            <button type="submit" class="btn btn-primary">{{ __('Add Credit') }}</button>
                        </div> --}}
                    <div class="flex justify-end mt-5">
                        <x-button type="accent" :button="true" icon="wallet">{{ __('Add Credit') }}</x-button>
                    </div>
                </form>
            </div>
        </div>

    </dialog>

    <dialog id="add_plan_modal" x-data="{ yearly_plan: false }"
        class="modal modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm animate-fade-in flex items-center justify-center min-h-screen p-4">
        <div
            class="modal-box glass-card relative w-full max-w-5xl mx-auto rounded-2xl shadow-2xl animate-slide-up bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg border border-white/20 dark:border-gray-700/30">
            <!-- Header -->
            <div
                class="flex items-center justify-between p-6 pt-0 border-b border-gray-200 dark:border-gray-700 relative">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <i data-lucide="layout-list" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <h3 id="modal-title" class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ __('Assign Plan') }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Assign a plan to the user') }}
                        </p>
                    </div>
                </div>

                <form method="dialog" class="block">
                    <button
                        class="btn btn-sm btn-circle btn-ghost text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </form>

                <div
                    class="absolute -bottom-5 right-1/2 translate-x-1/2 transform glass-card px-4 py-2 z-10 text-gray-800 dark:text-gray-200 rounded-2xl">
                    <div class="flex items-center justify-center gap-4 text-sm">
                        <span class="">{{ __('Monthly') }}</span>
                        <span class="">
                            <input type="checkbox" name="" id="yearly_plan" x-model="yearly_plan"
                                class="toggle! toggle-error! toggle-md! bg-transparent! checked:bg-none!" />

                        </span>
                        <span class="text-error font-bold">{{ __('Yearly') }}</span>
                    </div>
                </div>
            </div>

            <!-- Content with pricing cards. It is now scrollable independently of the header and footer. -->
            <div class="modal-action p-6 h-fit  max-h-[70vh] overflow-y-auto">

                <div class="space-y-4 w-full text-gray-800 dark:text-gray-200">
                    <!-- Responsive grid layout for cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach (App\Models\Plan::all() as $plan)
                            @if ($plan->monthly_price == 0)
                                @continue
                            @endif

                            <div
                                class=" card flex flex-col p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg ring-1 ring-inset ring-gray-200 dark:ring-gray-700 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl">
                                <p class="text-2xl sm:text-3xl font-bold mb-2 text-center">{{ $plan->name }}</p>

                                <div class="flex flex-col items-center justify-center my-4">
                                    <p
                                        class="text-2xl sm:text-3xl font-extrabold dark:text-indigo-400 transition-all duration-300 relative text-indigo-500">
                                        <span
                                            x-text="yearly_plan ? '{{ number_format($plan->yearly_price, 2) }}' : '{{ number_format($plan->monthly_price, 2) }}'"></span>
                                        <sup class="line-through text-base text-red-500"
                                            x-text="yearly_plan ? '{{ number_format($plan->monthly_price * 12, 2) }}' : '' "></sup>
                                    </p>
                                    <p class="text-base font-medium text-gray-500 dark:text-gray-400 ml-2 mt-2"
                                        x-text="yearly_plan ? 'Yearly'  : 'Monthly' "> </p>
                                    <p class="bg-gray-100 dark:bg-gray-700 mt-2 w-full py-1 rounded-lg text-xs text-center text-gray-800 dark:text-gray-200"
                                        :class="yearly_plan ? 'hidden' : 'block'"
                                        x-text="yearly_plan ? ''  : 'Save {{ $plan->yearly_save_percentage }}% yearly' ">
                                    </p>

                                </div>

                                <x-button type="accent" :button="true" icon="wallet" icon_position="left"
                                    class="assign-plan-button"
                                    data-plan-id="{{ encrypt($plan->id) }}">{{ __('Assign') }}</x-button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </dialog>

    <dialog id="plan_confirm_modal"
        class="modal modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm animate-fade-in flex items-center justify-center min-h-screen p-4">
        <div
            class="modal-box glass-card relative w-full max-w-2xl mx-auto rounded-2xl shadow-2xl animate-slide-up bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg border border-white/20 dark:border-gray-700/30">
            <!-- Header -->
            <div
                class="flex items-center justify-between modal-action p-3 pt-0 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <i data-lucide="layout-list" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <h3 id="modal-title" class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ __('Confirm') }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Confirm to assign plan') }}
                        </p>
                    </div>
                </div>

                <form method="dialog">
                    <button
                        class="btn btn-sm btn-circle btn-ghost text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </form>
            </div>

            <!-- Content -->
            <div class="modal-action p-3">
                <form id="plan-assignment-form" action="{{ route('um.user.add-plan') }}" method="POST"
                    class="space-y-4 w-full">
                    @csrf
                    <input type="hidden" id="confirm-user-urn" name="user_urn" class="hidden opacity-0 invisible">
                    <input type="hidden" id="confirm-plan-id" name="plan_id" class="hidden opacity-0 invisible">
                    <input type="hidden" id="yearly-checkbox" name="yearly_plan"
                        class="hidden opacity-0 invisible">
                    <div>
                        <h3 id="plan-confirm-text"
                            class="text-lg font-bold text-gray-900 dark:text-white text-center mb-3">
                            {{ __('Are you sure to assign this plan?') }}</h3>

                        <div class="flex justify-between items-center gap-3 mt-4">
                            {{-- Add onclick to close the modal --}}
                            <x-button type="secondary" :button="true" :is_submit="false" icon_position="left"
                                icon="x-circle" :outline="true"
                                onclick="document.getElementById('plan_confirm_modal').close()">{{ __('Cancel') }}</x-button>

                            {{-- The Assign button will now submit this form --}}
                            <x-button type="accent" :button="true" icon="check-circle" icon_position="left">
                                <span>{{ __('Assign') }}</span>
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </dialog>


    {{-- Details Modal --}}
    <x-admin.details-modal />

    @push('js')
        <script src="{{ asset('assets/js/details-modal.js') }}"></script>
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    //name and data, orderable, searchable
                    ['name', true, true],
                    ['profile_link', true, true],
                    ['status', true, true],
                    ['banned_at', true, true],
                    ['last_synced_at', true, true],
                    ['creater_id', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('um.user.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 4, 5, 6],
                    model: 'User',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>

        {{-- Details Modal --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                $(document).on('click', '.view', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('um.user.show', ':id') }}";

                    const details = [{
                            label: '{{ __('Name') }}',
                            key: 'name',
                            icon: 'user'
                        },
                        {
                            label: '{{ __('Nickname') }}',
                            key: 'nickname',
                            icon: 'user-circle'
                        },
                        {
                            label: '{{ __('SoundCloud ID') }}',
                            key: 'soundcloud_id',
                            icon: 'fingerprint'
                        },
                        {
                            label: '{{ __('Status') }}',
                            key: 'status_label',
                            label_color: 'status_color',
                            type: 'badge'
                        },
                        {
                            label: '{{ __('Last Synced At') }}',
                            key: 'last_synced_at',
                            icon: 'clock'
                        },
                        {
                            label: '{{ __('Image') }}',
                            key: 'modified_image',
                            type: 'image'
                        },

                        {
                            label: '{{ __('First Name') }}',
                            key: 'userInfo?.first_name',
                            icon: 'user'
                        },
                        {
                            label: '{{ __('Last Name') }}',
                            key: 'userInfo?.last_name',
                            icon: 'user'
                        },
                        {
                            label: '{{ __('Full Name') }}',
                            key: 'userInfo?.full_name',
                            icon: 'user'
                        },
                        {
                            label: '{{ __('Username') }}',
                            key: 'userInfo?.username',
                            icon: 'at-sign'
                        },
                        {
                            label: '{{ __('Description') }}',
                            key: 'userInfo?.description',
                            icon: 'align-left'
                        },

                        {
                            label: '{{ __('Country') }}',
                            key: 'userInfo?.country',
                            icon: 'globe'
                        },
                        {
                            label: '{{ __('City') }}',
                            key: 'userInfo?.city',
                            icon: 'map-pin'
                        },
                        {
                            label: '{{ __('Plan') }}',
                            key: 'userInfo?.plan',
                            icon: 'award'
                        },
                        {
                            label: '{{ __('MySpace Name') }}',
                            key: 'userInfo?.myspace_name',
                            icon: 'music'
                        },
                        {
                            label: '{{ __('Discogs Name') }}',
                            key: 'userInfo?.discogs_name',
                            icon: 'disc'
                        },

                        {
                            label: '{{ __('Website Title') }}',
                            key: 'userInfo?.website_title',
                            icon: 'text-align-left'
                        },
                        {
                            label: '{{ __('Website') }}',
                            key: 'userInfo?.website',
                            icon: 'link'
                        },
                        {
                            label: '{{ __('Local') }}',
                            key: 'userInfo?.local',
                            icon: 'map-pin'
                        },

                        {
                            label: '{{ __('SoundCloud URN') }}',
                            key: 'userInfo?.soundcloud_urn',
                            icon: 'link'
                        },
                        {
                            label: '{{ __('SoundCloud Kind') }}',
                            key: 'userInfo?.soundcloud_kind',
                            icon: 'tag'
                        },
                        {
                            label: '{{ __('Permalink URL') }}',
                            key: 'userInfo?.soundcloud_permalink_url',
                            icon: 'globe'
                        },
                        {
                            label: '{{ __('Permalink') }}',
                            key: 'userInfo?.soundcloud_permalink',
                            icon: 'link'
                        },
                        {
                            label: '{{ __('SoundCloud URI') }}',
                            key: 'userInfo?.soundcloud_uri',
                            icon: 'link-2'
                        },

                        {
                            label: '{{ __('Created At') }}',
                            key: 'userInfo?.soundcloud_created_at',
                            icon: 'calendar'
                        },
                        {
                            label: '{{ __('Last Modified') }}',
                            key: 'userInfo?.soundcloud_last_modified',
                            icon: 'clock'
                        },

                        {
                            label: '{{ __('Track Count') }}',
                            key: 'userInfo?.track_count',
                            icon: 'music'
                        },
                        {
                            label: '{{ __('Favorites Count') }}',
                            key: 'userInfo?.public_favorites_count',
                            icon: 'star'
                        },
                        {
                            label: '{{ __('Reposts Count') }}',
                            key: 'userInfo?.reposts_count',
                            icon: 'refresh-ccw'
                        },
                        {
                            label: '{{ __('Followers Count') }}',
                            key: 'userInfo?.followers_count',
                            icon: 'users'
                        },
                        {
                            label: '{{ __('Following Count') }}',
                            key: 'userInfo?.following_count',
                            icon: 'user-plus'
                        },

                        {
                            label: '{{ __('Comments Count') }}',
                            key: 'userInfo?.comments_count',
                            icon: 'message-circle'
                        },
                        {
                            label: '{{ __('Like Count') }}',
                            key: 'userInfo?.like_count',
                            icon: 'thumbs-up'
                        },
                        {
                            label: '{{ __('Playlist Count') }}',
                            key: 'userInfo?.playlist_count',
                            icon: 'list-music'
                        },
                        {
                            label: '{{ __('Private Playlist Count') }}',
                            key: 'userInfo?.private_playlist_count',
                            icon: 'lock'
                        },
                        {
                            label: '{{ __('Private Tracks Count') }}',
                            key: 'userInfo?.private_tracks_count',
                            icon: 'lock'
                        },

                        {
                            label: '{{ __('Email Confirmed') }}',
                            key: 'userInfo?.primary_email_confirmed',
                            type: 'boolean'
                        },
                        {
                            label: '{{ __('Upload Seconds Left') }}',
                            key: 'userInfo?.upload_seconds_left',
                            icon: 'clock'
                        },
                    ];

                    showDetailsModal(route, id, '{{ __('User Details') }}', details);
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {

                $(document).on('click', '.add-credit', function() {
                    const user_urn = $(this).data('id');
                    const route = "{{ route('um.user.add-credit', ':urn') }}";
                    const url = route.replace(':urn', user_urn);
                    const form = document.getElementById('add_credit_form');
                    form.action = url;
                    const modal = document.getElementById('add_credit_modal');
                    modal.showModal();
                });


                let currentUserUrn = null;
                let isYearlyPlan = false;

                $(document).on('click', '.add-plan', function() {
                    currentUserUrn = $(this).data('id');
                    const modal = document.getElementById('add_plan_modal');
                    modal.showModal();
                });

                $(document).on('click', '.assign-plan-button', function() {
                    const planId = $(this).data('plan-id');

                    // Get the Alpine.js data context for the modal
                    const modalElement = document.getElementById('add_plan_modal');
                    const alpineComponent = Alpine.$data(modalElement);

                    // Get the current state of the yearly toggle
                    isYearlyPlan = alpineComponent.yearly_plan;

                    // Get the elements for the confirm modal
                    const confirmModal = document.getElementById('plan_confirm_modal');
                    const confirmForm = document.getElementById('plan-assignment-form');
                    const userUrnInput = document.getElementById('confirm-user-urn');
                    const planIdInput = document.getElementById('confirm-plan-id');
                    const yearlyPlanInput = document.getElementById('yearly-checkbox');

                    // Set the hidden inputs with the data
                    userUrnInput.value = currentUserUrn;
                    planIdInput.value = planId;
                    yearlyPlanInput.value = isYearlyPlan ? 1 : 0;

                    // Show the confirmation modal
                    confirmModal.showModal();
                });
            });
        </script>
    @endpush
</x-admin::layout>
