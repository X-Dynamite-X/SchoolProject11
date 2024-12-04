<script setup>
import { useAdminStore } from "@/Stores/admin";
import { onMounted, ref, computed, watch } from "vue";
import TabelTh from "@/components/Tabel/TabelTh.vue";
import SearchInput from "@/components/FieldRequst/SearchInput.vue";
import SearchIcon from "@/components/Icon/SearchIcon.vue";
import Pagination from "@/components/Tabel/Pagination.vue";
import DataTable from "@/components/Tabel/DataTable.vue";
import DynamicRow from "@/components/Tabel/DynamicRow.vue";
import EditIcon from "@/components/Icon/EditIcon.vue";
import DeleteIcon from "@/components/Icon/DeleteIcon.vue";
import InfoIcon from "@/components/Icon/InfoIcon.vue";

const adminStore = useAdminStore();
const loading = ref(true);
const currentPageUrl = ref("/api/admin/subject");
const searchKeyword = ref("");

const fetchData = () => {
    loading.value = true;
    adminStore
        .getSubjects(currentPageUrl.value, searchKeyword.value)
        .then(() => {
            loading.value = false;
        });
};

onMounted(fetchData);

watch(searchKeyword, fetchData);

const subjects = computed(() =>
    adminStore.subjects(
        `${currentPageUrl.value}?keyword=${searchKeyword.value}`
    )
);
const subjectsData = computed(() => subjects.value?.data || []);
const pagination = computed(() => ({
    current_page: subjects.value?.current_page || 1,
    last_page: subjects.value?.last_page || 1,
    prev_page_url: subjects.value?.prev_page_url || null,
    next_page_url: subjects.value?.next_page_url || null,
}));

// الحسابات
const totalsubjects = computed(() => subjects.value?.total || 0);
const cumulativeDisplayedsubjects = computed(() => {
    const perPage = subjects.value?.per_page || 10; // عدد المستخدمين لكل صفحة
    const currentPage = pagination.value.current_page || 1; // الصفحة الحالية
    const displayedInCurrentPage = subjectsData.value.length; // عدد المستخدمين في الصفحة الحالية
    return (currentPage - 1) * perPage + displayedInCurrentPage;
});

const changePage = (url) => {
    if (url) {
        currentPageUrl.value = url;
        fetchData();
    }
};
const thNameFields = ["ID", "Name", "Email", "Full Mark", "Actions"];
const columns = [
    { key: "id", label: "ID" },
    { key: "name", label: "Name" },
    { key: "success_mark", label: "Success Mark" },
    { key: "full_mark", label: "Full Mark" },
];
</script>
<template>
    <div
        class="bg-red-800 flex-grow p-4 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 min-h-screen"
    >
        <div class="container w-10/12 mx-auto">
            <SearchInput
                v-model="searchKeyword"
                placeholder="Search subjects..."
            >
                <template #icon>
                    <SearchIcon />
                </template>
            </SearchInput>
            <div>
                <DataTable :data="subjectsData" :loading="loading">
                    <template #header>
                        <TabelTh
                            v-for="thNameField in thNameFields"
                            :key="thNameField"
                            :nameFeild="thNameField"
                        />
                    </template>
                    <template #row="{ item }">
                        <DynamicRow :item="item" :columns="columns">
                            <template #column-actions="{ item }">

                                     <button
                                        :id="item.id"
                                        class="px-3 py-1 text-xs  bg-stone-300 dark:bg-gray-800 text-blue-400 hover:text-blue-600 rounded mx-1"
                                    >
                                        <InfoIcon />
                                    </button>
                                    <button
                                        :id="item.id"
                                        class="px-3 py-1 text-xs bg-stone-300 dark:bg-gray-800 text-yellow-400 hover:text-yellow-600 rounded mx-1"
                                    >
                                        <EditIcon />
                                    </button>
                                    <button
                                        :id="item.id"
                                        class="px-3 py-1 bg-stone-300 dark:bg-gray-800 text-red-400 hover:text-red-600 rounded mx-1"
                                    >
                                        <DeleteIcon />
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
                            >{{ cumulativeDisplayedsubjects }}/{{
                                totalsubjects
                            }}</strong
                        >
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
