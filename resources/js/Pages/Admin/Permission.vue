<script setup>
import { ref } from "vue";
import TabelTh from "@/components/Tabel/TabelTh.vue";
import DataTable from "@/components/Tabel/DataTable.vue";
import DynamicRow from "@/components/Tabel/DynamicRow.vue";
import Alerts from "@/components/AllApp/Alerts.vue";
import LodengSpiner from "@/components/AllApp/LodengSpiner.vue";

const newPermission = ref("");
const loading = ref(false);

const permissions = ref([
    { id: 1, name: "User Management" },
    { id: 2, name: "Delete" },
    { id: 3, name: "Read" },
    { id: 4, name: "Write" },
    { id: 5, name: "Subject Management" },
]);

const createPermission = () => {
    if (!newPermission.value.trim()) return;

    permissions.value.push({
        id: permissions.value.length + 1,
        name: newPermission.value,
    });
    newPermission.value = "";
};

const thNamePermissionsFields = ["ID", "Name"];
const columnsPermissions = [
    { key: "id", label: "ID", showInTabel: true },
    { key: "name", label: "Name", showInTabel: true },
];
</script>

<template>
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
                    <label class="block text-gray-700 dark:text-gray-300">
                        Permission Name:
                    </label>
                    <input
                        type="text"
                        v-model="newPermission"
                        placeholder="Enter permission name"
                        class="w-full p-2 border rounded mt-2 bg-gray-50 dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600"
                    />
                </div>

                <button
                    @click="createPermission"
                    :disabled="isLoading"
                    class="bg-blue-600 dark:bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-700 dark:hover:bg-blue-800"
                >
                    {{ isLoading ? "Saving..." : "Add Permission" }}
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
                        <DynamicRow
                            :item="item"
                            :columns="columnsPermissions"
                        />
                    </template>
                </DataTable>
            </div>
        </div>
    </template>
</template>
