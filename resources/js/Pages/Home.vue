<script setup>
import { useAuthStore } from "@/Stores/auth";
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
const authStore = useAuthStore();
const thNameFields = ["ID", "Name Subject", "Mark"];
const columns = [
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
    <div class="container w-10/12 mx-auto">
        <!-- <div
                class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between space-x-4 pb-4"
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
            </div> -->
        <DataTable :data="authStore.user.user.subjects" :loading="false">
            <template #header>
                <TabelTh
                    v-for="thNameField in thNameFields"
                    :key="thNameField"
                    :nameFeild="thNameField"
                />
            </template>
            <template #row="{ item }">
                <DynamicRow :item="item" :columns="columns">
                    <template #column-mark="{ item }">
                        {{ item["pivot"].mark }}
                    </template>
                </DynamicRow>
            </template>
        </DataTable>

        <!-- <Pagination
                :currentPage="currentPage"
                :totalPages="totalPages"
                @change-page="changePage"
            />
    --></div>
</template>
