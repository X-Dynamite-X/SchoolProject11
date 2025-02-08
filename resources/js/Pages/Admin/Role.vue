<script setup>
import { ref, computed, onMounted, watch } from "vue";
import TabelTh from "@/components/Tabel/TabelTh.vue";
import DataTable from "@/components/Tabel/DataTable.vue";
import DynamicRow from "@/components/Tabel/DynamicRow.vue";
import Alerts from "@/components/AllApp/Alerts.vue";
import InputForm from "@/components/FieldRequst/InputForm.vue";
import LodengSpiner from "@/components/AllApp/LodengSpiner.vue";
import { usePermssionRoleStore } from "@/Stores/permissionRoles";
import SearchInput from "@/components/FieldRequst/SearchInput.vue";
import SearchIcon from "@/components/Icon/SearchIcon.vue";
import Pagination from "@/components/Tabel/Pagination.vue";
import ItemsPerPage from "@/components/FieldRequst/ItemsPerPage.vue";
import EditIcon from "@/components/Icon/EditIcon.vue";
import DeleteIcon from "@/components/Icon/DeleteIcon.vue";
import DynamicDelete from "@/components/Model/DynamicDelete.vue";
import DynamicEdit from "@/components/Model/DynamicEdit.vue";
import InputCheckBox from "@/components/FieldRequst/InputCheckBox.vue";

const permissionRoleStore = usePermssionRoleStore();

const newRole = ref("");
const selectedPermissions = ref([]);
const loading = ref(false);
const roles = computed(() => permissionRoleStore.roles);
const permissions = ref();
const searchKeyword = ref("");
const limitRole = ref(10);
const totalItems = ref(0);
const currentPage = ref(1);
const sortColumn = ref("id");
const sortDirection = ref("asc");
const fetchData = async () => {
    loading.value = true;
    permissionRoleStore.clearErrors();
    try {
        await permissionRoleStore.getRole();
        await permissionRoleStore.getPermission();
    } catch (error) {
        console.error("Error fetching data:", error);
    } finally {
        loading.value = false;
        // roles.value = permissionRoleStore.roles;
        permissions.value = permissionRoleStore.permissions;
    }
};
watch(searchKeyword, () => {
    currentPage.value = 1; // إعادة تعيين الصفحة الحالية إلى 1
});

// مراقبة التغييرات في عدد العناصر لكل صفحة
watch(limitRole, () => {
    currentPage.value = 1; // إعادة تعيين الصفحة الحالية إلى 1
});

// تصفيح البيانات بناءً على كلمة البحث
const filteredRoles = computed(() => {
    const keyword = searchKeyword.value.toLowerCase().trim();
    return roles.value.filter((role) =>
        role.name.toLowerCase().includes(keyword)
    );
});

// حساب العناصر المعروضة بعد التصفية والفرز
const paginatedRoles = computed(() => {
    const startIndex = (currentPage.value - 1) * limitRole.value;
    const endIndex = startIndex + limitRole.value;
    return sortedRoles.value.slice(startIndex, endIndex);
});

// فرز البيانات بناءً على العمود والاتجاه
const sortedRoles = computed(() => {
    if (!sortColumn.value) return filteredRoles.value;

    return [...filteredRoles.value].sort((a, b) => {
        const valA = a[sortColumn.value] ?? ""; // معالجة القيم غير المعرفة أو null
        const valB = b[sortColumn.value] ?? ""; // معالجة القيم غير المعرفة أو null

        if (valA < valB) return sortDirection.value === "asc" ? -1 : 1;
        if (valA > valB) return sortDirection.value === "asc" ? 1 : -1;
        return 0;
    });
});

// حساب إجمالي عدد الصفحات
const totalPages = computed(() =>
    Math.ceil(filteredRoles.value.length / limitRole.value)
);

// تغيير الصفحة
const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

// تحديث عدد العناصر في الصفحة
const updateItemsPerPage = (newLimit) => {
    limitRole.value = newLimit;
    currentPage.value = 1; // إعادة ضبط الصفحة إلى الأولى
};

// تغيير العمود المستخدم للفرز
const sort = (columnKey) => {
    if (!columnKey) return;

    if (sortColumn.value === columnKey) {
        sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
    } else {
        sortColumn.value = columnKey;
        sortDirection.value = "asc";
    }
};

