<template>
  <AdminLayout>
    <div class="admin-products-container">
      <div class="admin-header">
        <h1 class="admin-title">Товары</h1>
        <Link
          :href="route('admin.products.create')"
          class="button primary-button"
        >
          Добавить товар
        </Link>
      </div>

      <div v-if="$page.props.flash && $page.props.flash.success" class="alert success-alert">
        {{ $page.props.flash.success }}
      </div>

      <div class="table-wrapper">
        <table class="products-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Изображение</th>
              <th>Название</th>
              <th>Цена</th>
              <th>Категория</th>
              <th>Действия</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in products.data" :key="product.id">
              <td>{{ product.id }}</td>
              <td>
                <img v-if="product.image" :src="`/storage/${product.image}`" alt="Изображение товара" class="product-image">
                <span v-else>Нет изображения</span>
              </td>
              <td>{{ product.name }}</td>
              <td>{{ product.price }} ₽</td>
              <td>{{ product.category }}</td>
              <td class="actions-cell">
                <Link
                  :href="route('admin.products.edit', product.id)"
                  class="action-link edit-link"
                >
                  Редактировать
                </Link>
                <button
                  @click="deleteProduct(product.id)"
                  class="action-button delete-button"
                >
                  Удалить
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="products.links.length > 3" class="pagination-container">
        <template v-for="link in products.links">
          <Link
            v-if="link.url"
            :key="link.label"
            :href="link.url"
            v-html="link.label"
            class="pagination-link"
            :class="{ 'active': link.active, 'disabled': !link.url }"
          />
          <span v-else v-html="link.label" class="pagination-link disabled-text"></span>
        </template>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Pages/AdminPages.vue';
import { Link, router } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
  products: Object
});

const deleteProduct = (id) => {
  if (confirm('Вы уверены, что хотите удалить этот товар?')) {
    console.log('Попытка удалить товар с ID:', id);
    console.log('Генерируемый маршрут:', route('admin.products.destroy', id));

    // --- ИЗМЕНИТЕ ЭТУ СТРОКУ: используйте router.post с _method: 'delete' ---
    router.post(route('admin.products.destroy', id), {
      _method: 'delete', // Явно указываем, что это DELETE запрос
    }, {
      onError: (errors) => {
        console.error("Ошибка при удалении:", errors);
        alert("Не удалось удалить товар. Проверьте консоль.");
      }
    });
  }
};
</script>
<style scoped>
/* Ваши стили остаются без изменений */
.admin-products-container {
  padding: 20px;
}

.admin-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.admin-title {
  font-size: 24px;
  font-weight: bold;
  color: #333;
}

/* Кнопки */
.button {
  padding: 10px 15px;
  border-radius: 5px;
  text-decoration: none;
  font-weight: bold;
  transition: background-color 0.3s ease;
}

.primary-button {
  background-color: #007bff;
  color: white;
  border: none;
}

.primary-button:hover {
  background-color: #0056b3;
}

/* Сообщения */
.alert {
  padding: 15px;
  margin-bottom: 20px;
  border-left: 5px solid;
  border-radius: 4px;
  color: #333;
}

.success-alert {
  background-color: #d4edda;
  border-color: #28a745;
  color: #155724;
}

/* Таблица */
.table-wrapper {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  overflow-x: auto; /* Для прокрутки таблицы на малых экранах */
}

.products-table {
  width: 100%;
  border-collapse: collapse;
}

.products-table th,
.products-table td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.products-table th {
  background-color: #f8f9fa;
  font-size: 12px;
  color: #555;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.products-table tbody tr:hover {
  background-color: #f2f2f2;
}

.product-image {
  width: 64px;
  height: 64px;
  object-fit: cover;
  border-radius: 4px;
}

.actions-cell {
  display: flex;
  gap: 10px;
}

.action-link,
.action-button {
  padding: 6px 10px;
  border-radius: 4px;
  text-decoration: none;
  font-size: 14px;
  transition: background-color 0.3s ease;
  cursor: pointer;
}

.edit-link {
  color: #007bff;
  border: 1px solid #007bff;
  background-color: transparent;
}

.edit-link:hover {
  background-color: #007bff;
  color: white;
}

.delete-button {
  color: #dc3545;
  border: 1px solid #dc3545;
  background-color: transparent;
}

.delete-button:hover {
  background-color: #dc3545;
  color: white;
}

/* Пагинация */
.pagination-container {
  display: flex;
  justify-content: center;
  margin-top: 20px;
  gap: 5px;
}

.pagination-link {
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 5px;
  text-decoration: none;
  color: #007bff;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.pagination-link:hover:not(.active):not(.disabled) {
  background-color: #e9ecef;
}

.pagination-link.active {
  background-color: #007bff;
  color: white;
  border-color: #007bff;
}

.pagination-link.disabled,
.disabled-text {
  color: #6c757d;
  background-color: #e9ecef;
  cursor: not-allowed;
  opacity: 0.6;
}
</style>