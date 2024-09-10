import { createApp } from "vue";
import App from "./App.vue";
import { createWebHistory, createRouter } from 'vue-router'
import Vue3Toastify from 'vue3-toastify'
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import HomeView from './pages/Home.vue'
import MyTasks from './pages/MyTasks.vue'
import CategoryPanel from "./pages/CategoryPanel.vue";


const routes = [
  { path: '/', component: HomeView },
  { path: '/my-tasks', component: MyTasks },
  { path: '/categories', component: CategoryPanel },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

createApp(App).use(router).use(Vue3Toastify).use(VueSweetalert2).mount("#app");