onMounted(() => {
    fetchData();
});

const createRole = async () => {
    if (!newRole.value.trim()) return;
    permissionRoleStore.clearErrors();
    console.log(selectedPermissions.value);

    try {
        const data = {
            name: newRole.value,
            permissions: selectedPermissions.value,
        };
        const response = await permissionRoleStore.createRole(data);

        // تحويل الأذونات إلى الشكل المطلوب (مصفوفة من الكائنات)
        const permissions = response.permissions.map((permission) => ({
            name: permission,
        }));

        // إضافة الدور والأذونات إلى الـ roles
        roles.value.push({
            id: response.role.id,
            name: response.role.name,
            guard_name: response.role.guard_name,
            permissions: permissions,
        });

        viewAlert("success", response.message);
    } catch (error) {
        console.error("Error create data:", error);
        viewAlert("error", "Failed to Create Role.");
    }

    newRole.value = "";
    selectedPermissions.value = [];
};

const thNameUsersFields = [
    "ID",
    "Name",
    "Permissions",
    "Guard Name",
    "Actions",
];
const columnsRoles = [
    { key: "id", label: "ID", showInTabel: true },
    {
        key: "name",
        label: "Name",
        showInTabel: true,
        showInEdit: true,
        required: true,
    },
    {
        key: "permission",
        label: "Permissions",
        name:"permissions",
        showInTabel: true,
        showInEdit: true,
    },
    { key: "guard_name", label: "guard_name", showInTabel: true },
    { key: "actions" },
];

const showEditModel = ref(false);
const showDeleteModel = ref(false);
const oldRoleData = ref(null);
const modelData = ref({});

const openEditModel = (data) => {
    showEditModel.value = true;
    modelData.value = { ...data };

    // البحث عن العنصر الذي يحتوي على key: "name"
    const nameColumn = columnsRoles.find((col) => col.key === "name");

    if (nameColumn) {
        nameColumn.name = "updateName"; // تغيير الاسم
    }

    oldRoleData.value = data.name || null;
};
const permission_name = ref([]);

const handleSelectedOption = (options) => {
    // تحديث قائمة الأذونات المحددة
    permission_name.value = options;
};

const updateData = async (updatedData) => {
    const data = {
        role: updatedData,
        permissions: permission_name.value, // الصلاحيات المحددة
    };

    try {
        // تنفيذ التحديث عبر المتجر
        const response = await permissionRoleStore.updateRole(data);

        // تحديث بيانات الدور في الجدول
        const index = roles.value.findIndex((role) => role.id === updatedData.id);
        if (index !== -1) {
            roles.value[index] = { ...updatedData }; // استبدال العنصر بالكامل
            roles.value[index].permissions =response.role.permissions;
        }

        closeModal(true, true);
        viewAlert("success", response.message || "The role has been updated successfully.");
    } catch (error) {
        // معالجة الأخطاء وإظهار الإشعار الفاشل
        console.error("Error updating data:", error.message);
        viewAlert("error", error.message || "Failed to update the role.");
    }
};
const openDeleteModel = (data) => {
    console.log(data);

    showDeleteModel.value = true;
    modelData.value = { ...data };
};

