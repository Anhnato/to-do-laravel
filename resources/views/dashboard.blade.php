<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SunnyDay Tasks</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .modal-bg {
            backdrop-filter: blur(4px);
            background-color: rgba(0, 0, 0, 0.3);
        }

        /* Removed the #addTaskFormContainer styles as they are no longer needed */
    </style>
</head>

<body class="bg-yellow-50 min-h-screen p-6 md:p-10 text-gray-800 font-sans">

    <header class="flex justify-between items-center max-w-7xl mx-auto mb-10">
        <h1 class="text-4xl font-extrabold text-amber-600 drop-shadow-sm flex items-center gap-3">
            <i class="fa-solid fa-sun"></i> SunnyDay Tasks
        </h1>

        <div class="flex gap-3">
            <button onclick="openModal('create')"
                class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-6 rounded-2xl shadow-lg transition transform hover:scale-105 flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> <span>New Task</span>
            </button>

            <a href="{{ route('login') }}"
                class="bg-white hover:bg-gray-100 text-gray-800 font-bold py-3 px-6 rounded-2xl shadow-lg transition transform hover:scale-105 flex items-center gap-2 border border-gray-100">
                <i class="fa-solid fa-right-to-bracket"></i> Login
            </a>
        </div>
    </header>

    <div class="max-w-7xl mx-auto mb-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-4"
                role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-6" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <span class="block sm:inline">Something went wrong:</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($tasks as $task)
                <div
                    class="bg-white p-6 rounded-3xl shadow-lg border border-yellow-100 hover:shadow-xl transition flex flex-col justify-between h-full relative group">
                    <div class="absolute top-4 right-4">
                        @php
                            $colors = [
                                'high' => 'bg-red-100 text-red-800',
                                'medium' => 'bg-blue-100 text-blue-800',
                                'low' => 'bg-green-100 text-green-800',
                            ];
                        @endphp
                        <span class="{{ $colors[$task->priority] }} text-xs font-bold px-2 py-1 rounded-full">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>

                    <div>
                        <h3 class="font-bold text-xl text-gray-800 mb-1">{{ $task->title }}</h3>
                        <p class="text-gray-500 text-sm mb-3">
                            <i class="fa-regular fa-folder text-amber-500"></i>
                            {{ $task->category->name ?? 'None' }} &bull;
                            <i class="fa-regular fa-calendar"></i>
                            {{ $task->due_date }}
                        </p>
                        <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                            {{ Str::limit($task->description, 80) }}
                        </p>
                    </div>
                    <div class="pt-4 border-t border-gray-100 mt-auto">
                        <div class="flex items-center justify-between">
                            <span
                                class="text-sm font-semibold {{ $task->status == 'completed' ? 'text-green-600' : 'text-gray-500' }}">
                                {{ $task->status == 'completed' ? '✔ Completed' : '📝 Pending'}}
                            </span>
                            <div class="flex gap-2 opacity-100 lg:opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="openModal('edit', {{ $task->id }})"
                                    class="text-blue-500 hover:text-blue-700 bg-blue-50 p-2 rounded-lg">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button onclick="openModal('read', {{ $task->id }})"
                                    class="text-green-500 hover:text-green-700 bg-green-50 p-2 rounded-lg">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button onclick="openModal('delete', {{ $task->id }})"
                                    class="text-red-500 hover:text-red-700 bg-red-50 p-2 rounded-lg">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div id="modal" class="hidden fixed inset-0 modal-bg flex items-center justify-center p-5 z-50">
        <div
            class="bg-white p-8 rounded-3xl shadow-2xl max-w-lg w-full border border-gray-100 relative transform transition-all scale-100">
            <div class="flex justify-between items-center mb-6">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-800"></h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div id="modalContent" class="space-y-4"></div>
        </div>
    </div>

    <script>
        const availableCategories = @json($categories);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function closeModal() {
            document.getElementById("modal").classList.add("hidden");
        }

        async function openModal(type, taskId = null) {
            const modal = document.getElementById("modal");
            const title = document.getElementById("modalTitle");
            const content = document.getElementById("modalContent");

            // Show the modal
            modal.classList.remove("hidden");

            // Default loading state for async operations
            if (type !== 'create' && type !== 'category') {
                content.innerHTML = '<p class="text-center p-4">Loading...</p>';
            }

            // --- 0. CREATE MODAL (NEW) ---
            if (type === "create") {
                title.innerHTML = '<i class="fa-solid fa-plus text-amber-500"></i> New Task';

                // Build Category Options for the Create Form
                let categoryOptions = '<option value="" disabled selected>Select Category</option>';
                availableCategories.forEach(cat => {
                    categoryOptions += `<option value="${cat.id}">${cat.name}</option>`;
                });

                // IMPORTANT: We inject a standard HTML form here.
                // We add the hidden input for _token so Laravel accepts the POST request.
                content.innerHTML = `
                    <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4">
                        <input type="hidden" name="_token" value="${csrfToken}">

                        <input type="text" name="title" placeholder="Task Title" required class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 ring-amber-400 outline-none">

                        <textarea name="description" placeholder="Description..." class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 ring-amber-400 outline-none h-24"></textarea>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Due Date</label>
                                <input type="date" name="due_date" class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Category</label>
                                <div class="flex gap-2">
                                    <select name="category_id" class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none">
                                        ${categoryOptions}
                                    </select>
                                    <button type="button" onclick="openModal('category')" class="bg-amber-100 hover:bg-amber-200 text-amber-700 px-3 rounded-xl border border-amber-200">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <select name="status" required class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none">
                                    <option value="" disabled selected>Status</option>
                                    <option value="pending">📝 Pending</option>
                                    <option value="completed">✔ Completed</option>
                                </select>
                            </div>

                            <div>
                                <select name="priority" required class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none">
                                    <option value="" disabled selected>Priority</option>
                                    <option value="high">🔥 High</option>
                                    <option value="medium">✨ Medium</option>
                                    <option value="low">🍃 Low</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-gray-800 text-white text-lg font-bold py-3 rounded-xl hover:bg-gray-900 shadow-lg transition mt-2">
                            Create Task
                        </button>
                    </form>
                `;
            }

            // --- 1. EDIT MODAL ---
            if (type === "edit") {
                const response = await fetch(`/tasks/${taskId}`);
                const task = await response.json();

                let categoryOptions = '<option value="" disabled>Select Category</option>';
                availableCategories.forEach(cat => {
                    const isSelected = (task.category_id === cat.id) ? 'selected' : '';
                    categoryOptions += `<option value="${cat.id}" ${isSelected}>${cat.name}</option>`;
                });

                title.innerHTML = '<i class="fa-solid fa-pen-to-square text-amber-500"></i> Edit Task';
                content.innerHTML = `
                    <div class="space-y-4">
                        <input id="editTitle" type="text" value="${task.title}" class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none">
                        <textarea id="editDesc" class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none h-24">${task.description || ''}</textarea>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Due Date</label>
                                <input id="editDate" type="date" value="${task.due_date}" class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Category</label>
                                <select id="editCategory" class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none">
                                    ${categoryOptions}
                                </select>
                            </div>

                            <div>
                                <select id="editStatus" class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none">
                                    <option value="pending" ${task.status === 'pending' ? 'selected' : ''}>📝 Pending</option>
                                    <option value="completed" ${task.status === 'completed' ? 'selected' : ''}>✔ Completed</option>
                                </select>
                            </div>

                            <div>
                                <select id="editPriority" class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none">
                                    <option value="high" ${task.priority === 'high' ? 'selected' : ''}>🔥 High</option>
                                    <option value="medium" ${task.priority === 'medium' ? 'selected' : ''}>✨ Medium</option>
                                    <option value="low" ${task.priority === 'low' ? 'selected' : ''}>🍃 Low</option>
                                </select>
                            </div>
                        </div>

                        <button onclick="updateTask(${taskId})" class="w-full bg-amber-500 text-white py-3 rounded-xl mt-4 hover:bg-amber-600 font-bold shadow-md">
                            Save Changes
                        </button>
                    </div>
                `;
            }

            // --- 2. VIEW MODAL ---
            if (type === "read") {
                const response = await fetch(`/tasks/${taskId}`);
                const task = await response.json();

                title.innerHTML = '<i class="fa-solid fa-eye text-amber-500"></i> Task Details';
                content.innerHTML = `
                    <div class="bg-yellow-50 p-6 rounded-2xl border border-amber-100 space-y-4">
                        <div class="border-b border-amber-200 pb-4">
                            <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2 py-1 rounded-full mb-2 inline-block uppercase">${task.priority} Priority</span>
                            <h3 class="text-xl font-bold text-gray-800">${task.title}</h3>
                            <p class="text-sm text-gray-500 mt-1"><i class="fa-solid fa-calendar mr-1"></i> Due: ${task.due_date}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-bold mb-1">Description</p>
                            <p class="text-gray-700 leading-relaxed">${task.description || 'No description provided.'}</p>
                        </div>

                        <div class="flex gap-4 pt-2">
                            <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-amber-100 shadow-sm">
                                <i class="fa-solid fa-folder text-amber-500"></i>
                                <span class="text-sm font-semibold text-gray-700">${task.category ? task.category.name : 'Uncategorized'}</span>
                            </div>
                            <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-amber-100 shadow-sm">
                                <i class="fa-solid fa-list-check text-amber-500"></i>
                                <span class="text-sm font-semibold text-gray-700">${task.status}</span>
                            </div>
                        </div>
                    </div>
                    <button onclick="closeModal()" class="w-full bg-white border border-gray-200 text-gray-700 py-3 rounded-xl mt-2 hover:bg-gray-50 font-bold transition">
                        Close
                    </button>
                `;
            }

            // --- 3. DELETE MODAL ---
            if (type === "delete") {
                title.innerHTML = '<i class="fa-solid fa-trash text-red-500"></i> Delete Task';
                content.innerHTML = `
                    <div class="text-center py-4">
                        <div class="bg-red-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-triangle-exclamation text-2xl text-red-500"></i>
                        </div>
                        <p class="text-gray-800 font-bold text-lg">Are you sure?</p>
                        <p class="text-gray-500 text-sm mt-2">Do you really want to delete this task?
                            <br>This process cannot be undone.</p>
                    </div>
                    <div class="flex gap-3 mt-4">
                        <button onclick="closeModal()" class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-xl hover:bg-gray-200 font-bold transition">
                            Cancel
                        </button>
                        <button onclick="deleteTask(${taskId})" class="flex-1 bg-red-500 text-white py-3 rounded-xl hover:bg-red-600 font-bold shadow-md transition transform active:scale-95">
                            Yes, Delete
                        </button>
                    </div>
                `;
            }

            // --- 4. CATEGORY MODAL ---
            if (type === "category") {
                title.innerHTML = '📦 Manage Categories';
                content.innerHTML = `
                    <div class="flex gap-2 mb-4">
                        <input type="text" placeholder="New Category Name" class="flex-1 p-3 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 ring-amber-400 outline-none">
                        <button class="bg-amber-500 text-white px-4 rounded-xl hover:bg-amber-600 shadow-md">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                    <div class="space-y-2 max-h-60 overflow-y-auto">
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <span class="font-semibold text-gray-700">Work</span>
                            <button class="text-red-400 hover:text-red-600"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                `;
            }
        }

        // --- AJAX FUNCTIONS ---

        async function updateTask(id) {
            const data = {
                title: document.getElementById('editTitle').value,
                description: document.getElementById('editDesc').value,
                due_date: document.getElementById('editDate').value,
                status: document.getElementById('editStatus').value,
                priority: document.getElementById('editPriority').value,
                category_id: document.getElementById('editCategory').value,
            };

            await fetch(`/tasks/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            });

            location.reload();
        }

        async function deleteTask(id) {
            await fetch(`/tasks/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });
            location.reload();
        }

        // Close on click outside
        document.getElementById("modal").addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });
    </script>
</body>

</html>
