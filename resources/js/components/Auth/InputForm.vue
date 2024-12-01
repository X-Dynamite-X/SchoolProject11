<script setup>
defineProps({
  modelValue: {
    type: String,
    required: true,
  },
  name: {
    type: String,
    required: true,
  },
  type: {
    type: String,
    default: "text",
  },
  autocomplete: {
    type: String,
    default: "off",
  },
  id: {
    type: String,
    required: true,
  },
  label: {
    type: String,
    required: true,
  },
  placeholder: {
    type: String,
    default: "",
  },
  required: {
    type: Boolean,
    default: false,
  },
  errorMessage: {
    type: Array,
    default: "",
  },
});

const emit = defineEmits(["update:modelValue"]);
</script>

<template>
  <div>
    <!-- التسمية -->
    <label
      :for="id"
      class="block text-sm font-medium text-gray-700 dark:text-gray-200"
    >
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>

    <!-- الحقل -->
    <div class="mt-2">
      <input
        :type="type"
        :name="name"
        :id="id"
        :autocomplete="autocomplete"
        :placeholder="placeholder"
        :required="required"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 sm:text-base dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
      />
    </div>

    <!-- رسالة الخطأ -->

    <div v-for="error in errorMessage" :key="error"  >

    <p v-if="error"
      class="mt-1 text-sm text-red-600 dark:text-red-500"
    >
      {{ error }}
    </p>
    </div>
  </div>
</template>
