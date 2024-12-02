<script setup>
    import Navigation from "@/Layouts/Navigation.vue"
    const menuItems = [
  { name: 'Home' , to:"home" , auth:true},
  { name: 'Login' ,to:"login", auth:false },
  { name: 'Register' ,to:"register" , auth:false },
];


// حالة التحكم في ظهور القائمة المتنقلة

</script>

 <template >
  <div>
    <Navigation @toggle="toggleMobileMenu" :menuItems="menuItems" />
  </div>

    <router-view v-slot="{ Component, route }" >
        <div :key="route.name" class="dark:bg-gray-900 text-gray-900 dark:text-white ">
            <Component :is="Component" class="min-h-[92.96vh] max-h-[92.96vh] " />
        </div>
    </router-view>

</template>
