<script setup>
import { useAdminStore } from "@/Stores/admin";
import { onMounted, ref, computed, watch } from "vue";
import TabelTh from "@/components/Tabel/TabelTh.vue";
import SearchInput from "@/components/FieldRequst/SearchInput.vue";
import SearchIcon from "@/components/Icon/SearchIcon.vue";
import DataTable from "@/components/Tabel/DataTable.vue";
import DynamicRow from "@/components/Tabel/DynamicRow.vue";
import EditIcon from "@/components/Icon/EditIcon.vue";
import DeleteIcon from "@/components/Icon/DeleteIcon.vue";
import InfoIcon from "@/components/Icon/InfoIcon.vue";
import DynamicInfo from "@/components/Model/DynamicInfo.vue";
import DynamicEdit from "@/components/Model/DynamicEdit.vue";
import InputRadio from "@/components/FieldRequst/InputRadio.vue";
import DynamicDelete from "@/components/Model/DynamicDelete.vue";
import DynamicCreate from "@/components/Model/DynamicCreate.vue";

import ItemsPerPage from "@/components/FieldRequst/ItemsPerPage.vue";
import Pagination from "@/components/Tabel/Pagination.vue";
import Alerts from "@/components/AllApp/Alerts.vue";

const adminStore = useAdminStore();
const loading = ref(true);
const searchKeyword = ref("");
const limitUser = ref(5);
const users = computed(() => adminStore.users);
const totalItems = ref(0);
const fetchData = async () => {
    loading.value = true;
    try {
        await adminStore.getUsers();
        totalItems.value = users.value.length; // إجمالي عدد المستخدمين
    } catch (error) {
        console.error("Error fetching data:", error);
    } finally {
        loading.value = false;
    }
};
const currentPage = ref(1); // الصفحة الحالية
const sortColumn = ref(null); // العمود المستخدم للفرز
const sortDirection = ref("asc");

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
        showInCreate: true,
        showInEdit: true,
        placeholder: "Enter Name",
        errorMessages: ["The name field is required."],
    },
    {
        key: "email",
        label: "Email",
        name: "email",
        type: "email",
        showInCreate: true,
        showInEdit: true,
        required: true,
        disabled: true,
        placeholder: "Enter Email",
        errorMessages: ["The email field is required."],
    },
    {
        key: "password",
        label: "Password",
        name: "password",
        type: "password",
        showInCreate: true,
        showInEdit: false,
        required: true,
        disabled: true,
        placeholder: "Enter Password",
        errorMessages: ["The email field is required."],
    },
    {
        key: "password_confirmation",
        label: "Password Confirmation",
        name: "password_confirmation",
        type: "password",
        showInCreate: true,
        showInEdit: false,
        required: true,
        disabled: true,
        placeholder: "Enter password Confirmation",
        errorMessages: ["The email field is required."],
    },
    {
        key: "roles",
        label: "Role",
        name: "roles",
        type: "radio",
        showInEdit: true,
        showInCreate: false,
        required: true,
        options: [
            { label: "Admin", value: "admin" },
            { label: "User", value: "user" },
        ],
    },
];

onMounted(fetchData);

watch(searchKeyword, (newKeyword) => {
    searchKeyword.value = newKeyword;
    currentPage.value = 1; // إعادة تعيين الصفحة الحالية إلى 1
});

watch(limitUser, (newLimit) => {
    currentPage.value = 1; // إعادة تعيين الصفحة الحالية إلى 1
});

const filteredUsers = computed(() => {
    const keyword = searchKeyword.value.toLowerCase();
    return users.value.filter(
        (user) =>
            user.name.toLowerCase().includes(keyword) ||
            user.email.toLowerCase().includes(keyword) ||
            user.roles[0].name.includes(keyword)
    );
});

const paginatedUsers = computed(() => {
    const startIndex = Number((currentPage.value - 1) * limitUser.value); // تحويل إلى عدد

    const endIndex = startIndex + Number(limitUser.value); // تحويل إلى عدد

    return sortedUsers.value.slice(startIndex, endIndex);
});

const totalPages = computed(() =>
    Math.ceil(filteredUsers.value.length / limitUser.value)
);
// تغيير الصفحة
const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

// تحديث عدد العناصر في الصفحة
const updateItemsPerPage = (newLimit) => {
    limitUser.value = newLimit;
    currentPage.value = 1; // إعادة ضبط الصفحة إلى الأولى
};

// تغيير العمود المستخدم للفرز
const sort = (column) => {
    const columnKey = columns.find((col) => col.label === column)?.key;
    if (!columnKey) return; // تجاهل إذا لم يتم العثور على العمود

    if (sortColumn.value === columnKey) {
        sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
    } else {
        sortColumn.value = columnKey;
        sortDirection.value = "asc";
    }
};

const sortedUsers = computed(() => {
    if (!sortColumn.value) return filteredUsers.value;

    return [...filteredUsers.value].sort((a, b) => {
        const valA = a[sortColumn.value] ?? ""; // معالجة القيم غير المعرفة أو null
        const valB = b[sortColumn.value] ?? ""; // معالجة القيم غير المعرفة أو null

        if (valA < valB) return sortDirection.value === "asc" ? -1 : 1;
        if (valA > valB) return sortDirection.value === "asc" ? 1 : -1;
        return 0;
    });
});

