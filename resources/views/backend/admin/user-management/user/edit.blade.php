<x-admin::layout>
    <x-slot name="title">{{ __('Update User') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Update User') }}</x-slot>
    <x-slot name="page_slug">user</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Update User') }}</h2>
                <x-button href="{{ route('um.user.index') }}" icon="undo-2" type='info' permission="admin-list">
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1 {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('um.user.update', encrypt($user->id)) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="space-y-2">
                            <x-inputs.input name="name" label="{{ __('Name') }}" icon="user"
                                placeholder="Enter Name" value="{{ $user->name }}" :messages="$errors->get('name')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="nickname" label="{{ __('Nickname') }}" icon="user-circle"
                                placeholder="Enter Nickname" value="{{ $user->nickname }}" :messages="$errors->get('nickname')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="soundcloud_id" label="{{ __('SoundCloud ID') }}" icon="fingerprint"
                                placeholder="Enter SoundCloud ID" value="{{ $user->soundcloud_id }}"
                                :messages="$errors->get('soundcloud_id')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="first_name" label="{{ __('First Name') }}" icon="user"
                                placeholder="Enter First Name" value="{{ $user->userInfo?->first_name }}"
                                :messages="$errors->get('first_name')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="last_name" label="{{ __('Last Name') }}" icon="user"
                                placeholder="Enter Last Name" value="{{ $user->userInfo?->last_name }}"
                                :messages="$errors->get('last_name')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="full_name" label="{{ __('Full Name') }}" icon="user"
                                placeholder="Enter Full Name" value="{{ $user->userInfo?->full_name }}"
                                :messages="$errors->get('full_name')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="username" label="{{ __('Username') }}" icon="at-sign"
                                placeholder="Enter Username" value="{{ $user->userInfo?->username }}"
                                :messages="$errors->get('username')" />
                        </div>

                        <div class="space-y-2 col-span-2">
                            <x-inputs.textarea name="description" class="textarea" rows="8"
                                label="{{ __('Description') }}" icon="align-left" placeholder="Enter Description"
                                :messages="$errors->get('description')">{{ $user->userInfo?->description }}</x-inputs.textarea>
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="country" label="{{ __('Country') }}" icon="globe"
                                placeholder="Enter Country" value="{{ $user->userInfo?->country }}"
                                :messages="$errors->get('country')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="city" label="{{ __('City') }}" icon="map-pin"
                                placeholder="Enter City" value="{{ $user->userInfo?->city }}" :messages="$errors->get('city')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="plan" label="{{ __('Plan') }}" icon="award"
                                placeholder="Enter Plan" value="{{ $user->userInfo?->plan }}" :messages="$errors->get('plan')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="myspace_name" label="{{ __('MySpace Name') }}" icon="music"
                                placeholder="Enter MySpace Name" value="{{ $user->userInfo?->myspace_name }}"
                                :messages="$errors->get('myspace_name')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="discogs_name" label="{{ __('Discogs Name') }}" icon="disc"
                                placeholder="Enter Discogs Name" value="{{ $user->userInfo?->discogs_name }}"
                                :messages="$errors->get('discogs_name')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="website_title" label="{{ __('Website Title') }}"
                                icon="text-align-left" placeholder="Enter Website Title"
                                value="{{ $user->userInfo?->website_title }}" :messages="$errors->get('website_title')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="website" label="{{ __('Website') }}" icon="link"
                                placeholder="Enter Website URL" value="{{ $user->userInfo?->website }}"
                                :messages="$errors->get('website')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="local" label="{{ __('Local') }}" icon="map-pin"
                                placeholder="Enter Local" value="{{ $user->userInfo?->local }}" :messages="$errors->get('local')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="full_name" label="{{ __('Full Name') }}" icon="user"
                                placeholder="Enter Full Name" value="{{ $user->userInfo?->full_name ?? '' }}"
                                :messages="$errors->get('full_name')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="soundcloud_urn" label="{{ __('SoundCloud URN') }}" icon="link"
                                placeholder="Enter SoundCloud URN"
                                value="{{ $user->userInfo?->soundcloud_urn ?? '' }}" :messages="$errors->get('soundcloud_urn')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="soundcloud_kind" label="{{ __('SoundCloud Kind') }}"
                                icon="tag" placeholder="Enter SoundCloud Kind"
                                value="{{ $user->userInfo?->soundcloud_kind ?? '' }}" :messages="$errors->get('soundcloud_kind')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="soundcloud_permalink_url" label="{{ __('Permalink URL') }}"
                                icon="globe" placeholder="Enter Permalink URL"
                                value="{{ $user->userInfo?->soundcloud_permalink_url ?? '' }}" :messages="$errors->get('soundcloud_permalink_url')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="soundcloud_permalink" label="{{ __('Permalink') }}" icon="link"
                                placeholder="Enter Permalink"
                                value="{{ $user->userInfo?->soundcloud_permalink ?? '' }}" :messages="$errors->get('soundcloud_permalink')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="soundcloud_uri" label="{{ __('SoundCloud URI') }}" icon="link-2"
                                placeholder="Enter SoundCloud URI"
                                value="{{ $user->userInfo?->soundcloud_uri ?? '' }}" :messages="$errors->get('soundcloud_uri')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input type="datetime-local" name="soundcloud_created_at"
                                label="{{ __('Created At') }}" icon="calendar"
                                value="{{ \Carbon\Carbon::parse($user->userInfo?->soundcloud_created_at)->format('Y-m-d\TH:i') }}"
                                :messages="$errors->get('soundcloud_created_at')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input type="datetime-local" name="soundcloud_last_modified"
                                label="{{ __('Last Modified') }}" icon="clock"
                                value="{{ $user->userInfo?->soundcloud_last_modified ? \Carbon\Carbon::parse($user->userInfo->soundcloud_last_modified)->format('Y-m-d\TH:i') : '' }}"
                                :messages="$errors->get('soundcloud_last_modified')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input type="number" name="track_count" label="{{ __('Track Count') }}"
                                icon="music" placeholder="Enter Track Count"
                                value="{{ $user->userInfo?->track_count ?? 0 }}" :messages="$errors->get('track_count')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input type="number" name="public_favorites_count"
                                label="{{ __('Favorites Count') }}" icon="star"
                                placeholder="Enter Favorites Count"
                                value="{{ $user->userInfo?->public_favorites_count ?? 0 }}" :messages="$errors->get('public_favorites_count')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input type="number" name="reposts_count" label="{{ __('Reposts Count') }}"
                                icon="refresh-ccw" placeholder="Enter Reposts Count"
                                value="{{ $user->userInfo?->reposts_count ?? 0 }}" :messages="$errors->get('reposts_count')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input type="number" name="followers_count"
                                label="{{ __('Followers Count') }}" icon="users"
                                placeholder="Enter Followers Count"
                                value="{{ $user->userInfo?->followers_count ?? 0 }}" :messages="$errors->get('followers_count')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input type="number" name="following_count"
                                label="{{ __('Following Count') }}" icon="user-plus"
                                placeholder="Enter Following Count"
                                value="{{ $user->userInfo?->following_count ?? 0 }}" :messages="$errors->get('following_count')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input type="number" name="comments_count" label="{{ __('Comments Count') }}"
                                icon="message-circle" placeholder="Enter Comments Count"
                                value="{{ $user->userInfo?->comments_count ?? 0 }}" :messages="$errors->get('comments_count')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input type="number" name="like_count" label="{{ __('Like Count') }}"
                                icon="thumbs-up" placeholder="Enter Like Count"
                                value="{{ $user->userInfo?->like_count ?? 0 }}" :messages="$errors->get('like_count')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input type="number" name="playlist_count" label="{{ __('Playlist Count') }}"
                                icon="list-music" placeholder="Enter Playlist Count"
                                value="{{ $user->userInfo?->playlist_count ?? 0 }}" :messages="$errors->get('playlist_count')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input type="number" name="private_playlist_count"
                                label="{{ __('Private Playlist Count') }}" icon="lock"
                                placeholder="Enter Private Playlist Count"
                                value="{{ $user->userInfo?->private_playlist_count ?? 0 }}" :messages="$errors->get('private_playlist_count')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input type="number" name="private_tracks_count"
                                label="{{ __('Private Tracks Count') }}" icon="lock"
                                placeholder="Enter Private Tracks Count"
                                value="{{ $user->userInfo?->private_tracks_count ?? 0 }}" :messages="$errors->get('private_tracks_count')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="primary_email_confirmed" label="{{ __('Email Confirmed') }}"
                                :checked="$user->userInfo?->primary_email_confirmed ?? false" :messages="$errors->get('primary_email_confirmed')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input type="number" name="upload_seconds_left"
                                label="{{ __('Upload Seconds Left') }}" icon="clock"
                                placeholder="Enter Upload Seconds Left"
                                value="{{ $user->userInfo?->upload_seconds_left ?? '' }}" :messages="$errors->get('upload_seconds_left')" />
                        </div>


                        <div class="space-y-2 col-span-2">
                            <x-inputs.file name="image" label="{{ __('Image') }}"
                                accept="image/jpeg, image/png, image/jpg, image/webp, image/svg" :messages="$errors->get('image')" />
                        </div>
                    </div>

                    <div class="flex justify-end mt-5">
                        <x-button type="accent" :button="true" icon="save">{{ __('Update') }}</x-button>
                    </div>
                </form>
            </div>
        </div>

        @push('js')
            <script src="{{ asset('assets/js/ckEditor.js') }}"></script>
            <script src="{{ asset('assets/js/filepond.js') }}"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    file_upload(["#image"], ["image/jpeg", "image/png", "image/jpg", "image/webp", "image/svg"], {
                        "#image": "{{ $user->modified_image }}"
                    });
                });
            </script>
        @endpush
    </section>
</x-admin::layout>
