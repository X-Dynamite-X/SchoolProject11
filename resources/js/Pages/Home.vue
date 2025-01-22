<script setup>
import { useAuthStore } from "@/Stores/auth";
import TabelTh from "@/components/Tabel/TabelTh.vue";

import DataTable from "@/components/Tabel/DataTable.vue";
import DynamicRow from "@/components/Tabel/DynamicRow.vue";

import Alerts from "@/components/AllApp/Alerts.vue";
const authStore = useAuthStore();
const thNameUsersFields = ["ID", "name", "email", "roles"];
const columnsUsers = [
    { key: "id", label: "ID", showInTabel: true },
    {
        key: "name",
        name: "name",
        showInTabel: true,
    },
    {
        key: "email",
        name: "email",
        showInTabel: true,
    },
    { key: "roles", showInTabel: true, label: "Role", name: "roles" },
];
const thNameSubjectsFields = ["ID", "Name Subject", "Mark"];
const columnsSubject = [
    { key: "id", label: "ID", showInTabel: true },
    {
        key: "name",
        name: "name",
        showInTabel: true,
    },
    {
        key: "mark",
        showInTabel: true,
    },
];
</script>
<template>
    <div
        class="flex-grow p-4 overflow-scroll touch-scroll bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 max-h-[92vh] h-[92vh] min-h-[92vh]"
    >
        <div class="container w-10/12 mx-auto">
            <div class="py-4 overflow-auto">
                <DataTable
                    :data="authStore?.user"
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
                            <template #column-roles="{ item }">
                                <span
                                    v-for="role in item.roles"
                                    :key="role.id"
                                    class="inline-block px-2 py-1 mr-1 text-xs font-semibold bg-blue-500 text-white rounded dark:bg-blue-600"
                                >
                                    {{ role.name }}
                                </span>
                            </template>
                        </DynamicRow>
                    </template>
                </DataTable>
            </div>
            <div class="py-4">
                <DataTable
                    :data="
                        authStore.user.user.subjects
                            ? authStore.user.user.subjects
                            : []
                    "
                    :loading="false"
                >
                    <template #header>
                        <TabelTh
                            v-for="thNameField in thNameSubjectsFields"
                            :key="thNameField"
                            :nameFeild="thNameField"
                        />
                    </template>
                    <template #row="{ item }">
                        <DynamicRow :item="item" :columns="columnsSubject">
                            <template #column-mark="{ item }">
                                {{ item["pivot"].mark }}
                            </template>
                        </DynamicRow>
                    </template>
                </DataTable>
            </div>
        </div>
    </div>
</template>
