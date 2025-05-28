<select class="theme-toggle select select-success w-fit pr-10" x-model="$store.theme.current"
    @change="$store.theme.updateTheme()">
    <option value="system">{{ __('System') }}</option>
    <option value="dark">{{ __('Dark') }}</option>
    <option value="light">{{ __('Light') }}</option>
</select>