const deleteData = async (data) => {
    console.log("Deleting Permission:", data);
    closeModal();
    try {
        const response = await permissionRoleStore.deleteRole(data);
        roles.value = roles.value.filter((role) => role.id !== data.id);
        viewAlert("success", response.message);
    } catch (error) {
        console.error("Error deleting Role :", error);
        // عرض إشعار الخطأ
        viewAlert("error", "Failed to delete Role .");
    }
};
const closeModal = (isEdit = false, saveChanges = false) => {
    if (!isEdit && !saveChanges) {
        if (oldRoleData.value !== null) {
            modelData.value.name = oldRoleData.value;
        }
    }
    showEditModel.value = false;
    showDeleteModel.value = false;

    permissionRoleStore.clearErrors();
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
    <template v-if="showAlert">
        <Alerts :title="alertTitle" :message="alertMessage" />
    </template>
    <template v-if="loading">
        <LodengSpiner />
    </template>

    <template v-else>
        <div class="p-6 bg-gray-100 dark:bg-gray-900 min-h-[92vh]">
            <div
                class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md"
            >
                <h2
                    class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white"
                >
                    Role Management
                </h2>
                <div class="mb-6">
                    <InputForm
                        type="text"
                        name="role"
                        id="role"
                        autocomplete="role"
                        :required="true"
                        placeholder="Role Name"
                        label="Role Name"
                        v-model="newRole"
                        :errorMessage="permissionRoleStore.errors.name || null"
                    />
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">
                        Select Permissions:
                    </label>
                    <div
                        class="items-center ps-4 border overflow-y-scroll h-56 touch-scroll border-gray-200 rounded-sm dark:border-gray-700"
                    >
                        <div class="grid grid-cols-2 gap-2">
                            <div
                                v-for="perm in permissions"
                                :key="perm.id"
                                class="flex items-center ps-4 border border-gray-200 rounded-sm dark:border-gray-700"
                            >
                                <input
                                    type="checkbox"
                                    :id="perm.id"
                                    v-model="selectedPermissions"
                                    :value="perm.name"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                />
                                <label
                                    :for="perm.id"
                                    class="w-full py-4 ms-2 font-medium text-gray-900 dark:text-gray-300"
                                >
                                    {{ perm.name }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <button
                    @click="createRole"
                    :disabled="loading"
                    class="bg-blue-600 dark:bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-700 dark:hover:bg-blue-800"
                >
                    {{ loading ? "Saving..." : "Add Role" }}
                </button>
            </div>

            <!-- Roles table -->
            <div
                class="mt-8 max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md"
            >
                <h3
                    class="text-xl font-semibold mb-4 text-gray-900 dark:text-white"
                >
                    Roles List
                </h3>
                <div
                    class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between space-x-4 pb-4"
                >
                    <div class="relative">
                        <SearchInput
                            v-model="searchKeyword"
                            placeholder="Search Roles..."
                            class="w-full"
                        >
                            <template #icon>
                                <SearchIcon />
                            </template>
                        </SearchInput>
                    </div>
                    <div>
                        <ItemsPerPage
                            :modelValue="limitRole"
                            v-model="limitRole"
                            @update:modelValue="updateItemsPerPage"
                        />
                    </div>
                </div>
                <DataTable
                    :data="paginatedRoles"
                    :availableData="true"
                    :loading="false"
                >
                    <template #header>
                        <TabelTh
                            v-for="thNameField in thNameUsersFields"
                            :key="thNameField"
                            :nameFeild="thNameField"
                        />
                    </template>
                    <template #row="{ item }">
                        <DynamicRow :item="item" :columns="columnsRoles">
                            <template #column-permission="{ item }">
                                <span
                                    v-for="permission in item.permissions"
                                    :key="permission.id"
                                    class="inline-block px-2 py-1 mr-1 text-xs font-semibold bg-blue-500 text-white rounded dark:bg-blue-600"
                                >
                                    {{ permission.name }}
                                </span>
                            </template>
                            <template #column-actions="{ item }">
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
                <DynamicEdit
                    :data="modelData"
                    :columns="columnsRoles"
                    :show="showEditModel"
                    @close="closeModal"
                    title="Edit Role"
                    @update="updateData"
                    :errors="permissionRoleStore"
                >
                    <template #column-permission="{ data ,column }">
                        <InputCheckBox
                            :multiple="column.multiple"
                            :trueValueOptions="data.permissions"
                            :options="permissions"
                            :errorMessage="permissionRoleStore"
                            :id="column.id"
                            :name="column.name"
                            :disabled="column.disabled"
                            :required="column.required"
                            :label="column.label"
                            @create="handleSelectedOption"
                            WhatValueNeed="name"
                            nameError="permissions"
                        >
                        </InputCheckBox>
                    </template>
                </DynamicEdit>
                <DynamicDelete
                    :data="modelData"
                    :show="showDeleteModel"
                    @close="closeModal"
                    @delete="deleteData"
                    title="Delete Role"
                >
                    <template #message="{ data }">
                        Are you sure you want to delete this Role?
                        <strong class="text-red-600">{{ data.name }}</strong>
                    </template>
                </DynamicDelete>
            </div>
        </div>
    </template>
</template>
