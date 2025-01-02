<script setup>
import { onMounted, ref, defineEmits, computed, watch } from "vue";
import { useAdminStore } from "@/Stores/admin";
import TabelTh from "@/components/Tabel/TabelTh.vue";
import SearchInput from "@/components/FieldRequst/SearchInput.vue";
import SearchIcon from "@/components/Icon/SearchIcon.vue";
import DataTable from "@/components/Tabel/DataTable.vue";
import DynamicRow from "@/components/Tabel/DynamicRow.vue";
import EditIcon from "@/components/Icon/EditIcon.vue";
import DeleteIcon from "@/components/Icon/DeleteIcon.vue";
import DynamicEdit from "@/components/Model/DynamicEdit.vue";
import DynamicDelete from "@/components/Model/DynamicDelete.vue";
import DynamicCreate from "@/components/Model/DynamicCreate.vue";
import ItemsPerPage from "@/components/FieldRequst/ItemsPerPage.vue";
import Pagination from "@/components/Tabel/Pagination.vue";
import Alerts from "@/components/AllApp/Alerts.vue";
// Props

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    title: {
        type: String,
        default: "Info",
    },
    data: {
        type: Object,
        required: true,
    },
});
const emit = defineEmits(["close"]);

// Functions
const closeTableInModal = () => {
    emit("close"); // يجب تعريف emit هنا
};

//######################################################################3
const adminStore = useAdminStore();
const loading = ref(true);
const searchKeyword = ref("");
const limitSubjectUsers = ref(10);
const subjectUsers = ref(props.data);
const totalItems = ref(0);

const currentPage = ref(1); // الصفحة الحالية
const sortColumn = ref(null); // العمود المستخدم للفرز
const sortDirection = ref("asc");
const thNameFields = ["ID", "Student Name", "Mark", "Actions"];
const columns = [
    { key: "id", label: "ID", showInTabel: true },
    {
        key: "name",
        label: "Student Name",
        name: "name",
        type: "text",
        showInCreate: true,
        showInEdit: true,
        required: true,
        showInTabel: true,
        disabled: true,
        placeholder: "Enter Name",
    },
    {
        key: "pivot_mark",
        label: "Mark",
        name: "mark",
        type: "number",
        showInCreate: true,
        showInEdit: true,
        required: true,
        showInTabel: true,
        disabled: false,
        placeholder: "Enter Success Mark",
    },
];
watch(
    () => props.data,
    (newData) => {
        subjectUsers.value = newData;
    },
    { immediate: true }
);

watch(searchKeyword, (newKeyword) => {
    searchKeyword.value = newKeyword;
    currentPage.value = 1; // إعادة تعيين الصفحة الحالية إلى 1
});

watch(limitSubjectUsers, (newLimit) => {
    currentPage.value = 1; // إعادة تعيين الصفحة الحالية إلى 1
});

const filteredSubjectUsers = computed(() => {
    const keyword = searchKeyword.value.toLowerCase().trim();
    console.log(keyword);

    return subjectUsers.value.filter((subjectUser) =>
        subjectUser.name.toLowerCase().includes(keyword)
    );
});

const paginatedSubjectUsers = computed(() => {
    const startIndex = Number(
        (currentPage.value - 1) * limitSubjectUsers.value
    ); // تحويل إلى عدد
    const endIndex = startIndex + Number(limitSubjectUsers.value); // تحويل إلى عدد
    return sortedSubjectUsers.value.slice(startIndex, endIndex);
});

const totalPages = computed(() =>
    Math.ceil(filteredSubjectUsers.value.length / limitSubjectUsers.value)
);
// تغيير الصفحة
const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

