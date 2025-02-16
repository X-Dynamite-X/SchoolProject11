<script setup>
import { ref, computed, onMounted, watch } from "vue";
import TabelTh from "@/components/Tabel/TabelTh.vue";
import DataTable from "@/components/Tabel/DataTable.vue";
import DynamicRow from "@/components/Tabel/DynamicRow.vue";
import Alerts from "@/components/AllApp/Alerts.vue";
import LodengSpiner from "@/components/AllApp/LodengSpiner.vue";
import InputForm from "@/components/FieldRequst/InputForm.vue";
import DynamicDelete from "@/components/Model/DynamicDelete.vue";
import DynamicEdit from "@/components/Model/DynamicEdit.vue";
import EditIcon from "@/components/Icon/EditIcon.vue";
import DeleteIcon from "@/components/Icon/DeleteIcon.vue";
import SearchInput from "@/components/FieldRequst/SearchInput.vue";
import SearchIcon from "@/components/Icon/SearchIcon.vue";
import Pagination from "@/components/Tabel/Pagination.vue";
import ItemsPerPage from "@/components/FieldRequst/ItemsPerPage.vue";
import { usePermssionRoleStore } from "@/Stores/permissionRoles";
const permissionRoleStore = usePermssionRoleStore();
const newPermission = ref("");
const loading = ref(false);
const permissions = computed(() => permissionRoleStore.permissions);
const searchKeyword = ref("");
const limitPermission = ref(10);
const totalItems = ref(0);
const currentPage = ref(1);
const sortColumn = ref("id");
const sortDirection = ref("asc");

// جلب البيانات من المتجر
const fetchData = async () => {
    loading.value = true;
    try {
        await permissionRoleStore.getPermission();
    } catch (error) {
        console.error("Error fetching data:", error);
    } finally {
        loading.value = false;
    }
};
watch(searchKeyword, () => {
    currentPage.value = 1; // إعادة تعيين الصفحة الحالية إلى 1
});

// مراقبة التغييرات في عدد العناصر لكل صفحة
watch(limitPermission, () => {
    currentPage.value = 1; // إعادة تعيين الصفحة الحالية إلى 1
});

// تصفيح البيانات بناءً على كلمة البحث
const filteredPermissions = computed(() => {
    const keyword = searchKeyword.value.toLowerCase().trim();
    return permissions.value.filter((permission) =>
        permission.name.toLowerCase().includes(keyword)
    );
});

// حساب العناصر المعروضة بعد التصفية والفرز
const paginatedPermissions = computed(() => {
    const startIndex = (currentPage.value - 1) * limitPermission.value;
    const endIndex = startIndex + limitPermission.value;
    return sortedPermissions.value.slice(startIndex, endIndex);
});

// حساب إجمالي عدد الصفحات
const totalPages = computed(() =>
    Math.ceil(filteredPermissions.value.length / limitPermission.value)
);

// تغيير الصفحة
const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

