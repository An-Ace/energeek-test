<template>
  <div class="page grid gap-6 w-full relative">
    <div class="flex justify-between align-middle">
      <div class="text-lg font-medium">Kategori List</div>
      <button @click="categories.push({ name: '', label: '', key: categories[categories.length - 1].key + 1 });" class="bg-red-400/20 rounded-lg py-2 px-3 flex text-red-600 align-middle gap-1">
        <img src="/public/plus.svg" class="w-5" alt="Add">Tambah Kategori
      </button>
    </div>
    <div class="grid gap-5">
      <div v-for="(category, i) in categories" class="flex gap-4" id="todo-1">
        <div class="w-full">
          <label :for="'name-'+ (i + 1)" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul Kategori</label>
          <input v-model="categories[i].name" @change="changedCategories.push(category.id)" placeholder="Todo" :id="'name-'+ (i + 1)" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-2 px-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
        </div>
        <div class="w-full">
          <label :for="'label-'+ (i + 1)" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Label Kategori</label>
          <input v-model="categories[i].label" @change="changedCategories.push(category.id)" placeholder="Contoh: To Do" :id="'label-'+ (i + 1)" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-2 px-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
        </div>
        <div class="flex">
          <button
            :disabled="!(changedCategories.includes(category.id) && categories[i].name !== '')"

            @click="handleSave(category.id)"
            class="p-2 !w-9 bg-blue-400 hover:bg-blue-400/80 rounded-lg self-end disabled:bg-blue-300">
            <img class="!w-7" src="/public/save.svg" alt="Del">
          </button>
        </div>
        <div class="flex">
          <button
            @click="$swal.fire({
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
                handleDelete($swal, category.id);
              }
            });"
            class="p-2 !w-8 bg-red-400 hover:bg-red-300 rounded-lg self-end">
            <img class="!w-7" src="/public/trash.svg" alt="Del">
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import Api from '../helpers/api';

const categories = ref([]);
const changedCategories = ref([])

const handleSave = async (id) => {
  await Api.put(`/api/categories/${id}`, {
    name: categories.find(cat => cat.id === id).name,
    label: categories.find(cat => cat.id === id).label,
  })
  changedCategories.value = changedCategories.value.filter(cat => cat !== id)
  console.log(changedCategories.value)
}

const handleDelete = async (swal, id) => {
  Api.delete(`/api/categories/${id}`)
  .then(() => {
      swal.fire({
      title: 'Berhasil!',
      text: 'Kategori berhasil dihapus.',
      imageUrl: '/check.svg',
      confirmButtonColor: '#50CD89',
      confirmButtonText: 'Ok',
      closeOnConfirm: false,
      closeOnCancel: false
    });
    categories.value = categories.value.filter(cat => cat.id !== id)
  })
}
onMounted(async () => {
  categories.value = await Api.get('/api/categories')
})
</script>