// تحديث عدد العناصر في الصفحة
const updateItemsPerPage = (newLimit) => {
    limitSubjectUsers.value = newLimit;
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

const sortedSubjectUsers = computed(() => {
    if (!sortColumn.value) return filteredSubjectUsers.value;

    return [...filteredSubjectUsers.value].sort((a, b) => {
        const valA = a[sortColumn.value] ?? ""; // معالجة القيم غير المعرفة أو null
        const valB = b[sortColumn.value] ?? ""; // معالجة القيم غير المعرفة أو null

        if (valA < valB) return sortDirection.value === "asc" ? -1 : 1;
        if (valA > valB) return sortDirection.value === "asc" ? 1 : -1;
        return 0;
    });
});

const showEditModel = ref(false);
const showDeleteModel = ref(false);
const showCreateModel = ref(false);
const modelData = ref({});
const oldRolesData = ref(null);
const openCreateModel = () => {
    showCreateModel.value = true;
    "Name",
        "Success Mark",
        "Full Mark",
        (modelData.value = {
            name: "",
            success_mark: "",
            full_mark: "",
        });
};
const createData = async (createData) => {
    try {
        console.log("Create Data:", createData);
        await adminStore.createSubject(createData);
        closeModal(true, true);
        viewAlert("success", "subject Create successfully!");
    } catch (error) {
        viewAlert("error", "Failed to updating subject.");
    }
};

const openEditModel = (data) => {
    showEditModel.value = true;
    console.log(data);
    modelData.value = { ...data };
    console.log(modelData.value.pivot.mark);
};
const updateData = async (updatedData) => {
    if (updatedData.pivot_mark) {
        let data = {
            user_id: updatedData.pivot.user_id,
            mark: updatedData.pivot_mark,
            subject_id: updatedData.pivot.subject_id,
        };

        try {
            await adminStore.updateSubjectUsers(data); // تنفيذ التحديث عبر المتجر
            const index = subjectUsers.value.findIndex(
                (subjectUser) => subjectUser.id === updatedData.id
            );
            if (index !== -1) {
                console.log(subjectUsers.value[index]["pivot"].mark);

                subjectUsers.value[index]["pivot"].mark =
                    updatedData.pivot_mark; // استبدال العنصر بالكامل
            }
            closeModal(true, true);
            viewAlert("success", "Subject updated successfully!");
        } catch (error) {
            console.error("Error updating data:", error);
            viewAlert("error", "Failed to update subject.");
        }
    } else {
        closeModal();
    }
};

const openDeleteModel = (data) => {
    showDeleteModel.value = true;
    modelData.value = { ...data }; // إنشاء نسخة مستقلة من البيانات
};
const deleteData = async (data) => {
    let DeleteData = {
        user_id: data.pivot.user_id,
        subject_id: data.pivot.subject_id,
    };
    closeModal();
    try {
        let x = await adminStore.deleteSubjectUsers(DeleteData);

        let index = subjectUsers.value.findIndex(
            (item) => item.id === DeleteData.user_id
        );
        if (index !== -1) {
            subjectUsers.value.splice(index, 1);
        }

        viewAlert("success", "Subject deleted successfully!");
    } catch (error) {
        console.error("Error deleting Subject:", error);
        // عرض إشعار الخطأ
        viewAlert("error", "Failed to delete Subject.");
    }
};
const closeModal = (isEdit = false, saveChanges = false) => {
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
        v-if="show"
        class="fixed inset-0 z-10 overflow-y-auto bg-gray-800 bg-opacity-50 dark:bg-opacity-80 flex justify-center items-center"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <div
            class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow-xl transition-all w-full sm:max-w-4xl"
        >
            <!-- Header -->
            <div
                class="px-6 py-4 border-b border-gray-300 dark:border-gray-700"
            >
                <h3
                    class="text-lg font-semibold text-gray-900 dark:text-gray-100 text-center"
                    id="modal-title"
                >
                    {{ title }}
                </h3>
            </div>

            <!-- Content -->
            <div class="px-6 py-4">
                <!-- Filters -->
                <div
                    class="flex flex-col sm:flex-row sm:justify-between items-center gap-4"
                >
                    <SearchInput
                        v-model="searchKeyword"
                        placeholder="Search Subjects..."
                        class="w-full sm:w-1/2"
                    >
                        <template #icon>
                            <SearchIcon />
                        </template>
                    </SearchInput>

                    <ItemsPerPage
                        :modelValue="limitSubjectUsers"
                        v-model="limitSubjectUsers"
                        @update:modelValue="updateItemsPerPage"
                        class="w-full sm:w-auto"
                    />
                </div>

                <!-- Table -->
                <div class="mt-4">
                    <div
                        class="max-h-[35rem] touch-scroll overflow-auto border border-gray-300 dark:border-gray-700 rounded-md"
                    >
                        <DataTable :data="paginatedSubjectUsers" @sort="sort">
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
                                    <template #column-pivot_mark="{ item }">
                                        {{ item.pivot.mark }}
                                    </template>
                                    <template #column-actions="{ item }">
                                        <button
                                            :id="item.id"
                                            @click="openEditModel(item)"
                                            class="p-2 text-yellow-600 dark:text-yellow-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                        >
                                            <EditIcon />
                                        </button>
                                        <button
                                            @click="openDeleteModel(item)"
                                            :id="item.id"
                                            class="p-2 text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                        >
                                            <DeleteIcon />
                                        </button>
                                    </template>
                                </DynamicRow>
                            </template>
                        </DataTable>
                    </div>

                    <!-- Pagination -->
                    <Pagination
                        :currentPage="currentPage"
                        :totalPages="totalPages"
                        @change-page="changePage"
                        class="mt-4"
                    />
                </div>
            </div>

            <!-- Footer -->
            <div
                class="bg-gray-50 dark:bg-gray-700 px-6 py-3 flex justify-end space-x-4"
            >
                <button
                    type="button"
                    class="inline-flex justify-center rounded-md bg-gray-200 dark:bg-gray-600 px-4 py-2 text-sm font-semibold text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-500"
                    @click="closeTableInModal"
                >
                    Cancel
                </button>
                <slot name="actions"></slot>
            </div>

            <DynamicEdit
                :data="modelData"
                :columns="columns"
                :show="showEditModel"
                @close="closeModal"
                title="Edit Subject"
                @update="updateData"
                :data2="modelData.pivot"
            >
            </DynamicEdit>
            <DynamicDelete
                :data="modelData"
                :columns="columns"
                :show="showDeleteModel"
                @close="closeModal"
                @delete="deleteData"
                title="Delete Subject"
            >
                <template #message="{ data }">
                    Are you sure you want to delete this user
                    <strong class="text-red-600">{{ data.name }}</strong>
                    in the Subject
                    <strong class="text-red-600">{{ title }}</strong>
                    ?
                </template>
            </DynamicDelete>
            <DynamicCreate
                :columns="columns"
                :show="showCreateModel"
                :data="modelData"
                title="create Subject"
                @create="createData"
                @close="closeModal"
            >
            </DynamicCreate>
        </div>
        <button
            @click="openCreateModel"
            class="fixed bottom-4 right-4 flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
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
    </div>
</template>
