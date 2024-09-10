<template>
  <div class="page grid gap-6 w-full relative">
    <div class="flex justify-center">
      <img class="w-44" src="/public/energeek.png" alt="Energeek-Logo">
    </div>
    <div class="card bg-white rounded-lg p-6 grid md:grid-cols-3 gap-6 w-full">
      <div class="w-72">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
        <input v-model="userData.name" placeholder="Nama" id="name" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-2 px-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
      </div>
      <div class="w-72">
        <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
        <input v-model="userData.username" placeholder="Username" id="username" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-2 px-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
      </div>
      <div class="w-72">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
        <input v-model="userData.email" placeholder="Email" id="email" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-2 px-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
      </div>
    </div>
    <div class="flex justify-between align-middle">
      <div class="text-lg font-medium">To Do List</div>
      <button @click="tasks.push({ description: '', category_id: 1, key: tasks[tasks.length - 1].key + 1 }); console.log(tasks)" class="bg-red-400/20 rounded-lg py-2 px-3 flex text-red-600 align-middle gap-1">
        <img src="/public/plus.svg" class="w-5" alt="Add">Tambah To Do
      </button>
    </div>
    <div class="grid gap-5">
      <div v-for="(task, i) in tasks" class="flex gap-4" id="todo-1">
        <div class="w-full">
          <label :for="'title-'+ (i + 1)" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul To Do</label>
          <input v-model="tasks[i].description" placeholder="Contoh: Perbaikan api master" :id="'title-'+ (i + 1)" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-2 px-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
        </div>
        <div>
          <Select label="Kategori" :options="categories"/>
        </div>
        <div class="flex" v-show="tasks.length > 1">
          <button
            @click="
              $swal.fire({
                title: 'Apakah anda yakin?',
                text: 'To do yang dihapus tidak dapat dikembalikan.',
                showCancelButton: true,
                imageUrl: '/alert.svg',
                confirmButtonColor: '#F64E60',
                cancelButtonColor: '#F3F6F9',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                closeOnConfirm: false,
                closeOnCancel: false
              }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                  confirmedDeleteTask($swal, task.key);
                }
              });"
            class="p-2 bg-red-400 hover:bg-red-300 rounded-lg self-end">
            <img class="!w-7" src="/public/trash.svg" alt="Del">
          </button>
        </div>
      </div>
    </div>
    <div>
      <button class="w-full bg-green-600 text-white py-2 rounded-lg" 
        @click="handleSend($swal)">
        SIMPAN
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Select from '../components/select.vue';
import Api from '../helpers/api';
import { toast } from 'vue3-toastify';
import { validateEmail } from '../helpers/validate';

const data = await Api.get('/api/categories');
const tasks = ref([
  { key: 1, description: '', category_id: 1 },
]);
const categories = data.map((category) => ({ value: category.id, label: category.label || category.name }))
const confirmedDeleteTask = (swal, key) => {
  swal.fire({
    title: 'Berhasil!',
    text: 'To do berhasil dihapus.',
    imageUrl: '/check.svg',
    confirmButtonColor: '#50CD89',
    confirmButtonText: 'Ok',
    closeOnConfirm: false,
    closeOnCancel: false
  });
  tasks.value = tasks.value.filter((task) => task.key !== key);
}
const userData = ref({
  name: '',
  username: '',
  email: '',
})

const handleSend = (swal) => {
  if (userData.value.name === '') {
    return toast('Name Harus yang diisi', { type: 'error' })
  } else if (userData.value.username === '') {
    return toast('Username Harus yang diisi', { type: 'error' })
  } else if (!validateEmail(userData.value.email)) {
    return toast('Format Email Harus belum benar', { type: 'error' })
  } else if (tasks.value.find((task) => task.description === '')) {
    return toast('Judul To do Harus yang diisi', { type: 'error' })
  }
  Api.post('/api/add-tasks', {
    name: userData.value.name,  
    username: userData.value.username,  
    email: userData.value.email,
    tasks: tasks.value.map((task) => ({ description: task.description, category_id: task.category_id })),
  })
  .then((res) => {
      swal.fire({
      title: 'Berhasil!',
      text: 'To do berhasil Disimpan!',
      imageUrl: '/check.svg',
      confirmButtonColor: '#50CD89',
      confirmButtonText: 'Ok',
    })
  })
}

</script>