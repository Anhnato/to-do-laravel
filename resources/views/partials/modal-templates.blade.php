<template x-if="modalType === 'create'">
    <form action="{{ route('task.store') }}" method="post" @submit="isLoading = true" class="space-y-4">
        @csrf
        <input type="text" name="title" placeholder="Task Title" required
            class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 ring-amber-400 outline-none">
        <textarea name="description" placeholder="Description..."
            class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 ring-amber-400 outline-none h-24"></textarea>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Due Date</label><input type="date"
                    name="due_date" class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none"></div>
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
            <div><select name="status" class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none">
                    <option value="pending">📝 Pending</option>
                    <option value="completed">✔ Completed</option>
                </select></div>
            <div><select name="priority" class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none">
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
            <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Due Date</label><input
                    x-model="activeTask.due_date" type="date"
                    class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none"></div>
            <div><label class="block text-xs font-bold text-gray-500 uppercase mb-1">Category</label><select
                    x-model="activeTask.category_id"
                    class="w-full p-3 rounded-xl border border-gray-200 bg-gray-50 outline-none"><template
                        x-for="cat in categories" :key="cat.id">
                        <option :value="cat.id" x-text="cat.name" :selected="activeTask.category_id == cat.id"></option>
                    </template></select></div>
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
                <p class="text-sm text-gray-500 mt-1"><i class="fa-solid fa-calendar mr-1"></i> Due: <span
                        x-text="formatDate(activeTask.due_date)"></span></p>
            </div>
            <div class="mt-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-bold mb-1">Description</p>
                <p class="text-gray-700 leading-relaxed" x-text="activeTask.description || 'No description'"></p>
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
        <p class="text-gray-500 text-sm mt-2">Are you sure you want to delete this task?<br>This cannot be undone.</p>
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
            <input type="text" x-model="newCategoryName" @keydown.enter.prevent="createCategory()"
                placeholder="New Category Name"
                class="flex-1 p-3 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 ring-amber-400 outline-none">
            <button @click="createCategory()" :disabled="isCreatingCategory"
                :class="isCreatingCategory ? 'opacity-50 cursor-not-allowed' : 'hover:bg-amber-600 hover:scale-105'"
                class="bg-amber-500 text-white px-4 rounded-xl shadow-md transition transform">

                <i class="fa-solid" :class="isCreatingCategory ? 'fa-circle-notch fa-spin' : 'fa-plus'"></i>
            </button>
        </div>
        <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
            <template x-for="cat in categories" :key="cat.id">
                <div
                    class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100 hover:border-amber-200 transition">
                    <span class="font-semibold text-gray-700 truncate" x-text="cat.name"></span>
                    <button @click="confirmCategoryDelete(cat.id)"
                        class="text-gray-400 hover:text-red-500 w-8 h-8 rounded-full hover:bg-red-50 transition"><i
                            class="fa-solid fa-trash"></i></button>
                </div>
            </template>
            <div x-show="categories.length === 0" class="text-center text-gray-400 py-4 text-sm">No categories found.
                Add one above!</div>
        </div>
    </div>
</template>

<template x-if="modalType === 'deleteCategory'">
    <div class="text-center">
        <div class="bg-red-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"><i
                class="fa-solid fa-triangle-exclamation text-2xl text-red-500"></i></div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Delete Category?</h3>
        <p class="text-gray-500 text-sm mb-6">Are you sure? Tasks using this category will become "Uncategorized".</p>
        <div class="flex gap-3">
            <button @click="modalType = 'category'; categoryToDelete = null"
                class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-xl hover:bg-gray-200 font-bold transition">Cancel</button>
            <button @click="submitCategoryDelete()"
                class="flex-1 bg-red-500 text-white py-3 rounded-xl hover:bg-red-600 font-bold shadow-md transition transform active:scale-95">Yes,
                Delete</button>
        </div>
    </div>
</template>

<template x-if="modalType === 'alert'">
    <div class="text-center">
        <div class="bg-red-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-circle-exclamation text-2xl text-red-500"></i>
        </div>

        <h3 class="text-xl font-bold text-gray-800 mb-2">Whoops!</h3>

        <p class="text-gray-500 text-sm mb-6" x-text="alertMessage"></p>

        <button @click="modalType = 'category'"
            class="w-full bg-gray-800 text-white py-3 rounded-xl hover:bg-gray-900 font-bold shadow-md transition transform active:scale-95">
            Okay, got it
        </button>
    </div>
</template>