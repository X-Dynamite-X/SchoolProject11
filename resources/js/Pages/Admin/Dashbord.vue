<script setup>
import { useAdminStore } from '@/Stores/admin';
import { onMounted, ref, computed } from 'vue';

const adminStore = useAdminStore();
const loading = ref(true); // حالة التحميل
const currentPageUrl = ref('/api/admin/user'); // رابط الصفحة الحالي

onMounted(() => {
  // جلب البيانات عند تحميل الصفحة
  adminStore.getUsers(currentPageUrl.value).then(() => {
    loading.value = false; // عند انتهاء التحميل
  });
});

// استخراج بيانات المستخدمين
const users = computed(() => adminStore.users(currentPageUrl.value));
const usersData = computed(() => users.value?.data || []);
const pagination = computed(() => ({
  current_page: users.value?.current_page || 1,
  last_page: users.value?.last_page || 1,
  prev_page_url: users.value?.prev_page_url || null,
  next_page_url: users.value?.next_page_url || null,
}));

// تغيير الصفحة عند النقر على "السابق" أو "التالي"
const changePage = (url) => {
  if (url) {
    loading.value = true;
    currentPageUrl.value = url;
    adminStore.getUsers(url).then(() => {
      loading.value = false;
    });
  }
};
</script>

<template>
  <div class="flex-grow p-4 container bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 min-h-screen">
    <div class="container w-10/12 mx-auto">
      <!-- حالة التحميل -->
      <div v-if="loading" class="flex justify-center items-center py-20">
        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-blue-500"></div>
        <span class="ml-4 text-blue-500 dark:text-blue-400">Loading...</span>
      </div>

      <!-- الجدول -->
      <div v-else>
        <table
          id="user_table"
          class="w-full border-collapse text-sm bg-white shadow-lg rounded-lg overflow-hidden dark:bg-gray-800"
        >
          <!-- Header -->
          <thead class="bg-blue-100 text-blue-900 dark:bg-blue-900 dark:text-blue-100">
            <tr>
              <th class="px-4 py-2 text-left border-b border-blue-200 dark:border-blue-700">ID</th>
              <th class="px-4 py-2 text-left border-b border-blue-200 dark:border-blue-700">Name</th>
              <th class="px-4 py-2 text-left border-b border-blue-200 dark:border-blue-700">Email</th>
              <th class="px-4 py-2 text-left border-b border-blue-200 dark:border-blue-700">Role</th>
              <th class="px-4 py-2 text-left border-b border-blue-200 dark:border-blue-700">Actions</th>
            </tr>
          </thead>
          <!-- Body -->
          <tbody>
            <tr
              v-for="user in usersData"
              :key="user.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700"
            >
              <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-600">{{ user.id }}</td>
              <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-600">{{ user.name }}</td>
              <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-600">{{ user.email }}</td>
              <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                <span
                  v-for="role in user.roles"
                  :key="role.id"
                  class="inline-block px-2 py-1 mr-1 text-xs font-semibold bg-blue-500 text-white rounded dark:bg-blue-600"
                >
                  {{ role.name }}
                </span>
              </td>
              <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                <button
                  class="px-3 py-1 mr-2 text-xs text-white bg-green-500 rounded hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700"
                >
                  Edit
                </button>
                <button
                  class="px-3 py-1 text-xs text-white bg-red-500 rounded hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4">
          <button
            class="px-4 py-2 text-sm text-gray-700 bg-gray-300 rounded hover:bg-gray-400 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
            :disabled="!pagination.prev_page_url"
            @click="changePage(pagination.prev_page_url)"
          >
            Previous
          </button>
          <span class="text-sm text-gray-700 dark:text-gray-300">
            Page {{ pagination.current_page }} of {{ pagination.last_page }}
          </span>
          <button
            class="px-4 py-2 text-sm text-gray-700 bg-gray-300 rounded hover:bg-gray-400 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
            :disabled="!pagination.next_page_url"
            @click="changePage(pagination.next_page_url)"
          >
            Next
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
