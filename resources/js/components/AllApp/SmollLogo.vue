<template>
    <!-- الشعار يظهر في المنتصف على الشاشات الصغيرة فقط -->
    <div class="flex flex-1 justify-center items-center sm:hidden">
      <img
        class="h-8 w-auto"
        src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=500"
        alt="Your Company"
      />
    </div>
    <!-- الشعار يظهر على اليسار في الشاشات الكبيرة -->
    <div class="hidden sm:flex shrink-0 items-center">
      <img
        class="h-8 w-auto"
        src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=500"
        alt="Your Company"
      />
    </div>
  </template>
