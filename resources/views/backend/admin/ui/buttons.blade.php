<x-admin::layout>
    <x-slot name="title">Button UI</x-slot>
    <x-slot name="breadcrumb">Button UI</x-slot>
    <x-slot name="page_slug">button-ui</x-slot>

    <section>
        <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-white mb-8 border-b-2 border-gray-200 pb-4">
            Button Component
            Examples</h2>

        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mt-6 mb-4">Default & Type Buttons</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
            <x-button type="primary">Primary</x-button>
            <x-button type="secondary">Secondary</x-button>
            <x-button type="accent">Accent</x-button>
            <x-button type="success">Success</x-button>
            <x-button type="danger">Danger</x-button>
            <x-button type="warning">Warning</x-button>
            <x-button type="info">Info</x-button>
            <x-button type="dark">Dark</x-button>
            <x-button type="light">Light</x-button>
            <x-button type="ghost">Ghost</x-button>
            <x-button type="link">Link</x-button>
            <x-button type="neutral">Neutral</x-button>
        </div>

        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mt-6 mb-4">Button Sizes</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
            <x-button type="primary" size="lg">Large Button</x-button>
            <x-button type="primary" size="md">Medium Button</x-button>
            <x-button type="primary" size="sm">Small Button</x-button>
            <x-button type="primary" size="xs">Extra Small</x-button>
        </div>

        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mt-6 mb-4">Soft Buttons</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
            <x-button type="primary" soft="true">Primary Soft</x-button>
            <x-button type="secondary" soft="true">Secondary Soft</x-button>
            <x-button type="accent" soft="true">Accent Soft</x-button>
            <x-button type="success" soft="true">Success Soft</x-button>
            <x-button type="danger" soft="true">Danger Soft</x-button>
            <x-button type="warning" soft="true">Warning Soft</x-button>
            <x-button type="info" soft="true">Info Soft</x-button>
            <x-button type="dark" soft="true">Dark Soft</x-button>
            <x-button type="light" soft="true">Light Soft</x-button>
            <x-button type="neutral" soft="true">Neutral Soft</x-button>
        </div>

        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mt-6 mb-4">Outline Buttons</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
            <x-button type="primary" outline="true">Primary Outline</x-button>
            <x-button type="secondary" outline="true">Secondary Outline</x-button>
            <x-button type="accent" outline="true">Accent Outline</x-button>
            <x-button type="success" outline="true">Success Outline</x-button>
            <x-button type="danger" outline="true">Danger Outline</x-button>
            <x-button type="warning" outline="true">Warning Outline</x-button>
            <x-button type="info" outline="true">Info Outline</x-button>
            <x-button type="dark" outline="true">Dark Outline</x-button>
            <x-button type="light" outline="true">Light Outline</x-button>
            <x-button type="neutral" outline="true">Neutral Outline</x-button>
        </div>

        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mt-6 mb-4">Dashed Buttons</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
            <x-button type="primary" dashed="true">Primary Dashed</x-button>
            <x-button type="secondary" dashed="true">Secondary Dashed</x-button>
            <x-button type="accent" dashed="true">Accent Dashed</x-button>
            <x-button type="success" dashed="true">Success Dashed</x-button>
            <x-button type="danger" dashed="true">Danger Dashed</x-button>
            <x-button type="warning" dashed="true">Warning Dashed</x-button>
            <x-button type="info" dashed="true">Info Dashed</x-button>
            <x-button type="dark" dashed="true">Dark Dashed</x-button>
            <x-button type="light" dashed="true">Light Dashed</x-button>
            <x-button type="neutral" dashed="true">Neutral Dashed</x-button>
        </div>

        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mt-6 mb-4">Block Buttons (Full Width)</h3>
        <div class="grid grid-cols-1 gap-4 mb-8">
            <x-button type="primary" block="true">Primary Block Button</x-button>
            <x-button type="secondary" block="true" outline="true">Secondary Block Outline</x-button>
        </div>

        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mt-6 mb-4">No Animation Buttons</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
            <x-button type="primary" no_animation="true">No Animation</x-button>
            <x-button type="success" no_animation="true" outline="true">No Animation Outline</x-button>
        </div>

        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mt-6 mb-4">Active State Buttons</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
            <x-button type="primary" active="true">Active Primary</x-button>
            <x-button type="secondary" active="true" outline="true">Active Outline</x-button>
        </div>

        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mt-6 mb-4">Loading State Buttons</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
            <x-button type="primary" loading="true">Loading Primary</x-button>
            <x-button type="secondary" loading="true" outline="true">Loading Outline</x-button>
            <x-button type="success" loading="true" icon_position="left">Downloading</x-button>
            <x-button type="info" loading="true" size="lg">Submitting</x-button>
        </div>

        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mt-6 mb-4">Special Button Shapes</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
            {{-- Wide button --}}
            <x-button type="primary" class="btn-wide">Wide Button</x-button>
            {{-- Square button --}}
            <x-button type="success" shape="square" icon="check"></x-button>
            {{-- Circle button --}}
            <x-button type="info" shape="circle" icon="plus"></x-button>
        </div>

        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mt-6 mb-4">Buttons with Icons (Left & Right)
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
            <x-button type="primary" icon="plus" icon_position="left">Add Item</x-button>
            <x-button type="success" icon="save" icon_position="right">Save</x-button>
            <x-button type="info" outline="true" icon="info" icon_position="left">More
                Info</x-button>
            <x-button type="warning" soft="true" icon="settings"
                icon_position="right">Settings</x-button>
            <x-button type="secondary" size="sm" icon="edit"
                icon_position="left">Edit</x-button>
            <x-button type="error" size="xs" icon="trash-2"
                icon_position="right">Delete</x-button>
            <x-button type="ghost" icon="search" icon_position="left">Search</x-button>
            <x-button type="link" icon="eye" icon_position="right">View</x-button>
        </div>

        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mt-6 mb-4">Disabled Buttons</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
            <x-button type="primary" disabled="true">Disabled Primary</x-button>
            <x-button type="secondary" outline="true" disabled="true">Disabled Outline</x-button>
            <x-button type="success" soft="true" disabled="true">Disabled Soft</x-button>
            <x-button type="warning" disabled="true" icon="lock"
                icon_position="left">Locked</x-button>
        </div>

        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mt-6 mb-4">Custom Colored Buttons (Via
            `class` attribute)</h3>
        <p class="text-sm text-gray-600 mb-4">
            These examples show how to apply custom Tailwind color classes directly via the `class` attribute,
            which your component will merge.
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
            <x-button class="bg-blue-700 hover:bg-purple-700 text-white">Custom BG</x-button>
            <x-button class="bg-gray-200 hover:bg-gray-300 text-blue-700!">Custom Text Color</x-button>
            <x-button
                class="bg-transparent border-2 border-orange-500 hover:border-orange-700 text-orange-600">Custom
                Border</x-button>
            <x-button
                class="bg-transparent border-2 border-dashed border-teal-500 hover:border-teal-700 text-teal-600">Custom
                Dashed Outline</x-button>
            <x-button class="bg-red-500 hover:bg-red-600 text-yellow-300 border border-red-700">Full
                Custom</x-button>
        </div>

        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mt-6 mb-4">Buttons with Permissions
            (Conceptual Example)</h3>
        <p class="text-sm text-gray-600 mb-4">
            These buttons conceptually demonstrate the `permission` prop. In a real Laravel application,
            `admin()->can('permission-name')` would control their visibility based on user roles/permissions.
            Here, they are always shown for demonstration.
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
            <x-button type="primary" permission="create-users" icon="user-plus" icon_position="left">Create
                User</x-button>
            <x-button type="danger" permission="delete-posts" icon="trash" icon_position="left">Delete
                Post</x-button>
            <x-button type="info" outline="true" permission="view-reports" icon="bar-chart-2"
                icon_position="left">View Report</x-button>
        </div>
    </section>
</x-admin::layout>