// تحديث عدد العناصر في الصفحة
const updateItemsPerPage = (newLimit) => {
    limitPermission.value = newLimit;
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

// فرز البيانات بناءً على العمود والاتجاه
const sortedPermissions = computed(() => {
    if (!sortColumn.value) return filteredPermissions.value;

    return [...filteredPermissions.value].sort((a, b) => {
        const valA = a[sortColumn.value] ?? ""; // معالجة القيم غير المعرفة أو null
        const valB = b[sortColumn.value] ?? ""; // معالجة القيم غير المعرفة أو null

        if (valA < valB) return sortDirection.value === "asc" ? -1 : 1;
        if (valA > valB) return sortDirection.value === "asc" ? 1 : -1;
        return 0;
    });
});

onMounted(() => {
    fetchData();
});
const createPermission = async () => {
    if (!newPermission.value.trim()) return;
    permissionRoleStore.clearErrors();
    try {
        const response = await permissionRoleStore.createPermission(
            newPermission.value
        );
        permissions.value.push(response.data);
        viewAlert("success", response.message);
    } catch (error) {
        console.error("Error create data:", error);
        viewAlert("error", "Failed to Create Permission.");
    }
    newPermission.value = "";
};

const thNamePermissionsFields = ["ID", "Name", "Guard Name", "Actions"];
const columnsPermissions = [
    { key: "actions" },
    { key: "id", label: "ID", showInTabel: true },
    {
        key: "name",
        label: "Name",
        name: "name",
        type: "text",
        showInCreate: true,
        showInEdit: true,
        required: true,
        showInTabel: true,
        disabled: false,
        placeholder: "Enter Name",
    },
    { key: "guard_name", label: "guard_name", showInTabel: true },
];
const showEditModel = ref(false);
const showDeleteModel = ref(false);
const oldPermissionData = ref(null);
const modelData = ref({});

const openEditModel = (data) => {
    showEditModel.value = true;
    modelData.value = { ...data };

    // البحث عن العنصر الذي يحتوي على key: "name"
    const nameColumn = columnsPermissions.find((col) => col.key === "name");

    if (nameColumn) {
        nameColumn.name = "updateName"; // تغيير الاسم
    }

    oldPermissionData.value = data.name || null;
};

const updateData = async (updatedData) => {
    // console.log(updatedData);

    try {
        const response = await permissionRoleStore.updatePermission(
            updatedData
        ); // تنفيذ التحديث عبر المتجر
        const index = permissions.value.findIndex(
            (permission) => permission.id === updatedData.id
        );
        if (index !== -1) {
            permissions.value[index] = { ...updatedData }; // استبدال العنصر بالكامل
        }
        closeModal(true, true);
        viewAlert("success", response.message);
    } catch (error) {
        console.error("Error updating data:", error);
        viewAlert("error", "Failed to update Permission.");
    }
};
const openDeleteModel = (data) => {
    showDeleteModel.value = true;
    modelData.value = { ...data };
};

const deleteData = async (data) => {
    console.log("Deleting Permission:", data);
    closeModal();
    try {
        const response = await permissionRoleStore.deletePermission(data);
        permissions.value = permissions.value.filter(
            (permission) => permission.id !== data.id
        );
        viewAlert("success", response.message);
    } catch (error) {
        console.error("Error deleting Permission :", error);
        // عرض إشعار الخطأ
        viewAlert("error", "Failed to delete Permission .");
    }
};
const closeModal = (isEdit = false, saveChanges = false) => {
    if (!isEdit && !saveChanges) {
        if (oldPermissionData.value !== null) {
            modelData.value.name = oldPermissionData.value;
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
        <div class="p-6 bg-gray-100 dark:bg-gray-900 min-h-full max-h-[92vh] overflow-y-scroll touch-scroll  h-full  ">
            <div
                class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md"
            >
                <h2
                    class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white"
                >
                    Permission Management
                </h2>

                <!-- نموذج إضافة صلاحية جديدة -->
                <div class="mb-6">
                    <InputForm
                        type="text"
                        name="permission"
                        id="permission"
                        autocomplete="permission"
                        :required="true"
                        placeholder="Permission Name"
                        label="Permission Name"
                        v-model="newPermission"
                        :errorMessage="permissionRoleStore.errors.name || null"
                    />
                </div>

                <button
                    @click="createPermission"
                    :disabled="loading"
                    class="bg-blue-600 dark:bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-700 dark:hover:bg-blue-800"
                >
                    {{ loading ? "Saving..." : "Add Permission" }}
                </button>
            </div>

            <!-- جدول الصلاحيات -->
            <div
                class="mt-8 max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md"
            >
                <h3
                    class="text-xl font-semibold mb-4 text-gray-900 dark:text-white"
                >
                    Permissions List
                </h3>
                <div
                    class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between space-x-4 pb-4"
                >
                    <div class="relative">
                        <SearchInput
                            v-model="searchKeyword"
                            placeholder="Search Permissions..."
                            class="w-full"
                        >
                            <template #icon>
                                <SearchIcon />
                            </template>
                        </SearchInput>
                    </div>
                    <div>
                        <ItemsPerPage
                            :modelValue="limitPermission"
                            v-model="limitPermission"
                            @update:modelValue="updateItemsPerPage"
                        />
                    </div>
                </div>
                <DataTable
                    :data="paginatedPermissions"
                    :availableData="false"
                    :loading="false"
                >
                    <template #header>
                        <TabelTh
                            v-for="thNameField in thNamePermissionsFields"
                            :key="thNameField"
                            :nameFeild="thNameField"
                        />
                    </template>
                    <template #row="{ item }">
                        <DynamicRow :item="item"  :columns="columnsPermissions">
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
                    :columns="columnsPermissions"
                    :show="showEditModel"
                    @close="closeModal"
                    title="Edit Permission"
                    @update="updateData"
                    :errors="permissionRoleStore"
                >
                </DynamicEdit>
                <DynamicDelete
                    :data="modelData"
                    :show="showDeleteModel"
                    @close="closeModal"
                    @delete="deleteData"
                    title="Delete Permission"
                >
                    <template #message="{ data }">
                        Are you sure you want to delete this Permission?
                        <strong class="text-red-600">{{ data.name }}</strong>
                    </template>
                </DynamicDelete>
            </div>
        </div>
    </template>
</template>
