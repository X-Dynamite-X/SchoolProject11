<script setup>
import { ref, computed, onMounted } from "vue";
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

import { usePermssionRoleStore } from "@/Stores/permissionRoles";
const permissionRoleStore = usePermssionRoleStore();
const newPermission = ref("");
const loading = ref(false);
const permissions = ref([]);

const fetchData = async () => {
    loading.value = true;
    try {
        await permissionRoleStore.getPermission();
    } catch (error) {
        console.error("Error fetching data:", error);
    } finally {
        loading.value = false;
        permissions.value = permissionRoleStore.permissions;
    }
};
onMounted(() => {
    fetchData();
});
const createPermission = () => {
    if (!newPermission.value.trim()) return;
    permissionRoleStore.clearErrors();
    permissionRoleStore.createPermission(newPermission.value);
    newPermission.value = "";
};
const thNamePermissionsFields = ["ID", "Name", "Actions"];
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
];
const showEditModel = ref(false);
const showDeleteModel = ref(false);
const oldPermissionData = ref(null);
const modelData = ref({});

const openEditModel = (data) => {
    showEditModel.value = true;
    modelData.value = { ...data };
    oldPermissionData.value = data.name || null;
};
const updateData = async (updatedData) => {
    try {
        const response = await permissionRoleStore.updatePermission(updatedData); // تنفيذ التحديث عبر المتجر
        const index = subjects.value.findIndex(
            (subject) => subject.id === updatedData.id
        );
        if (index !== -1) {
            subjects.value[index] = { ...updatedData }; // استبدال العنصر بالكامل
        }
        closeModal(true, true);
        viewAlert("success", response.message);
    } catch (error) {
        console.error("Error updating data:", error);
        viewAlert("error", "Failed to update subject.");
    }
};
const openDeleteModel = (data) => {
    showDeleteModel.value = true;
    modelData.value = { ...data };
};
const deleteData = async (data) => {
    console.log("Deleting Subject:", data);
    closeModal();
    try {
        const response = await permissionRoleStore.deletePermission(data);
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
</script>

<template>
    <template v-if="showAlert">
        <Alerts :title="alertTitle" :message="alertMessage" />
    </template>

    <template v-if="loading">
        <LodengSpiner />
    </template>
    <template v-else>
        <div class="p-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
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
                <DataTable
                    :data="permissions"
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
                        <DynamicRow :item="item" :columns="columnsPermissions">
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
                <DynamicEdit
                    :data="modelData"
                    :columns="columnsPermissions"
                    :show="showEditModel"
                    @close="closeModal"
                    title="Edit Subject"
                    @update="updateData"
                >
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
                        Are you sure you want to delete this Permission?
                        <strong class="text-red-600">{{ data.name }}</strong>
                    </template>
                </DynamicDelete>
            </div>
        </div>
    </template>
</template>
