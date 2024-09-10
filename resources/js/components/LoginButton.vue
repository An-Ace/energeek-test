<template>
  <div>
    <!-- Trigger Button -->
    <button @click="openModal" class="px-2 py-1 text-white bg-blue-600 rounded">
      Login ->
    </button>

    <!-- Modal Dialog -->
    <Dialog as="div" class="relative z-10" :open="isOpen" @close="closeModal">
      <!-- Background Overlay -->
      <div class="fixed inset-0 bg-black bg-opacity-30"></div>

      <!-- Modal Content -->
      <div class="fixed inset-0 flex items-center justify-center p-4">
        <DialogPanel class="w-full max-w-md p-6 bg-white rounded shadow-lg">
          <DialogTitle class="text-xl font-semibold">Login Dengan Email</DialogTitle>

          <!-- Form -->
          <form @submit.prevent="sendEmail">
            <div class="mt-4">
              <label class="block text-sm">Email</label>
              <input
                v-model="formData.email"
                type="email"
                class="w-full p-2 mt-1 border rounded"
                placeholder="Email Kamu"
                required
              />
            </div>

            <!-- Submit and Cancel Buttons -->
            <div class="flex justify-end mt-6 space-x-2">
              <button
                type="submit"
                class="px-4 py-2 text-white bg-blue-600 rounded"
              >
                Login
              </button>
            </div>
          </form>
        </DialogPanel>
      </div>
    </Dialog>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Dialog, DialogPanel, DialogTitle } from '@headlessui/vue';
import Api from '../helpers/api';
import { toast } from 'vue3-toastify';
import { validateEmail } from '../helpers/validate';

const props = defineProps<{
  onSigned: ({ name, email, username, id } : { name: string, email: string, username: string, id: number }) => void;
}>()


const isOpen = ref(false);
// Form Data
const formData = ref({
  email: ''
});
const sendEmail = async () => {
  if (validateEmail(formData.value.email)) {
    await Api.post('/api/login', {
      email: formData.value.email
    })
    .then((response) => {
      props?.onSigned(response)
      closeModal()
    })
  } else {
    toast.warn('Email tidak valid')
  }
};
// Functions to handle modal state
const openModal = () => {
  isOpen.value = true;
};

const closeModal = () => {
  isOpen.value = false;
};

</script>

<style scoped>
/* Optionally, you can add some custom styles here */
</style>
