<template>
  <div class="admin-layout">
    <aside class="sidebar">
      <div class="logo">Админ-панель</div>
      <nav>
        <Link :href="route('admin.products.index')" class="nav-link">
          Товары
        </Link>
        </nav>
    </aside>

    <main class="content">
      <header class="header">
        <div class="user-info">
          {{ $page.props.auth.user.name }}
          <Link :href="route('logout')" method="post" class="logout-btn">
            Выйти
          </Link>
        </div>
      </header>

      <slot />
    </main>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
.admin-layout {
  display: flex;
  min-height: 100vh;
  font-family: Arial, sans-serif; /* Базовый шрифт */
  background-color: #f0f2f5; /* Светлый фон для основного контента */
}

.sidebar {
  width: 250px;
  background: #2c3e50; /* Темный фон для сайдбара */
  color: white;
  padding: 20px 0;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
}

.logo {
  padding: 0 20px 20px;
  font-size: 22px;
  font-weight: bold;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  margin-bottom: 20px;
  text-align: center;
  color: #f5f5f5;
}

.nav-link {
  display: block;
  padding: 12px 20px;
  color: #ececec;
  text-decoration: none;
  transition: background 0.3s ease, color 0.3s ease;
  font-size: 16px;
}

.nav-link:hover {
  background: #34495e; /* Немного светлее при наведении */
  color: #ffffff;
}

.nav-link.active { /* Если вы хотите добавить активное состояние */
  background: #007bff;
  color: white;
}

.content {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.header {
  background: white;
  padding: 15px 25px;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  border-bottom: 1px solid #e0e0e0;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 15px;
  font-size: 15px;
  color: #555;
}

.logout-btn {
  color: #007bff;
  text-decoration: none;
  font-size: 14px;
  padding: 5px 10px;
  border: 1px solid #007bff;
  border-radius: 4px;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.logout-btn:hover {
  background-color: #007bff;
  color: white;
}
</style>