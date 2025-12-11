@extends('layouts.app')

@section('alpine-data')
    taskApp({{ json_encode($categories) }}, {{ json_encode($tasks) }})
@endsection

@section('content')
    <main class="max-w-7xl mx-auto p-6 md:p-10 pb-24">

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-6">
                <strong class="font-bold">Success!</strong> <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-6">
                <strong class="font-bold">Whoops!</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div x-show="search.length > 0 && filteredTasks.length === 0" class="text-center col-span-full py-10">
            <i class="fa-solid fa-ghost text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 text-lg">No tasks found.</p>
        </div>

        <div x-show="view === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="task in filteredTasks" :key="task.id">
                <div
                    class="bg-white p-6 rounded-3xl shadow-lg border border-yellow-100 hover:shadow-xl transition flex flex-col justify-between h-full relative group">
                    <div class="absolute top-4 right-4">
                        <span :class="priorityColors[task.priority]"
                            class="text-xs font-bold px-2 py-1 rounded-full capitalize" x-text="task.priority"></span>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-gray-800 mb-1 truncate" :title="task.title" x-text="task.title">
                        </h3>
                        <p class="text-gray-500 text-sm mb-3">
                            <i class="fa-regular fa-folder text-amber-500"></i> <span
                                x-text="getCategoryName(task.category_id)"></span> &bull;
                            <i class="fa-regular fa-calendar"></i> <span x-text="formatDate(task.due_date)"></span>
                        </p>
                        <p class="text-gray-600 text-sm mb-4 leading-relaxed line-clamp-3" x-text="task.description"></p>
                    </div>
                    <div class="pt-4 border-t border-gray-100 mt-auto">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold"
                                :class="task.status === 'completed' ? 'text-green-600' : 'text-gray-500'"
                                x-text="task.status === 'completed' ? '✔ Completed' : '📝 Pending'"></span>
                            <div class="flex gap-2 opacity-100 lg:opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="openModal('edit', task.id)"
                                    class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg"><i
                                        class="fa-solid fa-pen"></i></button>
                                <button @click="openModal('read', task.id)"
                                    class="text-green-500 hover:bg-green-50 p-2 rounded-lg"><i
                                        class="fa-solid fa-eye"></i></button>
                                <button @click="openModal('delete', task.id)"
                                    class="text-red-500 hover:bg-red-50 p-2 rounded-lg"><i
                                        class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="view === 'list'" class="flex flex-col gap-3">
            <template x-for="task in filteredTasks" :key="task.id">
                <div
                    class="bg-white p-4 rounded-2xl shadow-sm border border-yellow-100 hover:shadow-md transition flex flex-col md:flex-row md:items-center justify-between group gap-4">
                    <div class="flex items-start md:items-center gap-4 flex-1 min-w-0">
                        <div class="mt-1 md:mt-0 shrink-0"
                            :class="task.status === 'completed' ? 'text-green-500' : 'text-gray-300'">
                            <i class="fa-solid fa-circle-check text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-lg text-gray-800 leading-tight truncate" :title="task.title"
                                x-text="task.title"></h3>
                            <p class="text-sm text-gray-500 mt-1 truncate" :title="task.description"
                                x-text="task.description"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-sm flex-wrap md:flex-nowrap shrink-0">
                        <div
                            class="flex items-center gap-1 bg-gray-50 px-2 py-1 rounded-lg border border-gray-100 text-gray-600 whitespace-nowrap">
                            <i class="fa-regular fa-folder text-amber-500 text-xs"></i>
                            <span x-text="getCategoryName(task.category_id)"></span>
                        </div>
                        <div
                            class="flex items-center gap-1 text-gray-500 bg-gray-50 px-2 py-1 rounded-lg border border-gray-100 whitespace-nowrap">
                            <i class="fa-regular fa-calendar text-gray-400 text-xs"></i>
                            <span x-text="formatDate(task.due_date)"></span>
                        </div>
                        <span :class="priorityColors[task.priority]"
                            class="text-xs font-bold px-2 py-1 rounded-full whitespace-nowrap capitalize"
                            x-text="task.priority"></span>
                    </div>
                    <div
                        class="flex gap-2 justify-end opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity shrink-0">
                        <button @click="openModal('edit', task.id)" class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg"><i
                                class="fa-solid fa-pen"></i></button>
                        <button @click="openModal('read', task.id)"
                            class="text-green-500 hover:bg-green-50 p-2 rounded-lg"><i class="fa-solid fa-eye"></i></button>
                        <button @click="openModal('delete', task.id)" class="text-red-500 hover:bg-red-50 p-2 rounded-lg"><i
                                class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            </template>
        </div>
    </main>

    <button @click="openModal('create')"
        class="fixed bottom-10 right-10 w-16 h-16 bg-amber-500 hover:bg-amber-600 text-white rounded-full shadow-2xl transition transform hover:scale-110 z-40 flex items-center justify-center group border-4 border-white/30 backdrop-blur">
        <i class="fa-solid fa-plus text-2xl group-hover:rotate-90 transition-transform duration-300"></i>
    </button>

    <div x-show="modalOpen" style="display: none;" class="fixed inset-0 modal-bg flex items-center justify-center p-5 z-50"
        x-transition.opacity.duration.300ms>
        <div @click.outside="closeModal()"
            class="bg-white p-8 rounded-3xl shadow-2xl max-w-lg w-full border border-gray-100 relative max-h-[90vh] overflow-y-auto">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    <span x-show="modalType === 'create'"><i class="fa-solid fa-plus text-amber-500"></i> New Task</span>
                    <span x-show="modalType === 'edit'"><i class="fa-solid fa-pen-to-square text-amber-500"></i> Edit
                        Task</span>
                    <span x-show="modalType === 'read'"><i class="fa-solid fa-eye text-amber-500"></i> Task Details</span>
                    <span x-show="modalType === 'delete'"><i class="fa-solid fa-trash text-red-500"></i> Delete Task</span>
                    <span x-show="modalType === 'alert'">📦 Manage Categories</span>
                </h2>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600"><i
                        class="fa-solid fa-xmark text-xl"></i></button>
            </div>

            <div x-show="isLoading" class="text-center py-8">
                <i class="fa-solid fa-circle-notch fa-spin text-3xl text-amber-500"></i>
                <p class="text-gray-400 mt-2">Loading...</p>
            </div>

            <div x-show="!isLoading">
                @include('partials.modal-templates')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('taskApp', (initialCategories, initialTasks) => ({
                view: 'grid',
                search: '',
                modalOpen: false,
                modalType: '',
                isLoading: false,
                categories: initialCategories,
                tasks: initialTasks,
                newCategoryName: '',
                categoryToDelete: null,
                isCreatingCategory: false,
                alertMessage: '',
                csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

                activeTask: { id: null, title: '', description: '', due_date: '', status: '', priority: '', category_id: '' },

                priorityColors: {
                    high: 'bg-red-100 text-red-800',
                    medium: 'bg-blue-100 text-blue-800',
                    low: 'bg-green-100 text-green-800'
                },

                get filteredTasks() {
                    if (this.search === '') return this.tasks;
                    const lowerSearch = this.search.toLowerCase();
                    return this.tasks.filter(task => {
                        const title = task.title ? task.title.toLowerCase() : '';
                        const desc = task.description ? task.description.toLowerCase() : '';
                        return title.includes(lowerSearch) || desc.includes(lowerSearch);
                    });
                },

                getCategoryName(id) {
                    const cat = this.categories.find(c => c.id === id);
                    return cat ? cat.name : 'Uncategorized';
                },

                formatDate(dateString) {
                    if (!dateString) return 'No due date';
                    const date = new Date(dateString);
                    if (isNaN(date.getTime())) return 'No due date';
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                },

                async openModal(type, taskId = null) {
                    this.modalType = type;
                    this.modalOpen = true;
                    if (type === 'create' || type === 'category') { this.isLoading = false; return; }
                    if (type === 'delete') { this.activeTask.id = taskId; this.isLoading = false; return; }
                    this.isLoading = true;
                    try {
                        const response = await fetch(`/tasks/${taskId}`);
                        this.activeTask = await response.json();
                    } catch (e) { alert('Error loading task'); } finally { this.isLoading = false; }
                },
                closeModal() {
                    this.modalOpen = false;
                    setTimeout(() => { this.activeTask = { id: null, title: '', description: '', due_date: '', status: '', priority: '', category_id: '' }; this.newCategoryName = ''; }, 300);
                },
                showError(message) {
                    this.alertMessage = message;
                    this.modalType = 'alert';
                },
                async createCategory() {
                    if (!this.newCategoryName.trim()) return;
                    if (this.isCreatingCategory) return; // Stop double clicks

                    this.isCreatingCategory = true; // Lock button

                    try {
                        const response = await fetch('/categories', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken
                            },
                            body: JSON.stringify({ name: this.newCategoryName })
                        });

                        if (!response.ok) {
                            // Handle validation error (422) specifically
                            if (response.status === 422) throw new Error('Duplicate');
                            throw new Error('Failed');
                        }

                        const newCat = await response.json();
                        this.categories.push(newCat);
                        this.newCategoryName = '';

                    } catch (e) {
                        if (e.message === 'Duplicate') {
                            this.showError('You already have a category with this name!');
                        } else {
                            this.showError('Could not add category.');
                        }
                    } finally {
                        this.isCreatingCategory = false; // Unlock button
                    }
                },
                confirmCategoryDelete(id) { this.categoryToDelete = id; this.modalType = 'deleteCategory'; },
                async submitCategoryDelete() {
                    if (!this.categoryToDelete) return;
                    try {
                        const response = await fetch(`/categories/${this.categoryToDelete}`, { method: 'DELETE', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken } });
                        if (!response.ok) throw new Error('Server Failed');
                        this.categories = this.categories.filter(cat => cat.id !== this.categoryToDelete);
                        this.categoryToDelete = null; this.modalType = 'category';
                    } catch (e) { alert('Failed to delete category.'); }
                },
                async submitUpdate() {
                    await fetch(`/tasks/${this.activeTask.id}`, { method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken }, body: JSON.stringify(this.activeTask) });
                    location.reload();
                },
                async submitDelete() {
                    await fetch(`/tasks/${this.activeTask.id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': this.csrfToken } });
                    location.reload();
                },
            }));
        });
    </script>
@endpush