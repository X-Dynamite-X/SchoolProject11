<script setup>
import { ref, onMounted } from "vue";
import TabelTh from "@/components/Tabel/TabelTh.vue";
import DataTable from "@/components/Tabel/DataTable.vue";
import DynamicRow from "@/components/Tabel/DynamicRow.vue";
import Alerts from "@/components/AllApp/Alerts.vue";
import InputForm from "@/components/FieldRequst/InputForm.vue";
import LodengSpiner from "@/components/AllApp/LodengSpiner.vue";
import { usePermssionRoleStore } from "@/Stores/permissionRoles";
const permissionRoleStore = usePermssionRoleStore();

const newRole = ref("");
const selectedPermissions = ref([]);
const loading = ref(false);
const roles = ref([]);
const permissions = ref();
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
        roles.value = permissionRoleStore.roles;
        permissions.value = permissionRoleStore.permissions;
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
        const permissions = response.permissions.map(permission => ({
            name: permission
        }));

        // إضافة الدور والأذونات إلى الـ roles
        roles.value.push({
            id: response.role.id,
            name: response.role.name,
            permissions: permissions
        });

        viewAlert("success", response.message);
    } catch (error) {
        console.error("Error create data:", error);
        viewAlert("error", "Failed to Create Role.");
    }

    newRole.value = "";
    selectedPermissions.value = [];
};

 
const thNameUsersFields = ["ID", "Name", "Permissions"];
const columnsUsers = [
    { key: "id", label: "ID", showInTabel: true },
    { key: "name", label: "Name", showInTabel: true },
    { key: "permission", label: "Permissions", showInTabel: true },
];
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
                <DataTable
                    :data="roles"
                    :availableData="false"
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
                        <DynamicRow :item="item" :columns="columnsUsers">
                            <template #column-permission="{ item }">
                                <span
                                    v-for="permission in item.permissions"
                                    :key="permission.id"
                                    class="inline-block px-2 py-1 mr-1 text-xs font-semibold bg-blue-500 text-white rounded dark:bg-blue-600"
                                >
                                    {{ permission.name }}
                                </span>
                            </template>
                        </DynamicRow>
                    </template>
                </DataTable>
            </div>
        </div>
    </template>
</template>
