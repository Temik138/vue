<template>
  <AdminLayout>
    <div class="admin-form-container">
      <h1 class="admin-title">Добавить товар</h1>

      <div v-if="form.errors.name" class="alert error-alert">
        {{ form.errors.name }}
      </div>
      <div v-if="form.errors.description" class="alert error-alert">
        {{ form.errors.description }}
      </div>
      <div v-if="form.errors.price" class="alert error-alert">
        {{ form.errors.price }}
      </div>
      <div v-if="form.errors.category" class="alert error-alert">
        {{ form.errors.category }}
      </div>
      <div v-if="form.errors.image" class="alert error-alert">
        {{ form.errors.image }}
      </div>

      <form @submit.prevent="submit" class="product-form">
        <div class="form-group">
          <label for="name">Название:</label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            :class="{ 'input-error': form.errors.name }"
          >
        </div>

        <div class="form-group">
          <label for="description">Описание:</label>
          <textarea
            id="description"
            v-model="form.description"
            rows="5"
            :class="{ 'input-error': form.errors.description }"
          ></textarea>
        </div>

        <div class="form-group">
          <label for="price">Цена:</label>
          <input
            id="price"
            v-model.number="form.price"
            type="number"
            step="0.01"
            :class="{ 'input-error': form.errors.price }"
          >
        </div>

        <div class="form-group">
          <label for="category">Категория:</label>
          <input
            id="category"
            v-model="form.category"
            type="text"
            :class="{ 'input-error': form.errors.category }"
          >
        </div>

        <div class="form-group">
          <label for="image">Основное изображение:</label>
          <input
            id="image"
            type="file"
            @input="form.image = $event.target.files[0]"
            :class="{ 'input-error': form.errors.image }"
          >
          <p v-if="form.errors.image" class="error-message">{{ form.errors.image }}</p>
        </div>

        <button
          type="submit"
          :disabled="form.processing"
          class="button primary-button"
          :class="{ 'button-disabled': form.processing }"
        >
          {{ form.processing ? 'Добавление...' : 'Добавить товар' }}
        </button>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Pages/AdminPages.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  description: '',
  price: 0,
  category: '',
  image: null,
});

const submit = () => {
  form.post(route('admin.products.store'), {
    onSuccess: () => {
      form.reset();
    },
    onError: (errors) => {
      console.error("Ошибка при добавлении товара:", errors);
    },
  });
};
</script>

<style scoped>
.admin-form-container {
  padding: 20px;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  max-width: 600px;
  margin: 20px auto;
}

.admin-title {
  font-size: 24px;
  font-weight: bold;
  color: #333;
  margin-bottom: 20px;
  text-align: center;
}

/* Сообщения об ошибках */
.alert {
  padding: 15px;
  margin-bottom: 20px;
  border-left: 5px solid;
  border-radius: 4px;
}

.error-alert {
  background-color: #f8d7da;
  border-color: #dc3545;
  color: #721c24;
}

/* Форма */
.product-form {
  display: flex;
  flex-direction: column;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  font-weight: bold;
  margin-bottom: 8px;
  color: #555;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group textarea {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 16px;
  box-sizing: border-box; /* Учитывает padding и border в общей ширине */
  transition: border-color 0.3s ease;
}

.form-group input[type="file"] {
  border: 1px solid #ccc;
  padding: 8px;
  border-radius: 5px;
  width: 100%;
  box-sizing: border-box;
  background-color: #f9f9f9;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.input-error {
  border-color: #dc3545 !important;
}

.error-message {
  color: #dc3545;
  font-size: 14px;
  margin-top: 5px;
}

/* Кнопки */
.button {
  padding: 10px 15px;
  border-radius: 5px;
  text-decoration: none;
  font-weight: bold;
  transition: background-color 0.3s ease, opacity 0.3s ease;
  border: none;
  cursor: pointer;
}

.primary-button {
  background-color: #007bff;
  color: white;
}

.primary-button:hover {
  background-color: #0056b3;
}

.button-disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>