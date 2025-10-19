@extends('admin.layouts.dashboard')

@section('content')
    <div class="form">
        <div class="mb-6 flex items-center justify-between">
            <x-heading-section title="User Management" subtitle="Manage all user accounts and permissions" />
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.content.createusers') }}"
                    class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add New User
                </a>
            </div>
        </div>

        {{-- Search and Filter Section --}}
        <div class="mb-6 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.content.listusers') }}" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Search by name or email..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select id="role" name="role"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit"
                            class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Filter
                        </button>
                        <a href="{{ route('admin.content.listusers') }}"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition class="success-toast">
                {{ session('success') }}
            </div>
        @endif

        {{-- Bulk Actions --}}
        <div class="mb-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <button type="button" id="select-all" class="text-sm text-gray-600 hover:text-gray-900">
                    Select All
                </button>
                <button type="button" id="bulk-delete" class="text-sm text-red-600 hover:text-red-900" style="display: none;">
                    Delete Selected
                </button>
            </div>
            <div class="text-sm text-gray-600">
                Total: {{ $users->total() }} users
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-md">
            <form id="bulk-form" action="{{ route('admin.content.bulkdeleteusers') }}" method="POST">
                @csrf
                <table class="table-next">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="th-next w-4">
                                <input type="checkbox" id="select-all-checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="th-next">User</th>
                            <th class="th-next">Role</th>
                            <th class="th-next">Status</th>
                            <th class="th-next">Email</th>
                            <th class="th-next">Bio</th>
                            <th class="th-next">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="tbody-next">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="td-next">
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>

                                <td class="td-next">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $user->profil ? asset($user->profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                            class="h-10 w-10 rounded-full object-cover border border-slate-200 shadow-sm"
                                            alt="Profile"
                                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF'">
                                        <div>
                                            <div class="font-semibold text-slate-800">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="td-next">
                                    @if(($user->role->value ?? 'user') === 'admin')
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                                            </svg>
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                            User
                                        </span>
                                    @endif
                                </td>

                                <td class="td-next">
                                    @if($user->is_active ?? true)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
                                            <svg class="mr-1 h-2 w-2 fill-current" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">
                                            <svg class="mr-1 h-2 w-2 fill-current" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="td-next text-sm text-gray-500">{{ $user->email }}</td>

                                <td class="td-next text-sm text-gray-500">{{ $user->bio ?? 'No bio' }}</td>

                                <td class="td-next">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.content.showusers', $user->id) }}"
                                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-2 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                            View
                                        </a>
                                        <a href="{{ route('admin.content.editusers', $user->id) }}"
                                            class="inline-flex items-center rounded-md border border-blue-500 bg-white px-2 py-1 text-xs font-medium text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.content.togglestatususers', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center rounded-md border border-yellow-500 bg-white px-2 py-1 text-xs font-medium text-yellow-600 hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                                                @if($user->is_active ?? true)
                                                    Deactivate
                                                @else
                                                    Activate
                                                @endif
                                            </button>
                                        </form>
                                        <button type="button"
                                            onclick="openModal('modal-{{ $user->id }}')"
                                            class="inline-flex items-center rounded-md border border-red-500 bg-white px-2 py-1 text-xs font-medium text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
                                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new user.</p>
                                        <div class="mt-6">
                                            <a href="{{ route('admin.content.createusers') }}"
                                                class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                                </svg>
                                                New User
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="mt-6">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    {{-- Individual Delete Modals --}}
    @foreach ($users as $user)
        <x-popup id="modal-{{ $user->id }}" title="Delete User"
            message="Are you sure want to delete this user? All data will be permanently removed."
            formId="delete-form-{{ $user->id }}"
            confirmText="Delete"
            confirmClass="text-sm link-primary delete-btn-color"
            cancelText="Cancel" />
        
        <form id="delete-form-{{ $user->id }}" action="{{ route('admin.content.deleteusers', $user->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    {{-- Bulk Delete Modal --}}
    <x-popup id="bulk-delete-modal" title="Delete Selected Users"
        message="Are you sure you want to delete the selected users? All data will be permanently removed and cannot be recovered."
        formId="bulk-form"
        confirmText="Delete Selected"
        confirmClass="text-sm link-primary delete-btn-color"
        cancelText="Cancel" />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all-checkbox');
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            const selectAllButton = document.getElementById('select-all');
            const bulkDeleteButton = document.getElementById('bulk-delete');

            // Select all functionality
            selectAllCheckbox.addEventListener('change', function() {
                userCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                toggleBulkActions();
            });

            // Individual checkbox change
            userCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                    selectAllCheckbox.checked = checkedBoxes.length === userCheckboxes.length;
                    selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < userCheckboxes.length;
                    toggleBulkActions();
                });
            });

            // Select all button
            selectAllButton.addEventListener('click', function() {
                const allChecked = document.querySelectorAll('.user-checkbox:checked').length === userCheckboxes.length;
                userCheckboxes.forEach(checkbox => {
                    checkbox.checked = !allChecked;
                });
                selectAllCheckbox.checked = !allChecked;
                selectAllCheckbox.indeterminate = false;
                toggleBulkActions();
            });

            // Bulk delete button
            bulkDeleteButton.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                if (checkedBoxes.length > 0) {
                    openModal('bulk-delete-modal');
                }
            });

            function toggleBulkActions() {
                const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                if (checkedBoxes.length > 0) {
                    bulkDeleteButton.style.display = 'inline-block';
                    selectAllButton.textContent = 'Deselect All';
                } else {
                    bulkDeleteButton.style.display = 'none';
                    selectAllButton.textContent = 'Select All';
                }
            }
        });
    </script>
@endpush
