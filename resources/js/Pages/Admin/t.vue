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
import ItemsPerPage from "@/components/FieldRequst/ItemsPerPage.vue";
import Pagination from "@/components/Tabel/Pagination.vue";

const adminStore = useAdminStore();
const loading = ref(true);
const searchKeyword = ref("");
const limitUser = ref(5);
const users = computed(() => adminStore.users);
const totalItems = ref(0);
const fetchData = async () => {
    loading.value = true;
    try {
        await adminStore.getUsers(); // استدعاء الدالة من المخزن
        console.log(users.value.length);

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

onMounted(fetchData);

watch(searchKeyword, (newKeyword) => {
    searchKeyword.value = newKeyword;
});

watch(limitUser, (newLimit) => {
    currentPage.value = 1; // إعادة تعيين الصفحة الحالية إلى 1
    fetchData(); // تحديث البيانات
});

const filteredUsers = computed(() => {
    const keyword = searchKeyword.value.toLowerCase();
    return users.value.filter(
        (user) =>
            user.name.toLowerCase().includes(keyword) ||
            user.email.toLowerCase().includes(keyword)
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
const modelData = ref({});
const oldRolesData = ref(null);

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
        closeModal(true, true);
        await adminStore.updateUser(updatedData); // انتظار تحديث المستخدم
        modelData.value = { ...updatedData }; // تحديث البيانات في النموذج
    } catch (error) {
        console.error("Error updating data:", error);
    }
};

const openDeleteModel = (data) => {
    showDeleteModel.value = true;
    modelData.value = { ...data }; // إنشاء نسخة مستقلة من البيانات
};

const deleteData = async (data) => {
    console.log("Deleting User:", data);
    closeModal();
    await adminStore.deleteUser(data, limitUser.value); // انتظار حذف
};

const closeModal = (isEdit = false, saveChanges = false) => {
    showInfoModel.value = false;
    showEditModel.value = false;
    showDeleteModel.value = false;
    if (!isEdit && !saveChanges) {
        if (oldRolesData.value !== null) {
            modelData.value.roles[0].name = oldRolesData.value;
        }
    }
};
</script>

<template>
    <div
        class="flex-grow p-4 overflow-scroll touch-scroll bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 min-h-screen"
    >
        <div class="container w-10/12 mx-auto">
            <div class="flex items-center mb-6 space-x-4">
                <div class="flex-1">
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

                <!-- اختيار عدد الصفوف -->
                <div class="w-1/6">
                    <ItemsPerPage
                        :modelValue="limitUser"
                        v-model="limitUser"
                        class="ml-4"
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
            <!-- <Pagination :disabled="currentPage === 1" @change-page="changePage" /> -->
            <Pagination
                :currentPage="currentPage"
                :totalPages="totalPages"
                @change-page="changePage"
            />

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
        </div>
    </div>
</template>
