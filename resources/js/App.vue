<script setup>
    import Navigation from "./Layouts/Navigation.vue"
    const menuItems = [
  { name: 'Home' , to:"home"},
  { name: 'Login' ,to:"login" },
];


// حالة التحكم في ظهور القائمة المتنقلة

</script>

 <template>
  <div>
    <Navigation @toggle="toggleMobileMenu" :menuItems="menuItems" />
  </div>

    <router-view v-slot="{ Component, route }">
        <div :key="route.name">
            <Component :is="Component" />
        </div>
    </router-view>
</template>
