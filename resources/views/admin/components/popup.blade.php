@props([
    'id',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin?',
    'formId' => null,
    'onConfirm' => null,
    'confirmText' => 'Delete',
    'cancelText' => 'Cancel',
    'confirmClass' => 'text-sm link-primary ',
    'cancelClass' => 'text-sm link-secondary'
])

<div id="{{ $id }}" class="flex fixed inset-0 z-50 hidden items-center justify-center bg-gray-900 bg-opacity-70">
    <div class="w-96 rounded-lg bg-white p-6 shadow-lg">
        <div class="mb-2 flex items-center gap-x-2">
            <h3 class="text-left text-lg font-bold text-gray-800">{{ $title }}</h3>
        </div>
        <p class="text-left text-sm font-light text-gray-500">{{ $message }}</p>
        <hr class="my-2">
        <div class="mt-4 flex justify-end gap-2">
            <button onclick="closeModal('{{ $id }}')" class="{{ $cancelClass }}">{{ $cancelText }}</button>
            @if ($onConfirm)
                <button onclick="({!! $onConfirm !!})(); closeModal('{{ $id }}')" class="{{ $confirmClass }}">
                    {{ $confirmText }}
                </button>
            @elseif ($formId)
                <button onclick="document.getElementById('{{ $formId }}').submit(); closeModal('{{ $id }}')" class="{{ $confirmClass }}">
                    {{ $confirmText }}
                </button>
            @endif
        </div>
    </div>
</div>

@once
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>
@endonce
