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
import DynamicInfo from "@/components/Model/DynamicInfo.vue";
import DynamicEdit from "@/components/Model/DynamicEdit.vue";
import InputRadio from "@/components/FieldRequst/InputRadio.vue";
import DynamicDelete from "@/components/Model/DynamicDelete.vue";

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
    const perPage = users.value?.per_page || 10;
    const currentPage = pagination.value.current_page || 1;
    const displayedInCurrentPage = usersData.value.length;
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
    {
        key: "name",
        label: "Name",
        name: "name",
        type: "text",
        required: true,
        disabled: true,
        placeholder: "Enter Name",
        errorMessages: ["The name field is required."],
    },
    {
        key: "email",
        label: "Email",
        name: "email",
        type: "email",
        required: true,
        disabled: true,
        placeholder: "Enter Email",
        errorMessages: ["The email field is required."],
    },
    {
        key: "roles",
        label: "Role",
        name: "roles",
        type: "radio",
        required: true,
        options: [
            { label: "Admin", value: "admin" },
            { label: "User", value: "user" },
        ],
    },
];
const showInfoModel = ref(false);
const showEditModel = ref(false);
const showDeleteModel = ref(false);
const modelData = ref({});

function openInfoModel(data) {
    showInfoModel.value = true;

    modelData.value = data;
}
function openEditModel(data) {
    showEditModel.value = true;
    modelData.value = data;
}
function openDeleteModel(data) {
    showDeleteModel.value = true;
    modelData.value = data;
}
function deleteData(data) {
     adminStore.deleteUser(data)
    console.log(usersData.value[0]);
    closeModal();
    usersData.value.filter(c => c.id !== data.id);
}
const closeModal = () => {
    showInfoModel.value = false;
    showDeleteModel.value = false;
    showEditModel.value = false;
};
</script>
<template>
    <div
        class="flex-grow p-4 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 min-h-screen"
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
                                <button
                                    :id="item.id"
                                    @click="openInfoModel(item)"
                                    class="px-3 py-1 mx-1 text-xs bg-stone-300 dark:bg-gray-800 text-blue-400 hover:text-blue-600 rounded"
                                >
                                    <InfoIcon />
                                </button>
                                <button
                                    :id="item.id"
                                    @click="openEditModel(item)"
                                    class="px-3 py-1 mx-1 text-xs bg-stone-300 dark:bg-gray-800 text-yellow-400 hover:text-yellow-600 rounded"
                                >
                                    <EditIcon />
                                </button>
                                <button
                                    @click="openDeleteModel(item)"
                                    :id="item.id"
                                    class="px-3 py-1 mx-1 bg-stone-300 dark:bg-gray-800 text-red-400 hover:text-red-600 rounded"
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
                            >{{ cumulativeDisplayedUsers }}/{{
                                totalUsers
                            }}</strong
                        >
                    </p>
                </div>
            </div>

            <DynamicInfo
                :data="modelData"
                :columns="columns"
                :show="showInfoModel"
                @close="closeModal"
            >
                <template #column-roles="{ data, column }">
                    <strong>{{ column.label }}:</strong>
                    <span
                        v-for="role in data.roles"
                        :key="role.id"
                        class="inline-block px-2 py-1 mr-1 text-xs font-semibold rounded"
                    >
                        {{ role.name }}
                    </span>
                </template>
            </DynamicInfo>

            <DynamicEdit
                :data="modelData"
                :columns="columns"
                :show="showEditModel"
                @close="closeModal"
                title="Edit User"
            >
                <template #column-roles="{ data, column }">
                    <InputRadio
                        :label="column.label"
                        :name="column.name"
                        :id="column.key"
                        :type="column.type"
                        :modelValue="data[column.key][0].name"
                        :placeholder="column.placeholder"
                        :required="column.required"
                        :options="column.options"
                        :errorMessage="column.errorMessage"
                        :autocomplete="column.autocomplete"
                    />
                </template>
            </DynamicEdit>
            <DynamicDelete
                :data="modelData"
                :columns="columns"
                :show="showDeleteModel"
                @close="closeModal"
                @delete="deleteData"
                title="Delete User"
            >
            <template #message="{data}">
                Are you sure you want to delete this user?
                <strong class="text-red-600">{{data.name}}</strong>
            </template>
            </DynamicDelete>
        </div>
    </div>
</template>
