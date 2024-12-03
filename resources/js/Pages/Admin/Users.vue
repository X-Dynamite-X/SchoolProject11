<script setup>
import { useAdminStore } from "@/Stores/admin";
import { onMounted, ref, computed, watch } from "vue";
import TabelTh from "@/components/Tabel/TabelTh.vue";
import SearchInput from "@/components/FieldRequst/SearchInput.vue";
import SearchIcon from "@/components/Icon/SearchIcon.vue";
import Pagination from "@/components/Tabel/Pagination.vue";
import DataTable from "@/components/Tabel/DataTable.vue";
import DynamicRow from "@/components/Tabel/DynamicRow.vue";

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

const thNameFields = ["ID", "Name", "Email", "Role", "Actions"];
const columns = [
    { key: "id", label: "ID" },
    { key: "name", label: "Name" },
    { key: "email", label: "Email" },
    { key: "roles", label: "Role" },
];
</script>
<template>
    <div
        class="flex-grow p-4 container bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 min-h-screen"
    >
        <div class="container w-10/12 mx-auto">
            <SearchInput v-model="searchKeyword" placeholder="Search users...">
                <template #icon>
                    <SearchIcon />
                </template>
            </SearchInput>
            <div>
                <DataTable :data="usersData" :loading="loading">
                    <template #header>
                        <TabelTh
                            v-for="thNameField in thNameFields"
                            :key="thNameField"
                            :nameFeild="thNameField"
                        />
                    </template>
                    <template #row="{ item }">
                        <DynamicRow :item="item" :columns="columns">
                            <template #column-roles="{ item }">
                                <span
                                    v-for="role in item.roles"
                                    :key="role.id"
                                    class="inline-block px-2 py-1 mr-1 text-xs font-semibold bg-blue-500 text-white rounded dark:bg-blue-600"
                                >
                                    {{ role.name }}
                                </span>
                            </template>
                            <template #column-actions="{ item }">
                                <button :id="item.id"
                                    class="px-3 py-1 text-xs bg-green-500 text-white rounded"
                                >
                                    Edit
                                </button>
                                <button  :id="item.id"
                                    class="px-3 py-1 text-xs bg-red-500 text-white rounded"
                                >
                                    Delete
                                </button>
                            </template>
                        </DynamicRow>
                    </template>
                </DataTable>

                <Pagination
                    :pagination="pagination"
                    @change-page="changePage"
                />
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
