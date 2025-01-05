<script setup>
import { ref, watch } from "vue";
defineProps({
    options: {
        type: Array,
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
    id: {
        type: String,
        required: true,
    },
    required: {
        type: Boolean,
        default: false,
    },
    errorMessage: {
        type: Array,
        default: "",
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    multiple: {
        type: Boolean,
        default: false,
    },
    label: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["create"]);

function addOptionIds(option) {
    console.log("Option selected:", option);
    emit("create", option);
}
</script>

<template>
    <form class="max-w-sm mx-auto" method="post" >
        <label
            for="countries"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >{{ label }}</label
        >
        <select
            :multiple="multiple"
            :id="id"
            :name="name"
            :required="required"
            :disabled="disabled"
            @change="addOptionIds($event.target.value)"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        >
            <option
                @click="addOptionIds($event.target.value)"
                v-for="option in options"
                :key="option.id"
                :value="option.id"
            >
                {{ option.name }}
            </option>
        </select>
    </form>
</template>
