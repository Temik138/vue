<template>
  <div class="auth-page">
    <AppHeader />
    <section class="auth-section profile-section">
      <div class="auth-card profile-card">
        <h2 class="auth-title">МОИ ЗАКАЗЫ</h2>

        <div v-if="flash.success" class="alert success-alert">
          {{ flash.success }}
        </div>
        <div v-if="flash.error" class="alert error-alert">
          {{ flash.error }}
        </div>

        <nav class="profile-tabs">
          <Link :href="route('profile.edit')" class="tab-link">Профиль</Link>
          <Link :href="route('profile.orders.index')" class="tab-link active">Мои заказы</Link>
          </nav>

        <div v-if="orders.length > 0" class="orders-list">
          <div v-for="order in orders" :key="order.id" class="order-card">
            <div class="order-header">
              <h3>Заказ № {{ order.id }}</h3>
              <p>Статус: <span :class="['status-badge', 'status-' + order.status]">{{ order.status }}</span></p>
              <p>Дата: {{ new Date(order.created_at).toLocaleDateString() }}</p>
              <p>Общая сумма: {{ order.total_amount }} ₽</p>
            </div>
            <div class="order-items">
              <h4>Товары в заказе:</h4>
              <ul>
                <li v-for="item in order.order_items" :key="item.id" class="order-item">
                  <img v-if="item.product && item.product.image" :src="`/storage/${item.product.image}`" alt="Изображение товара" class="order-item-image">
                  <span v-else class="order-item-image-placeholder">Нет фото</span>
                  <div>
                    <p class="item-name">{{ item.product ? item.product.name : 'Товар удален' }}</p>
                    <p class="item-details">{{ item.quantity }} шт. x {{ item.price_per_unit }} ₽</p>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div v-else class="no-orders-message">
          <p>У вас пока нет оформленных заказов.</p>
          <Link :href="route('catalog')" class="button primary-button">Начать покупки</Link>
        </div>

      </div>
    </section>
    <AppFooter />
  </div>
</template>

<script setup>
import AppHeader from '@/Pages/Header.vue';
import AppFooter from '@/Pages/Footer.vue';
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
  orders: Array,
  flash: Object, // Для флеш-сообщений
});
</script>

<style scoped>
/* Стили для личного кабинета (скопированы из Profile/Edit.vue) */
.auth-page {
  background-color: #884535;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.auth-section {
  flex-grow: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 40px 20px;
}

.auth-card {
  background-color: #7a3a2d;
  border: dashed 2px white;
  border-radius: 8px;
  padding: 40px;
  max-width: 900px; /* Увеличьте ширину для отображения заказов */
  width: 100%;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  color: white;
  font-family: "Montserrat", sans-serif;
}

.auth-title {
  font-size: 32px;
  color: #ffd700;
  text-align: center;
  margin-bottom: 30px;
  font-weight: bold;
}

/* Стили для табов */
.profile-tabs {
  display: flex;
  justify-content: center;
  margin-bottom: 30px;
  border-bottom: 2px solid rgba(255, 255, 255, 0.3);
  padding-bottom: 10px;
}

.tab-link {
  padding: 10px 20px;
  text-decoration: none;
  color: white;
  font-weight: bold;
  border-radius: 5px 5px 0 0;
  transition: background-color 0.3s ease;
}

.tab-link:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.tab-link.active {
  background-color: rgba(255, 255, 255, 0.2);
  border-bottom: 2px solid #ffd700;
  color: #ffd700;
}

/* Стили для списка заказов */
.orders-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.order-card {
  background-color: #6a2a22; /* Чуть светлее, чем основной фон карточки */
  border: dashed 1px rgba(255, 255, 255, 0.5);
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.order-header {
  border-bottom: 1px solid rgba(255, 255, 255, 0.3);
  padding-bottom: 15px;
  margin-bottom: 15px;
}

.order-header h3 {
  font-size: 22px;
  color: #ffd700;
  margin-bottom: 10px;
}

.order-header p {
  margin: 5px 0;
  font-size: 16px;
  color: #f5f5f5;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: bold;
    color: #fff;
}

.status-pending { background-color: #ffc107; color: #333; } /* Желтый */
.status-processing { background-color: #17a2b8; } /* Синий */
.status-completed { background-color: #28a745; } /* Зеленый */
.status-cancelled { background-color: #dc3545; } /* Красный */


.order-items ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.order-item {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 10px;
  padding-bottom: 10px;
  border-bottom: 1px dashed rgba(255, 255, 255, 0.1);
}

.order-item:last-child {
  border-bottom: none;
  margin-bottom: 0;
  padding-bottom: 0;
}

.order-item-image {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 4px;
  flex-shrink: 0; /* Не сжимать изображение */
}

.order-item-image-placeholder {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(255,255,255,0.1);
    border-radius: 4px;
    font-size: 12px;
    text-align: center;
    flex-shrink: 0;
}

.item-name {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 5px;
  color: #ffd700;
}

.item-details {
  font-size: 15px;
  color: #f5f5f5;
}

.no-orders-message {
  text-align: center;
  margin-top: 50px;
  font-size: 18px;
  color: #f5f5f5;
}

.no-orders-message .button {
    margin-top: 20px;
    display: inline-block; /* Чтобы кнопка была по центру */
}

/* Адаптивность */
@media (max-width: 768px) {
  .auth-card {
    padding: 20px;
  }
  .auth-title {
    font-size: 28px;
  }
  .order-card {
    padding: 15px;
  }
  .order-header h3 {
    font-size: 20px;
  }
  .order-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }
}

/* Стили для флеш-сообщений (скопированы из Admin/Products/Index.vue) */
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

.error-alert {
    background-color: #f8d7da;
    border-color: #dc3545;
    color: #721c24;
}

/* Кнопки, если они нужны в этом компоненте */
.button {
    padding: 10px 15px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s ease;
    display: inline-block; /* Для кнопок в тексте */
}

.primary-button {
    background-color: #007bff;
    color: white;
    border: none;
}

.primary-button:hover {
    background-color: #0056b3;
}
</style>