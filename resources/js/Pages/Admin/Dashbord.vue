<script setup>
import { useAdminStore } from "@/Stores/admin";
import { onMounted, ref, computed, watch } from "vue";

const adminStore = useAdminStore();
const loading = ref(true);
const currentPageUrl = ref("/api/admin/user");
const searchKeyword = ref("");

const fetchData = () => {
    loading.value = true;
    adminStore.getUsers(currentPageUrl.value, searchKeyword.value).then(() => {
        loading.value = false;
    });
};

onMounted(fetchData);

watch(searchKeyword, fetchData);

const users = computed(() =>
    adminStore.users(`${currentPageUrl.value}?keyword=${searchKeyword.value}`)
);
const usersData = computed(() => users.value?.data || []);
const pagination = computed(() => ({
    current_page: users.value?.current_page || 1,
    last_page: users.value?.last_page || 1,
    prev_page_url: users.value?.prev_page_url || null,
    next_page_url: users.value?.next_page_url || null,
}));

// الحسابات
const totalUsers = computed(() => users.value?.total || 0);
const cumulativeDisplayedUsers = computed(() => {
    const perPage = users.value?.per_page || 10; // عدد المستخدمين لكل صفحة
    const currentPage = pagination.value.current_page || 1; // الصفحة الحالية
    const displayedInCurrentPage = usersData.value.length; // عدد المستخدمين في الصفحة الحالية
    return (currentPage - 1) * perPage + displayedInCurrentPage;
});

const changePage = (url) => {
    if (url) {
        currentPageUrl.value = url;
        fetchData();
    }
};
</script>
<template>
    <div
        class="flex-grow p-4 container bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 min-h-screen"
    >
        <div class="container w-10/12 mx-auto">
            <!-- حقل البحث -->
            <div class="mb-4">
                <input
                    type="text"
                    v-model="searchKeyword"
                    placeholder="Search users..."
                    class="w-full p-2 border border-gray-300 rounded shadow-sm dark:bg-gray-800 dark:text-gray-200"
                />
            </div>

            <!-- الجدول -->
            <div>
                <table
                    id="user_table"
                    class="w-full border-collapse text-sm bg-white shadow-lg rounded-lg overflow-hidden dark:bg-gray-800"
                >
                    <!-- Header -->
                    <thead
                        class="bg-blue-100 text-blue-900 dark:bg-blue-900 dark:text-blue-100"
                    >
                        <tr>
                            <th
                                class="px-4 py-2 text-left border-b border-blue-200 dark:border-blue-700"
                            >
                                ID
                            </th>
                            <th
                                class="px-4 py-2 text-left border-b border-blue-200 dark:border-blue-700"
                            >
                                Name
                            </th>
                            <th
                                class="px-4 py-2 text-left border-b border-blue-200 dark:border-blue-700"
                            >
                                Email
                            </th>
                            <th
                                class="px-4 py-2 text-left border-b border-blue-200 dark:border-blue-700"
                            >
                                Role
                            </th>
                            <th
                                class="px-4 py-2 text-left border-b border-blue-200 dark:border-blue-700"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <!-- Body -->
                    <tbody>
                        <tr v-if="loading">
                            <td colspan="5" class="text-center py-6">
                                <div
                                    class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-blue-500"
                                ></div>

                                <span
                                    class="ml-4 text-blue-500 dark:text-blue-400"
                                    >Loading...</span
                                >
                            </td>
                        </tr>

                        <tr v-if="!loading && usersData.length === 0">
                            <td
                                colspan="5"
                                class="text-center py-6 text-gray-500 dark:text-gray-300"
                            >
                                No data available.
                            </td>
                        </tr>

                        <tr
                            v-for="user in usersData"
                            :key="user.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700"
                        >
                            <td
                                class="px-4 py-2 border-b border-gray-200 dark:border-gray-600"
                            >
                                {{ user.id }}
                            </td>
                            <td
                                class="px-4 py-2 border-b border-gray-200 dark:border-gray-600"
                            >
                                {{ user.name }}
                            </td>
                            <td
                                class="px-4 py-2 border-b border-gray-200 dark:border-gray-600"
                            >
                                {{ user.email }}
                            </td>
                            <td
                                class="px-4 py-2 border-b border-gray-200 dark:border-gray-600"
                            >
                                <span
                                    v-for="role in user.roles"
                                    :key="role.id"
                                    class="inline-block px-2 py-1 mr-1 text-xs font-semibold bg-blue-500 text-white rounded dark:bg-blue-600"
                                >
                                    {{ role.name }}
                                </span>
                            </td>
                            <td
                                class="px-4 py-2 border-b border-gray-200 dark:border-gray-600"
                            >
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

                <div class="flex justify-between items-center mt-4">
                    <button
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-300 rounded hover:bg-gray-400 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
                        :disabled="!pagination.prev_page_url"
                        @click="changePage(pagination.prev_page_url)"
                    >
                        Previous
                    </button>
                    <span class="text-sm text-gray-700 dark:text-gray-300">
                        Page {{ pagination.current_page }} of
                        {{ pagination.last_page }}
                    </span>
                    <button
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-300 rounded hover:bg-gray-400 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
                        :disabled="!pagination.next_page_url"
                        @click="changePage(pagination.next_page_url)"
                    >
                        Next
                    </button>
                </div>

                <!-- عدد المستخدمين -->
                <div class="mt-4 text-gray-700 dark:text-gray-300 text-center">
                    <p>
                        Displayed:
                        <strong
                            >{{ cumulativeDisplayedUsers }}/{{
                                totalUsers
                            }}</strong
                        >
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
