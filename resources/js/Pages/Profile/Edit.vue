<template>
  <div class="auth-page">
    <AppHeader />
    <section class="auth-section profile-section">
      <div class="auth-card profile-card">
        <h2 class="auth-title">ЛИЧНЫЙ КАБИНЕТ</h2>

        <nav class="profile-tabs">
          <Link :href="route('profile.edit')" class="tab-link active">Профиль</Link>
          <Link :href="route('profile.orders.index')" class="tab-link">Мои заказы</Link>
          </nav>

        <div class="profile-forms-wrapper">
          <UpdateProfileInformationForm
            ref="profileFormRef"
            :must-verify-email="mustVerifyEmail"
            :status="status"
            class="profile-form"
          />

          <UpdatePasswordForm
            ref="passwordFormRef"
            class="profile-form"
          />

          <div class="profile-actions">
            <button @click="handleCancel" class="profile-button-secondary">Отменить</button>
            <button @click="handleApply" class="profile-button-primary">Применить</button>
          </div>
        </div>
      </div>
    </section>
    <AppFooter />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import AppHeader from '@/Pages/Header.vue'; // Обновил путь, если он был '../Header.vue'
import AppFooter from '@/Pages/Footer.vue'; // Обновил путь, если он был '../Footer.vue'
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Link, router } from '@inertiajs/vue3'; // Убедитесь, что Link импортирован

defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const profileFormRef = ref(null);
const passwordFormRef = ref(null);

const handleApply = () => {
  if (profileFormRef.value) {
    profileFormRef.value.submitForm();
  }
  if (passwordFormRef.value) {
    passwordFormRef.value.submitForm();
  }
};

const handleCancel = () => {
  router.visit(route('home'));
};
</script>

<style scoped>
/* Используйте общие стили из Login.vue и Register.vue */
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
  max-width: 700px; /* Увеличьте ширину для личного кабинета */
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

/* Стили для внутренних форм профиля (UpdateProfileInformationForm, UpdatePasswordForm) */
.profile-forms-wrapper {
  display: flex;
  flex-direction: column;
  gap: 30px; /* Отступы между формами */
  color: black; /* Этот цвет скорее всего не нужен, так как текст белый */
}

.profile-form {
  padding: 0; /* Удаляем внутренний padding, так как он уже есть у auth-card */
  background-color: transparent; /* Сбрасываем фон, если он был */
  border: none; /* Сбрасываем рамку */
  box-shadow: none; /* Сбрасываем тень */
  color: black; /* Этот цвет скорее всего не нужен */
}

/* :deep() для стилизации элементов внутри дочерних компонентов */
.profile-form :deep(h2), .profile-form :deep(p) {
    color: white; /* Делаем заголовки и параграфы белыми */
    text-align: center;
}
.profile-form :deep(p) {
    margin-bottom: 20px;
}

.profile-form :deep(.form-group) {
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  justify-content: center; /* Центрирование элементов внутри form-group */
  gap: 20px; /* Расстояние между меткой и инпутом */
}

.profile-form :deep(.form-label) {
  display: block;
  font-size: 18px;
  margin-bottom: 0; /* Убираем нижний отступ */
  color: white;
  text-align: right; /* Выравнивание метки по правому краю */
  width: 200px; /* Фиксированная ширина для меток */
  flex-shrink: 0; /* Чтобы метка не сжималась */
}

.profile-form :deep(.form-input) {
  flex-grow: 1; /* Инпут занимает все оставшееся пространство */
  padding: 12px 15px;
  border: dashed 2px white;
  border-radius: 5px;
  background-color: #7a3a2d;
  color: white;
  font-size: 16px;
  box-sizing: border-box;
  outline: none;
  transition: border-color 0.3s ease, background-color 0.3s ease;
}

.profile-form :deep(.form-input:focus) {
  border-color: #ffd700;
  background-color: #884535;
}

.profile-form :deep(.error-message) {
  color: #ffcccc;
  font-size: 14px;
  margin-top: 5px;
  text-align: center;
  width: 100%; /* Чтобы сообщение об ошибке было на новой строке */
}

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

.profile-actions {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 30px;
}

.profile-button-primary,
.profile-button-secondary {
  padding: 12px 30px;
  background-color: transparent;
  color: white;
  border: dashed 2px white;
  border-radius: 5px;
  font-size: 18px;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  font-weight: bold;
}

.profile-button-primary {
  border-color: #ffd700; /* Для кнопки "Применить" */
  color: #ffd700;
}

.profile-button-primary:hover {
  background-color: rgba(255, 255, 255, 0.1);
  border-color: white;
  color: white;
}

.profile-button-secondary:hover {
  background-color: rgba(255, 255, 255, 0.1);
  border-color: #ffd700;
  color: #ffd700;
}

/* Медиа-запросы для адаптивности */
@media (max-width: 768px) {
  .auth-card {
    padding: 20px;
  }

  .auth-title {
    font-size: 28px;
    margin-bottom: 20px;
  }

  .profile-form :deep(.form-group) {
    flex-direction: column;
    align-items: center;
    gap: 10px;
  }

  .profile-form :deep(.form-label) {
    width: 100%;
    text-align: center;
  }
}
</style>