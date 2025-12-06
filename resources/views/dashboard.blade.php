<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SunnyDay Tasks</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .modal-bg {
            backdrop-filter: blur(4px);
            background-color: rgba(0, 0, 0, 0.3);
        }

        [x-cloak] {
            display: none !important;
        }

        /* Hides elements until Alpine loads */
    </style>
</head>

<body class="bg-yellow-50 min-h-screen text-gray-800 font-sans" x-data="taskApp({{ json_encode($categories) }})"
    x-cloak>

    <nav
        class="sticky top-0 z-40 bg-yellow-50/80 backdrop-blur-md border-b border-amber-100/50 shadow-sm transition-all duration-300">
        <div class="flex justify-between items-center max-w-7xl mx-auto px-6 py-4 md:px-10">

            <h1 class="text-3xl md:text-4xl font-extrabold text-amber-600 drop-shadow-sm flex items-center gap-3">
                <i class="fa-solid fa-sun"></i> <span class="hidden md:inline">SunnyDay</span>
            </h1>

            <div class="flex-1 max-w-md relative group mx-4">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i
                        class="fa-solid fa-magnifying-glass text-gray-400 group-focus-within:text-amber-500 transition"></i>
                </div>
                <input type="text" x-model="search" placeholder="Search tasks..."
                    class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-2xl leading-5 bg-white/50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-amber-400 focus:border-amber-400 sm:text-sm transition shadow-sm">
            </div>

            <div class="flex gap-3 items-center">
                <button @click="view = (view === 'grid' ? 'list' : 'grid')"
                    class="bg-white hover:bg-gray-100 text-gray-800 font-bold py-2 px-3 md:py-3 md:px-4 rounded-2xl shadow-lg transition transform hover:scale-105 border border-gray-100"
                    title="Switch View">
                    <i class="fa-solid text-amber-500" :class="view === 'grid' ? 'fa-list' : 'fa-border-all'"></i>
                </button>

                @auth
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit"
                            class="bg-white hover:bg-red-50 text-gray-800 hover:text-red-500 font-bold py-2 px-4 md:py-3 md:px-6 rounded-2xl shadow-lg transition transform hover:scale-105 flex items-center gap-2 border border-gray-100">
                            <i class="fa-solid fa-right-from-bracket"></i> <span class="hidden md:inline">Logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-white hover:bg-gray-100 text-gray-800 font-bold py-2 px-4 md:py-3 md:px-6 rounded-2xl shadow-lg transition transform hover:scale-105 flex items-center gap-2 border border-gray-100">
                        <i class="fa-solid fa-right-to-bracket"></i> <span class="hidden md:inline">Login</span>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

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

        <div x-show="search.length > 0" class="hidden text-center col-span-full py-10"
            :class="{'block': true, 'hidden': false}">
            <p class="text-xs text-gray-400">Filtering results for "<span x-text="search"></span>"...</p>
        </div>

        <div x-show="view === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($tasks as $task)
                <div x-data="{ title: '{{ addslashes($task->title) }}', desc: '{{ addslashes($task->description) }}' }"
                    x-show="title.toLowerCase().includes(search.toLowerCase()) || desc.toLowerCase().includes(search.toLowerCase())"
                    class="bg-white p-6 rounded-3xl shadow-lg border border-yellow-100 hover:shadow-xl transition flex flex-col justify-between h-full relative group">

                    <div class="absolute top-4 right-4">
                        @php $colors = ['high' => 'bg-red-100 text-red-800', 'medium' => 'bg-blue-100 text-blue-800', 'low' => 'bg-green-100 text-green-800']; @endphp
                        <span
                            class="{{ $colors[$task->priority] }} text-xs font-bold px-2 py-1 rounded-full">{{ ucfirst($task->priority) }}</span>
                    </div>

                    <div>
                        <h3 class="font-bold text-xl text-gray-800 mb-1">{{ $task->title }}</h3>
                        <p class="text-gray-500 text-sm mb-3">
                            <i class="fa-regular fa-folder text-amber-500"></i> {{ $task->category->name ?? 'None' }} &bull;
                            <i class="fa-regular fa-calendar"></i> {{ $task->due_date }}
                        </p>
                        <p class="text-gray-600 text-sm mb-4 leading-relaxed">{{ Str::limit($task->description, 80) }}</p>
                    </div>

                    <div class="pt-4 border-t border-gray-100 mt-auto">
                        <div class="flex items-center justify-between">
                            <span
                                class="text-sm font-semibold {{ $task->status == 'completed' ? 'text-green-600' : 'text-gray-500' }}">
                                {{ $task->status == 'completed' ? '✔ Completed' : '📝 Pending'}}
                            </span>
                            <div class="flex gap-2 opacity-100 lg:opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="openModal('edit', {{ $task->id }})"
                                    class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg"><i
                                        class="fa-solid fa-pen"></i></button>
                                <button @click="openModal('read', {{ $task->id }})"
                                    class="text-green-500 hover:bg-green-50 p-2 rounded-lg"><i
                                        class="fa-solid fa-eye"></i></button>
                                <button @click="openModal('delete', {{ $task->id }})"
                                    class="text-red-500 hover:bg-red-50 p-2 rounded-lg"><i
                                        class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div x-show="view === 'list'" class="flex flex-col gap-3">
            @foreach ($tasks as $task)
                <div x-data="{ title: '{{ addslashes($task->title) }}', desc: '{{ addslashes($task->description) }}' }"
                    x-show="title.toLowerCase().includes(search.toLowerCase()) || desc.toLowerCase().includes(search.toLowerCase())"
                    class="task-item bg-white p-4 rounded-2xl shadow-sm border border-yellow-100 hover:shadow-md transition flex flex-col md:flex-row md:items-center justify-between group gap-4">

                    <div class="flex items-start md:items-center gap-4 flex-1">

                        <div class="mt-1 md:mt-0 {{ $task->status == 'completed' ? 'text-green-500' : 'text-gray-300' }}">
                            <i class="fa-solid fa-circle-check text-xl"></i>
                        </div>

                        <div class="flex-1 min-w-0"> <h3 class="font-bold text-lg text-gray-800 leading-tight">{{ $task->title }}</h3>
                            <p class="task-desc text-sm text-gray-500 mt-1 truncate max-w-md">
                                {{ Str::limit($task->description, 60) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-sm flex-wrap md:flex-nowrap">

                        <div class="flex items-center gap-1 bg-gray-50 px-2 py-1 rounded-lg border border-gray-100 text-gray-600 whitespace-nowrap">
                            <i class="fa-regular fa-folder text-amber-500 text-xs"></i>
                            <span>{{ $task->category->name ?? 'None' }}</span>
                        </div>

                        <div class="flex items-center gap-1 text-gray-500 bg-gray-50 px-2 py-1 rounded-lg border border-gray-100 whitespace-nowrap">
                            <i class="fa-regular fa-calendar text-gray-400 text-xs"></i>
                            <span>{{ $task->due_date }}</span>
                        </div>

                        @php
                            $colors = [
                                'high' => 'bg-red-100 text-red-800',
                                'medium' => 'bg-blue-100 text-blue-800',
                                'low' => 'bg-green-100 text-green-800'
                            ];
                        @endphp
                        <span class="{{ $colors[$task->priority] }} text-xs font-bold px-2 py-1 rounded-full whitespace-nowrap uppercase">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>

                    <div class="flex gap-2 justify-end opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">
                        <button @click="openModal('edit', {{ $task->id }})" class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg" title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button @click="openModal('read', {{ $task->id }})" class="text-green-500 hover:bg-green-50 p-2 rounded-lg" title="View">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button @click="openModal('delete', {{ $task->id }})" class="text-red-500 hover:bg-red-50 p-2 rounded-lg" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>

                </div>
            @endforeach
        </div>

    </main>

    <button @click="openModal('create')"
        class="fixed bottom-10 right-10 w-16 h-16 bg-amber-500 hover:bg-amber-600 text-white rounded-full shadow-2xl transition transform hover:scale-110 z-40 flex items-center justify-center group border-4 border-white/30 backdrop-blur">
        <i class="fa-solid fa-plus text-2xl group-hover:rotate-90 transition-transform duration-300"></i>
    </button>

    <div x-show="modalOpen" style="display: none;"
        class="fixed inset-0 modal-bg flex items-center justify-center p-5 z-50" x-transition.opacity.duration.300ms>

        <div @click.outside="closeModal()"
            class="bg-white p-8 rounded-3xl shadow-2xl max-w-lg w-full border border-gray-100 relative max-h-[90vh] overflow-y-auto">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    <span x-show="modalType === 'create'"><i class="fa-solid fa-plus text-amber-500"></i> New
                        Task</span>
                    <span x-show="modalType === 'edit'"><i class="fa-solid fa-pen-to-square text-amber-500"></i> Edit
                        Task</span>
                    <span x-show="modalType === 'read'"><i class="fa-solid fa-eye text-amber-500"></i> Task
                        Details</span>
                    <span x-show="modalType === 'delete'"><i class="fa-solid fa-trash text-red-500"></i> Delete
                        Task</span>
                    <span x-show="modalType === 'category'">📦 Manage Categories</span>
                </h2>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600"><i
                        class="fa-solid fa-xmark text-xl"></i></button>
            </div>

            <div x-show="isLoading" class="text-center py-8">
                <i class="fa-solid fa-circle-notch fa-spin text-3xl text-amber-500"></i>
                <p class="text-gray-400 mt-2">Loading...</p>
            </div>

            <div x-show="!isLoading">

                <template x-if="modalType === 'create'">
                    <form action="{{ route('task.store') }}" method="post" class="space-y-4">
                        @csrf

                        <input type="text" name="title" placeholder="Task Title" required
                            class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 ring-amber-400 outline-none">
                        <textarea name="description" placeholder="Description..."
                            class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 ring-amber-400 outline-none h-24"></textarea>

                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Due
                                    Date</label><input type="date" name="due_date"
                                    class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none"></div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Category</label>
                                <div class="flex gap-2">
                                    <select name="category_id"
                                        class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none">
                                        <option value="" disabled selected>Select</option>
                                        <template x-for="cat in categories" :key="cat.id">
                                            <option :value="cat.id" x-text="cat.name"></option>
                                        </template>
                                    </select>
                                    <button type="button" @click="modalType = 'category'"
                                        class="bg-amber-100 hover:bg-amber-200 text-amber-700 px-3 rounded-xl border border-amber-200"><i
                                            class="fa-solid fa-plus"></i></button>
                                </div>
                            </div>
                            <div><select name="status"
                                    class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none">
                                    <option value="pending">📝 Pending</option>
                                    <option value="completed">✔ Completed</option>
                                </select></div>
                            <div><select name="priority"
                                    class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none">
                                    <option value="high">🔥 High</option>
                                    <option value="medium" selected>✨ Medium</option>
                                    <option value="low">🍃 Low</option>
                                </select></div>
                        </div>
                        <button type="submit"
                            class="w-full bg-gray-800 text-white text-lg font-bold py-3 rounded-xl hover:bg-gray-900 shadow-lg mt-2">Create
                            Task</button>
                    </form>
                </template>

                <template x-if="modalType === 'edit'">
                    <div class="space-y-4">
                        <input x-model="activeTask.title" type="text"
                            class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none">
                        <textarea x-model="activeTask.description"
                            class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none h-24"></textarea>

                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Due
                                    Date</label><input x-model="activeTask.due_date" type="date"
                                    class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none"></div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Category</label>
                                <select x-model="activeTask.category_id"
                                    class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none">
                                    <template x-for="cat in categories" :key="cat.id">
                                        <option :value="cat.id" x-text="cat.name"
                                            :selected="activeTask.category_id == cat.id"></option>
                                    </template>
                                </select>
                            </div>
                            <div><select x-model="activeTask.status"
                                    class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none">
                                    <option value="pending">📝 Pending</option>
                                    <option value="completed">✔ Completed</option>
                                </select></div>
                            <div><select x-model="activeTask.priority"
                                    class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none">
                                    <option value="high">🔥 High</option>
                                    <option value="medium">✨ Medium</option>
                                    <option value="low">🍃 Low</option>
                                </select></div>
                        </div>
                        <button @click="submitUpdate()"
                            class="w-full bg-amber-500 text-white py-3 rounded-xl mt-4 hover:bg-amber-600 font-bold shadow-md">Save
                            Changes</button>
                    </div>
                </template>

                <template x-if="modalType === 'read'">
                    <div class="space-y-4">
                        <div class="bg-yellow-50 p-6 rounded-2xl border border-amber-100">
                            <div class="border-b border-amber-200 pb-4">
                                <span
                                    class="bg-amber-100 text-amber-800 text-xs font-bold px-2 py-1 rounded-full mb-2 inline-block uppercase"
                                    x-text="activeTask.priority + ' Priority'"></span>
                                <h3 class="text-xl font-bold text-gray-800" x-text="activeTask.title"></h3>
                                <p class="text-sm text-gray-500 mt-1"><i class="fa-solid fa-calendar mr-1"></i> Due:
                                    <span x-text="activeTask.due_date"></span>
                                </p>
                            </div>
                            <div class="mt-4">
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-bold mb-1">Description</p>
                                <p class="text-gray-700 leading-relaxed"
                                    x-text="activeTask.description || 'No description'"></p>
                            </div>
                        </div>
                        <button @click="closeModal()"
                            class="w-full bg-white border border-gray-200 text-gray-700 py-3 rounded-xl mt-2 hover:bg-gray-50 font-bold">Close</button>
                    </div>
                </template>

                <template x-if="modalType === 'delete'">
                    <div class="text-center">
                        <div class="bg-red-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"><i
                                class="fa-solid fa-triangle-exclamation text-2xl text-red-500"></i></div>
                        <p class="text-gray-800 font-bold text-lg">Delete Task?</p>
                        <p class="text-gray-500 text-sm mt-2">Are you sure you want to delete this task?<br>This cannot
                            be undone.</p>
                        <div class="flex gap-3 mt-4">
                            <button @click="closeModal()"
                                class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-xl hover:bg-gray-200 font-bold">Cancel</button>
                            <button @click="submitDelete()"
                                class="flex-1 bg-red-500 text-white py-3 rounded-xl hover:bg-red-600 font-bold shadow-md">Yes,
                                Delete</button>
                        </div>
                    </div>
                </template>

                <template x-if="modalType === 'category'">
                    <div>
                        <div class="flex gap-2 mb-4">
                            <input type="text"
                                x-model="newCategoryName"
                                @keydown.enter.prevent="createCategory()"
                                placeholder="New Category Name"
                                class="flex-1 p-3 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 ring-amber-400 outline-none">

                            <button @click="createCategory()"
                                    class="bg-amber-500 text-white px-4 rounded-xl hover:bg-amber-600 shadow-md transition transform active:scale-95">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>

                        <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
                            <template x-for="cat in categories" :key="cat.id">
                                <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100 hover:border-amber-200 transition">
                                    <span class="font-semibold text-gray-700" x-text="cat.name"></span>

                                    <button @click="confirmCategoryDelete(cat.id)" class="text-gray-400 hover:text-red-500 w-8 h-8 rounded-full hover:bg-red-50 transition">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </template>

                            <div x-show="categories.length === 0" class="text-center text-gray-400 py-4 text-sm">
                                No categories found. Add one above!
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="modalType === 'deleteCategory'">
                    <div class="text-center">
                        <div class="bg-red-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-triangle-exclamation text-2xl text-red-500"></i>
                        </div>

                        <h3 class="text-xl font-bold text-gray-800 mb-2">Delete Category?</h3>

                        <p class="text-gray-500 text-sm mb-6">
                            Are you sure? Tasks using this category will become "Uncategorized".
                        </p>

                        <div class="flex gap-3">
                            <button @click="modalType = 'category'; categoryToDelete = null"
                                    class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-xl hover:bg-gray-200 font-bold transition">
                                Cancel
                            </button>

                            <button @click="submitCategoryDelete()"
                                    class="flex-1 bg-red-500 text-white py-3 rounded-xl hover:bg-red-600 font-bold shadow-md transition transform active:scale-95">
                                Yes, Delete
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('taskApp', (initialCategories) => ({
                // STATE
                view: 'grid',
                search: '',
                modalOpen: false,
                modalType: '',
                isLoading: false,
                categories: initialCategories,
                newCategoryName: '',
                categoryToDelete: null,
                csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

                // ACTIVE TASK HOLDER
                activeTask: { id: null, title: '', description: '', due_date: '', status: '', priority: '', category_id: '' },

                //Category Creation
                async createCategory() {
                    if (!this.newCategoryName.trim()) return; // Don't submit empty strings

                    try {
                        const response = await fetch('/categories', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken
                            },
                            body: JSON.stringify({ name: this.newCategoryName })
                        });

                        if (!response.ok) throw new Error('Failed to create');

                        const newCat = await response.json();

                        // Add to the local list immediately (No page reload needed!)
                        this.categories.push(newCat);

                        // Clear input
                        this.newCategoryName = '';
                    } catch (e) {
                        alert('Could not add category. It might already exist.');
                    }
                },

                confirmCategoryDelete(id){
                    this.categoryToDelete = id;
                    this.modalType = 'deleteCategory';
                },

                //Category Delete
                async submitCategoryDelete(id) {
                    if (!this.categoryToDelete) return;

                    try {
                        const response = await fetch(`/categories/${this.categoryToDelete}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken
                            }
                        });

                        // 1. CHECK FOR SERVER ERRORS
                        if (!response.ok) {
                            throw new Error('Server Failed');
                        }

                        // 2. ONLY UPDATE UI IF SERVER SUCCESS
                        this.categories = this.categories.filter(cat => cat.id !== this.categoryToDelete);

                        // 3. RESET MODAL
                        this.categoryToDelete = null;
                        this.modalType = 'category';

                    } catch (e) {
                        console.error(e);
                        alert('Failed to delete category. It might be in use or you might not have permission.');
                    }
                },

                // METHODS
                async openModal(type, taskId = null) {
                    this.modalType = type;
                    this.modalOpen = true;

                    if (type === 'create' || type === 'category') {
                        this.isLoading = false;
                        return;
                    }

                    // For Edit/Read/Delete, we handle data
                    if (type === 'delete') {
                        // Instant load for delete (don't fetch full data, just store ID)
                        this.activeTask.id = taskId;
                        this.isLoading = false;
                        return;
                    }

                    // For Read/Edit, fetch data
                    this.isLoading = true;
                    try {
                        const response = await fetch(`/tasks/${taskId}`);
                        this.activeTask = await response.json();
                    } catch (e) {
                        alert('Error loading task');
                    } finally {
                        this.isLoading = false;
                    }
                },

                closeModal() {
                    this.modalOpen = false;
                    setTimeout(() => {
                        this.activeTask = { id: null, title: '', description: '', due_date: '', status: '', priority: '', category_id: '' };
                        this.newCategoryName = '';
                    }, 300);
                },

                async submitUpdate() {
                    await fetch(`/tasks/${this.activeTask.id}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken },
                        body: JSON.stringify(this.activeTask)
                    });
                    location.reload();
                },

                async submitDelete() {
                    await fetch(`/tasks/${this.activeTask.id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': this.csrfToken }
                    });
                    location.reload();
                }
            }));
        });
    </script>
</body>

</html>
