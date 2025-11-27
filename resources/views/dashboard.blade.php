<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SunnyDay Tasks</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .modal-bg {
            backdrop-filter: blur(4px);
            background-color: rgba(0, 0, 0, 0.3);
        }

        #addTaskFormContainer {
            transition: max-height 0.5s ease-in-out, opacity 0.5s ease-in-out;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
        }

        #addTaskFormContainer.expanded {
            max-height: 1000px;
            opacity: 1;
        }
    </style>
</head>

<body class="bg-yellow-50 min-h-screen p-6 md:p-10 text-gray-800 font-sans">

    <header class="flex justify-between items-center max-w-7xl mx-auto mb-10">
        <h1 class="text-4xl font-extrabold text-amber-600 drop-shadow-sm flex items-center gap-3">
            <i class="fa-solid fa-sun"></i> SunnyDay Tasks
        </h1>

        <div class="flex gap-3">
            <button onclick="toggleAddTask()"
                class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-6 rounded-2xl shadow-lg transition transform hover:scale-105 flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> <span id="toggleBtnText">New Task</span>
            </button>

            <a href="{{ route('login') }}"
                class="bg-white hover:bg-gray-100 text-gray-800 font-bold py-3 px-6 rounded-2xl shadow-lg transition transform hover:scale-105 flex items-center gap-2 border border-gray-100">
                <i class="fa-solid fa-right-to-bracket"></i> Login
            </a>
        </div>
    </header>

    <div class="max-w-7xl mx-auto">

        <div id="addTaskFormContainer" class="mb-8">
            <div class="bg-white rounded-3xl p-8 border border-amber-100 shadow-xl">
                <h2 class="text-2xl font-bold text-gray-700 mb-6">📝 Draft New Task</h2>

                <form class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" placeholder="Task Title"
                        class="col-span-2 p-4 rounded-xl bg-yellow-50/50 border border-yellow-200 focus:bg-white focus:ring-2 ring-amber-400 outline-none transition">
                    <textarea placeholder="Description..."
                        class="col-span-2 p-4 rounded-xl bg-yellow-50/50 border border-yellow-200 focus:bg-white focus:ring-2 ring-amber-400 outline-none h-28 transition"></textarea>
                    <input type="date"
                        class="p-4 rounded-xl bg-yellow-50/50 border border-yellow-200 focus:bg-white focus:ring-2 ring-amber-400 outline-none transition">

                    <div class="flex gap-2">
                        <select
                            class="flex-1 p-4 rounded-xl bg-yellow-50/50 border border-yellow-200 focus:bg-white focus:ring-2 ring-amber-400 outline-none transition">
                            <option disabled selected>Select Category</option>
                            <option>Work</option>
                            <option>School</option>
                            <option>Personal</option>
                        </select>
                        <button type="button" onclick="openModal('category')"
                            class="bg-amber-100 hover:bg-amber-200 text-amber-700 p-4 rounded-xl border border-amber-200 transition shadow-sm">
                            <i class="fa-solid fa-folder-plus"></i>
                        </button>
                    </div>

                    <select
                        class="p-4 rounded-xl bg-yellow-50/50 border border-yellow-200 focus:bg-white focus:ring-2 ring-amber-400 outline-none transition">
                        <option disabled selected>Priority</option>
                        <option value="high">🔥 High</option>
                        <option value="medium">✨ Medium</option>
                        <option value="low">🍃 Low</option>
                    </select>

                    <select
                        class="p-4 rounded-xl bg-yellow-50/50 border border-yellow-200 focus:bg-white focus:ring-2 ring-amber-400 outline-none transition">
                        <option disabled selected>Status</option>
                        <option value="todo">📝 Todo</option>
                        <option value="done">✔ Done</option>
                    </select>

                    <button type="button" onclick="toggleAddTask()"
                        class="col-span-2 bg-gray-800 text-white text-lg font-bold py-4 rounded-xl hover:bg-gray-900 shadow-lg transition mt-2">
                        Save Task
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
                class="bg-white p-6 rounded-3xl shadow-lg border border-yellow-100 hover:shadow-xl transition flex flex-col justify-between h-full relative group">
                <div class="absolute top-4 right-4"><span
                        class="bg-amber-100 text-amber-800 text-xs font-bold px-2 py-1 rounded-full">High
                        Priority</span></div>
                <div>
                    <h3 class="font-bold text-xl text-gray-800 mb-1">Finish Laravel API</h3>
                    <p class="text-gray-500 text-sm mb-3"><i class="fa-regular fa-folder text-amber-500"></i> Work
                        &bull; <i class="fa-regular fa-calendar"></i> Nov 30</p>
                    <p class="text-gray-600 text-sm mb-4 leading-relaxed">Build authentication + CRUD endpoints.</p>
                </div>
                <div class="pt-4 border-t border-gray-100 mt-auto">
                    <div class="flex items-center justify-between">
                        <select
                            class="text-sm border-none bg-transparent font-semibold text-gray-700 focus:ring-0 cursor-pointer">
                            <option value="todo">📝 Todo</option>
                            <option value="done">✔ Done</option>
                        </select>
                        <div class="flex gap-2 opacity-100 lg:opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="openModal('edit')"
                                class="text-blue-500 hover:text-blue-700 bg-blue-50 p-2 rounded-lg"><i
                                    class="fa-solid fa-pen"></i></button>
                            <button onclick="openModal('read')"
                                class="text-green-500 hover:text-green-700 bg-green-50 p-2 rounded-lg"><i
                                    class="fa-solid fa-eye"></i></button>
                            <button onclick="openModal('delete')"
                                class="text-red-500 hover:text-red-700 bg-red-50 p-2 rounded-lg"><i
                                    class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
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
        function toggleAddTask() {
            const container = document.getElementById("addTaskFormContainer");
            const btnText = document.getElementById("toggleBtnText");
            container.classList.toggle("expanded");
            btnText.innerText = container.classList.contains("expanded") ? "Close Form" : "New Task";
        }

        function openModal(type) {
            const modal = document.getElementById("modal");
            const title = document.getElementById("modalTitle");
            const content = document.getElementById("modalContent");

            // Show the modal
            modal.classList.remove("hidden");

            // --- 1. EDIT MODAL ---
            if (type === "edit") {
                title.innerHTML = '<i class="fa-solid fa-pen-to-square text-amber-500"></i> Edit Task';
                content.innerHTML = `
                <form class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Task Title</label>
                        <input type="text" value="Finish Laravel API"
                            class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 ring-amber-400 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Description</label>
                        <textarea class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 ring-amber-400 outline-none h-24 transition">Build authentication + CRUD routes for the new project dashboard.</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Due Date</label>
                            <input type="date" value="2025-11-30"
                                class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 ring-amber-400 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Category</label>
                            <select class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 ring-amber-400 outline-none transition">
                                <option selected>Work</option>
                                <option>School</option>
                                <option>Personal</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Priority</label>
                            <select class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 ring-amber-400 outline-none transition">
                                <option value="high" selected>🔥 High</option>
                                <option value="medium">✨ Medium</option>
                                <option value="low">🍃 Low</option>
                            </select>
                        </div>
                         <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                            <select class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 ring-amber-400 outline-none transition">
                                <option value="todo" selected>📝 Todo</option>
                                <option value="done">✔ Done</option>
                            </select>
                        </div>
                    </div>

                    <button type="button" onclick="closeModal()" class="w-full bg-amber-500 text-white py-3 rounded-xl mt-4 hover:bg-amber-600 font-bold shadow-md transition transform active:scale-95">
                        Save Changes
                    </button>
                </form>
            `;
            }

            // --- 2. VIEW MODAL ---
            if (type === "read") {
                title.innerHTML = '<i class="fa-solid fa-eye text-amber-500"></i> Task Details';
                content.innerHTML = `
                <div class="bg-yellow-50 p-6 rounded-2xl border border-amber-100 space-y-4">

                    <div class="border-b border-amber-200 pb-4">
                        <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2 py-1 rounded-full mb-2 inline-block">High Priority</span>
                        <h3 class="text-xl font-bold text-gray-800">Finish Laravel API</h3>
                        <p class="text-sm text-gray-500 mt-1"><i class="fa-solid fa-calendar mr-1"></i> Due: Nov 30, 2025</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-bold mb-1">Description</p>
                        <p class="text-gray-700 leading-relaxed">Build authentication + CRUD endpoints for the new project dashboard. Ensure all API responses return JSON format.</p>
                    </div>

                    <div class="flex gap-4 pt-2">
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-amber-100 shadow-sm">
                            <i class="fa-solid fa-folder text-amber-500"></i>
                            <span class="text-sm font-semibold text-gray-700">Work</span>
                        </div>
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-amber-100 shadow-sm">
                            <i class="fa-solid fa-list-check text-amber-500"></i>
                            <span class="text-sm font-semibold text-gray-700">Todo</span>
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
                    <p class="text-gray-500 text-sm mt-2">Do you really want to delete <span class="font-bold text-gray-800">"Finish Laravel API"</span>? This process cannot be undone.</p>
                </div>

                <div class="flex gap-3 mt-4">
                    <button onclick="closeModal()" class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-xl hover:bg-gray-200 font-bold transition">
                        Cancel
                    </button>
                    <button class="flex-1 bg-red-500 text-white py-3 rounded-xl hover:bg-red-600 font-bold shadow-md transition transform active:scale-95">
                        Yes, Delete
                    </button>
                </div>
            `;
            }

            // --- KEEP CATEGORY MODAL ---
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

        function closeModal() {
            document.getElementById("modal").classList.add("hidden");
        }

        // Close on click outside
        document.getElementById("modal").addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });
    </script>
</body>

</html>
