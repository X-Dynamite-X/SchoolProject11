<script setup>
import { ref } from "vue";
import TabelTh from "@/components/Tabel/TabelTh.vue";
import DataTable from "@/components/Tabel/DataTable.vue";
import DynamicRow from "@/components/Tabel/DynamicRow.vue";
import Alerts from "@/components/AllApp/Alerts.vue";

const newRole = ref("");
const selectedPermissions = ref([]);
const isLoading = ref(false);

// Sample data for roles
const roles = ref([
    {
        id: 1,
        name: "Admin",
        permissions: [
            { id: 1, name: "User Management" },
            { id: 2, name: "Settings Management" },
            { id: 5, name: "Write" },
        ],
    },
    {
        id: 2,
        name: "User",
        permissions: [{ id: 4, name: "Read" }],
    },
]);

// Sample data for permissions
const permissions = ref([
    { id: 1, name: "User Management" },
    { id: 2, name: "Delete" },
    { id: 3, name: "Read" },
    { id: 4, name: "Write" },
    { id: 5, name: "Subject Management" },
]);

const createRole = () => {
    if (!newRole.value.trim()) return;
    isLoading.value = true;
    setTimeout(() => {
        roles.value.push({
            id: roles.value.length + 1,
            name: newRole.value,
            permissions: permissions.value.filter((p) =>
                selectedPermissions.value.includes(p.id)
            ),
        });
        newRole.value = "";
        selectedPermissions.value = [];
        isLoading.value = false;
    }, 1000);
};

const thNameUsersFields = ["ID", "Name", "Permissions"];
const columnsUsers = [
    { key: "id", label: "ID", showInTabel: true },
    { key: "name", label: "Name", showInTabel: true },
    { key: "permission", label: "Permissions", showInTabel: true },
];
</script>

<template>
    <div class="p-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div
            class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md"
        >
            <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">
                Role Management
            </h2>

            <!-- Form for adding a new role -->
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300">Role Name:</label>
                <input
                    type="text"
                    v-model="newRole"
                    placeholder="Enter role name"
                    class="w-full p-2 border rounded mt-2 bg-gray-50 dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600"
                />
            </div>

            <!-- Permissions selection -->
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 mb-2">
                    Select Permissions:
                </label>
                <div class="grid grid-cols-2 gap-2">
                    <label
                        v-for="perm in permissions"
                        :key="perm.id"
                        class="flex items-center text-gray-900 dark:text-gray-300"
                    >
                        <input
                            type="checkbox"
                            v-model="selectedPermissions"
                            :value="perm.id"
                            class="mr-2"
                        />
                        {{ perm.name }}
                    </label>
                </div>
            </div>

            <button
                @click="createRole"
                :disabled="isLoading"
                class="bg-blue-600 dark:bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-700 dark:hover:bg-blue-800"
            >
                {{ isLoading ? "Saving..." : "Add Role" }}
            </button>
        </div>

        <!-- Roles table -->
        <div
            class="mt-8 max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md"
        >
            <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">
                Roles List
            </h3>
            <DataTable :data="roles" :availableData="false" :loading="false">
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
