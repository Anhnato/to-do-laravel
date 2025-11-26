<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Vibrant Todo App</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .modal-bg {
            backdrop-filter: blur(6px);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-purple-500 via-indigo-500 to-blue-500 min-h-screen p-10">

    <h1 class="text-4xl font-extrabold text-white text-center mb-10 drop-shadow-lg">
        ✨ Vibrant Todo Manager
    </h1>

    <div class="max-w-5xl mx-auto grid grid-cols-3 gap-6">

        <!-- CATEGORY MANAGER -->
        <div class="bg-white/30 backdrop-blur-xl rounded-2xl p-6 border border-white/20 shadow-xl col-span-1">
            <h2 class="text-xl font-bold text-white mb-4">📦 Categories</h2>

            <form class="flex gap-2 mb-5">
                <input type="text" placeholder="New Category"
                    class="flex-1 p-3 rounded-xl bg-white/60 focus:bg-white focus:ring-2 ring-purple-400 outline-none">
                <button class="bg-purple-600 text-white px-4 rounded-xl hover:bg-purple-700 shadow-md">
                    ➕
                </button>
            </form>

            <div class="space-y-2">
                <div class="bg-white/40 rounded-xl p-3 text-white font-semibold">Work</div>
                <div class="bg-white/40 rounded-xl p-3 text-white font-semibold">School</div>
                <div class="bg-white/40 rounded-xl p-3 text-white font-semibold">Personal</div>
            </div>
        </div>

        <!-- MAIN TASK AREA -->
        <div class="col-span-2 bg-white/30 backdrop-blur-xl shadow-2xl rounded-3xl p-8 border border-white/20">

            <!-- CREATE TASK -->
            <h2 class="text-2xl font-bold text-white mb-4">📝 Add New Task</h2>

            <form class="grid grid-cols-2 gap-4 mb-8">

                <input type="text" placeholder="Task Title"
                    class="col-span-2 p-3 rounded-xl bg-white/60 focus:bg-white focus:ring-2 ring-purple-400 outline-none">

                <!-- Description added -->
                <textarea placeholder="Description..."
                    class="col-span-2 p-3 rounded-xl bg-white/60 focus:bg-white focus:ring-2 ring-purple-400 outline-none h-28"></textarea>

                <input type="date"
                    class="p-3 rounded-xl bg-white/60 focus:bg-white focus:ring-2 ring-purple-400 outline-none">

                <select class="p-3 rounded-xl bg-white/60 focus:bg-white focus:ring-2 ring-purple-400 outline-none">
                    <option disabled selected>Select Category</option>
                    <option>Work</option>
                    <option>School</option>
                    <option>Personal</option>
                </select>

                <!-- Priority -->
                <select class="p-3 rounded-xl bg-white/60 focus:bg-white focus:ring-2 ring-purple-400 outline-none">
                    <option disabled selected>Select Priority</option>
                    <option value="high">🔥 High</option>
                    <option value="medium">✨ Medium</option>
                    <option value="low">🍃 Low</option>
                </select>

                <!-- Status -->
                <select class="p-3 rounded-xl bg-white/60 focus:bg-white focus:ring-2 ring-purple-400 outline-none">
                    <option disabled selected>Select Status</option>
                    <option value="todo">📝 Todo</option>
                    <option value="done">✔ Done</option>
                </select>

                <button
                    class="col-span-2 bg-purple-600 text-white text-lg font-semibold py-3 rounded-xl hover:bg-purple-700 shadow-xl transition">
                    ➕ Add Task
                </button>
            </form>

            <!-- TASK LIST -->
            <div class="space-y-4">

                <!-- SAMPLE TASK -->
                <div
                    class="bg-white/40 backdrop-blur-xl p-5 rounded-2xl shadow-lg border border-white/20 flex justify-between items-center">

                    <div>
                        <h3 class="font-bold text-xl text-white">Finish Laravel API</h3>
                        <p class="text-white/80 text-sm">Category: Work</p>
                        <p class="text-white/80 text-sm">Due: 2025-11-30</p>
                        <p class="text-white/90 mt-1">Build authentication + CRUD endpoints</p>

                        <div class="flex gap-3 mt-3">

                            <!-- Status Dropdown -->
                            <select
                                class="px-3 py-2 rounded-xl bg-white/60 hover:bg-white focus:ring-2 ring-indigo-400">
                                <option value="todo">📝 Todo</option>
                                <option value="done">✔ Done</option>
                            </select>

                            <!-- Priority Dropdown -->
                            <select
                                class="px-3 py-2 rounded-xl bg-white/60 hover:bg-white focus:ring-2 ring-indigo-400">
                                <option value="high">🔥 High</option>
                                <option value="medium">✨ Medium</option>
                                <option value="low">🍃 Low</option>
                            </select>

                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button onclick="openModal('edit')"
                            class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-xl shadow-md">
                            Edit
                        </button>

                        <button onclick="openModal('read')"
                            class="cursor-pointer bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl shadow-md">
                            View
                        </button>

                        <button onclick="openModal('delete')"
                            class="cursor-pointer bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl shadow-md">
                            Delete
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div id="modal" class="hidden fixed inset-0 modal-bg bg-white/10 flex items-center justify-center p-5">
        <div class="bg-white/90 p-8 rounded-3xl shadow-2xl max-w-md w-full border border-gray-200 relative">

            <h2 id="modalTitle" class="text-2xl font-bold mb-4 text-gray-800"></h2>

            <div id="modalContent" class="space-y-3"></div>

            <button onclick="closeModal()"
                class="mt-6 bg-gray-700 text-white px-5 py-2 rounded-xl hover:bg-gray-800 w-full">
                Close
            </button>

        </div>
    </div>

    <script>
        function openModal(type) {
            const modal = document.getElementById("modal");
            const title = document.getElementById("modalTitle");
            const content = document.getElementById("modalContent");
            modal.classList.remove("hidden");

            if (type === "edit") {
                title.innerText = "Edit Task";
                content.innerHTML = `
                    <input class="w-full p-3 rounded-xl border" placeholder="New Title" />
                    <textarea class="w-full p-3 rounded-xl border h-24" placeholder="Description"></textarea>
                    <input type="date" class="w-full p-3 rounded-xl border" />
                    <select class="w-full p-3 rounded-xl border">
                        <option>Work</option>
                        <option>School</option>
                        <option>Personal</option>
                    </select>

                    <select class="w-full p-3 rounded-xl border">
                        <option value="todo">Todo</option>
                        <option value="done">Done</option>
                    </select>

                    <select class="w-full p-3 rounded-xl border">
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>

                    <button class="w-full bg-blue-600 text-white py-3 rounded-xl mt-3 hover:bg-blue-700">
                        Save Changes
                    </button>
                `;
            }

            if (type === "read") {
                title.innerText = "Task Details";
                content.innerHTML = `
                    <p><strong>Title:</strong> Finish Laravel API</p>
                    <p><strong>Description:</strong> Build authentication + CRUD routes</p>
                    <p><strong>Category:</strong> Work</p>
                    <p><strong>Status:</strong> Todo</p>
                    <p><strong>Priority:</strong> High</p>
                    <p><strong>Due Date:</strong> 2025-11-30</p>
                `;
            }

            if (type === "delete") {
                title.innerText = "Delete Task";
                content.innerHTML = `
                    <p class="text-red-600 font-semibold">Are you sure you want to delete this task?</p>
                    <button class="w-full mt-3 bg-red-600 text-white py-3 rounded-xl hover:bg-red-700">
                        Yes, Delete
                    </button>
                `;
            }
        }

        function closeModal() {
            document.getElementById("modal").classList.add("hidden");
        }
    </script>

</body>

</html>