@props(['title' => 'Details Modal'])

<dialog id="details_modal" class="modal">
    <div class="modal-box max-w-screen-md">
        <form method="dialog">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modal-title" class="text-lg font-bold">{{ __($title) }}</h3>
                <button class="btn btn-sm btn-circle btn-ghost"><i data-lucide="x"></i></button>
            </div>
        </form>

        <div id="modal-content" class="space-y-2">
            <!-- Dynamic content goes here -->
        </div>
    </div>
</dialog>
