<template>
  <div class="page grid gap-6 w-full relative">
    <div class="flex justify-center">
      <img class="w-44" src="/public/energeek.png" alt="Energeek-Logo">
    </div>
    <div class="card bg-white rounded-lg p-6 grid md:grid-cols-3 gap-6 w-full">
      <div class="w-72">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
        <input :value="user?.name" placeholder="Nama" id="name" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-2 px-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
      </div>
      <div class="w-72">
        <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
        <input :value="user?.username" placeholder="Username" id="username" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-2 px-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
      </div>
      <div class="w-72">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
        <input :value="user?.email" placeholder="Email" id="email" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-2 px-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
      </div>
    </div>
    <div class="flex justify-between align-middle">
      <div class="text-lg font-medium">To Do List</div>
      <button @click="tasks.push({ description: '', category_id: 1, key: tasks[tasks.length - 1].key + 1 }); updateFull = true" class="bg-red-400/20 rounded-lg py-2 px-3 flex text-red-600 align-middle gap-1">
        <img src="/public/plus.svg" class="w-5" alt="Add">Tambah To Do
      </button>
    </div>
    <div class="grid gap-5">
      <div v-for="(task, i) in user?.tasks || []" class="flex gap-4" id="todo-1">
        <div class="w-full">
          <label :for="'title-'+ (i + 1)" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul To Do</label>
          <input :value="task.description" placeholder="Contoh: Perbaikan api master" :id="'title-'+ (i + 1)" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-2 px-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
        </div>
        <div>
          <Select label="Kategori" :options="categories"/>
        </div>
        <div class="flex" v-show="tasks.length > 1">
          <button
            @click="tasks = tasks.filter((taskKey) => taskKey.key !== task.key);"
            class="p-2 bg-red-400 hover:bg-red-300 rounded-lg self-end">
            <img class="!w-7" src="/public/trash.svg" alt="Del">
          </button>
        </div>
      </div>
    </div>
    <div>
      <button class="w-full bg-green-600 text-white py-2 rounded-lg">
        SIMPAN
      </button>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import Select from '../components/select.vue';
import Api from '../helpers/api';

const user = ref({});
const data = await Api.get('/api/categories');
const tasks = ref([
  { key: 1, description: 'Task 1', category_id: 1 },
]);
const categories = data.map((category) => ({ value: category.id, label: category.label || category.name }))
const updateFull = ref(false);
onMounted(() => {
  const userSession = JSON.parse(localStorage.getItem('user-energeek') || '{}');
  Api.get(`/api/my-tasks/${userSession.email}`).then((response) => {
    user.value = response;
  })

})

</script>