onMounted(() => {
    loading.value = false;
});
watch(limitUser, fetchData); // تحديث البيانات عند تغيير عدد العناصر

const showInfoModel = ref(false);
const showEditModel = ref(false);
const showDeleteModel = ref(false);
const showCreateModel = ref(false);

const modelData = ref({});
const oldRolesData = ref(null);
const openCreateModel = () => {
    showCreateModel.value = true;
    modelData.value = {
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
    };
};
const createData = async (createData) => {
    try {
        console.log("Create Data:", createData);
        await adminStore.updateUser(createData);
        closeModal(true, true);
        viewAlert("success", "User Create successfully!");
        users.value.push(createData);
    } catch (error) {
        console.error("Error updating data:", error);
        viewAlert("error", "Failed to updating user.");
    }
};
const openInfoModel = (data) => {
    showInfoModel.value = true;
    modelData.value = { ...data }; // إنشاء نسخة مستقلة من البيانات
};

const openEditModel = (data) => {
    showEditModel.value = true;
    modelData.value = { ...data }; // إنشاء نسخة مستقلة من البيانات
    oldRolesData.value = data.roles?.[0]?.name || null; // معالجة أمان البيانات
};

const updateData = async (updatedData) => {
    try {
        console.log("Updating Data:", updatedData);
        await adminStore.updateUser(updatedData); // انتظار تحديث المستخدم
        closeModal(true, true);
        viewAlert("success", "User updating successfully!");
        modelData.value = { ...updatedData };
    } catch (error) {
        console.error("Error updating data:", error);
        viewAlert("error", "Failed to updating user.");
    }
};

const openDeleteModel = (data) => {
    showDeleteModel.value = true;
    modelData.value = { ...data }; // إنشاء نسخة مستقلة من البيانات
};

const deleteData = async (data) => {
    console.log("Deleting User:", data);
    closeModal();
    try {
        await adminStore.deleteUser(data);
        viewAlert("success", "User deleted successfully!");
    } catch (error) {
        console.error("Error deleting user:", error);
        // عرض إشعار الخطأ
        viewAlert("error", "Failed to delete user.");
    }
};

const closeModal = (isEdit = false, saveChanges = false) => {
    showInfoModel.value = false;
    showEditModel.value = false;
    showDeleteModel.value = false;
    showCreateModel.value = false;

    if (!isEdit && !saveChanges) {
        if (oldRolesData.value !== null) {
            modelData.value.roles[0].name = oldRolesData.value;
        }
    }
};

const showAlert = ref(false);
const alertTitle = ref("");
const alertMessage = ref("");

const viewAlert = (title, message) => {
    alertTitle.value = title;
    alertMessage.value = message;
    showAlert.value = true;

    // إخفاء الإشعار تلقائيًا بعد 3 ثوانٍ
    setTimeout(() => {
        showAlert.value = false;
    }, 3000);
};
</script>

<template>
    <div v-if="showAlert" class="fixed top-20 right-3 w-1/4 z-50">
        <Alerts :title="alertTitle" :message="alertMessage" />
    </div>
    <div
        class="flex-grow p-4 overflow-scroll touch-scroll bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 min-h-screen"
    >
        <!--  -->
        <div class="container w-10/12 mx-auto">

        <div
            class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between  space-x-4 pb-4 "
        >
            <div class="relative">
                <SearchInput
                    v-model="searchKeyword"
                    placeholder="Search users..."
                    class="w-full"
                >
                    <template #icon>
                        <SearchIcon />
                    </template>
                </SearchInput>
            </div>
            <div>
                <ItemsPerPage
                    :modelValue="limitUser"
                    v-model="limitUser"
                    class=" "
                    @update:modelValue="updateItemsPerPage"
                />

            </div>
        </div>



            <DataTable :data="paginatedUsers" @sort="sort" :loading="loading">
                <template #header>
                    <TabelTh
                        v-for="thNameField in thNameFields"
                        :key="thNameField"
                        :nameFeild="thNameField"
                        @click="sort(thNameField)"
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
                :currentPage="currentPage"
                :totalPages="totalPages"
                @change-page="changePage"
            />
            <button
                @click="openCreateModel()"
                class="fixed bottom-4 right-4 flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                style="position: fixed; bottom: 16px; right: 16px"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                    class="w-6 h-6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 4v16m8-8H4"
                    />
                </svg>
            </button>

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
                @update="updateData"
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
                        v-model="data[column.key][0].name"
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
                <template #message="{ data }">
                    Are you sure you want to delete this user?
                    <strong class="text-red-600">{{ data.name }}</strong>
                </template>
            </DynamicDelete>
            <DynamicCreate
                :columns="columns"
                :show="showCreateModel"
                :data="modelData"
                title="create User"
                @create="createData"
                @close="closeModal"
            >
            </DynamicCreate>
        </div>
    </div>
</template>
