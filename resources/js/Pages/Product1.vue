<template>
    <section class="main-section">
        <div class="product-detail-container">
            <div v-if="productData" class="product-card-detail">
                <div class="image-gallery">
                    <img v-if="mainImage" :src="`/images/${mainImage}`" :alt="productData.name" class="main-product-image">
                    <div class="thumbnail-row">
                        <img
                            v-for="(img, index) in productData.images"
                            :key="index"
                            :src="`/images/${img}`"
                            @click="setMainImage(img)"
                            :class="['thumbnail', { 'active-thumbnail': mainImage === img }]"
                            alt="Product thumbnail"
                        />
                    </div>
                </div>
                <div class="product-info">
                    <h1 class="title-detail">{{ productData.name }}</h1>
                    <div class="product-rating-detail">
                        Рейтинг: {{ productData.rating }} / 5.0
                        <span v-for="n in 5" :key="n"
                            :class="{'star-filled-detail': n <= productData.rating, 'star-empty-detail': n > productData.rating}">★</span>
                    </div>
                    <p class="description">{{ productData.description }}</p>
                    <div class="price-detail">{{ productData.price }}р</div>

                    <div class="add-to-cart-section">
                        <template v-if="isAuthenticated">
                            <label for="quantity-input">Количество:</label>
                            <input
                                id="quantity-input"
                                type="number"
                                min="1"
                                v-model.number="form.quantity"
                                class="quantity-selector"
                            />
                            <button @click="addToCart" :disabled="form.processing" class="add-to-cart-button">
                                Добавить в корзину
                            </button>
                        </template>
                        <template v-else>
                            <p class="auth-message">
                                <Link :href="route('login')" class="login-prompt-link">Войдите</Link> или
                                <Link :href="route('register')" class="register-prompt-link">Зарегистрируйтесь</Link>, чтобы добавить товар в корзину.
                            </p>
                        </template>
                    </div>
                </div>
            </div>
            <div v-else class="loading-message">
                Загрузка информации о товаре...
            </div>
        </div>
    </section>
</template>

<script>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export default {
    components: {
        Link,
    },
    props: {
        productData: {
            type: Object,
            required: true,
            default: () => ({})
        }
    },
    data() {
        return {
            mainImage: this.productData.image || (this.productData.images && this.productData.images.length > 0 ? this.productData.images[0] : null),
        };
    },
    setup(props) {
        const form = useForm({
            product_id: props.productData.id,
            quantity: 1,
        });

        const page = usePage();
        const isAuthenticated = computed(() => !!page.props.auth.user);

        return { form, isAuthenticated };
    },
    watch: {
        productData: {
            handler(newVal) {
                if (newVal) {
                    this.mainImage = newVal.image || (newVal.images && newVal.images.length > 0 ? newVal.images[0] : null);
                    if (this.form) {
                        this.form.product_id = newVal.id;
                    }
                }
            },
            deep: true,
            immediate: true
        }
    },
    methods: {
        setMainImage(image) {
            this.mainImage = image;
        },
        addToCart() {
            if (!this.isAuthenticated) {
                alert('Пожалуйста, авторизуйтесь, чтобы добавить товар в корзину.');
                return;
            }

            this.form.post(route('cart.add'), {
                onSuccess: () => {
                    alert('Товар успешно добавлен в корзину!');
                    this.form.quantity = 1;
                },
                onError: (errors) => {
                    console.error('Ошибка при добавлении в корзину:', errors);
                    alert('Произошла ошибка при добавлении товара в корзину.');
                }
            });
        }
    },
}
</script>

<style scoped>
/* Ваши существующие стили */
.main-section {
    color: white;
    font-family: "Montserrat";
    width: 100%;
    min-height: 100vh;
    padding: 20px;
    box-sizing: border-box;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding-top: 50px;
    background-color: #884535; /* Добавил цвет фона для всей секции */
}

.product-detail-container {
    max-width: 1200px;
    width: 100%;
    background-color: #884535;
    border-radius: 10px;
    padding: 30px;
    border: dashed 2px white;
}

.product-card-detail {
    display: flex;
    flex-wrap: wrap;
    gap: 40px;
    justify-content: center;
}

.image-gallery {
    flex: 1;
    min-width: 300px;
    max-width: 500px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.main-product-image {
    width: 100%;
    height: auto;
    max-height: 400px;
    object-fit: contain;
    border-radius: 8px;
    margin-bottom: 15px;
}

.thumbnail-row {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 5px;
}

.thumbnail {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.2s ease;
}

.thumbnail:hover {
    border-color: #ffd700;
}

.active-thumbnail {
    border-color: #ffd700;
    box-shadow: 0 0 5px rgba(255, 215, 0, 0.5);
}

.product-info {
    flex: 2;
    min-width: 300px;
    padding-left: 20px;
}

.title-detail {
    font-size: 36px;
    margin-bottom: 15px;
    color: #ffd700;
}

.description {
    font-size: 18px;
    line-height: 1.6;
    margin-bottom: 25px;
    color: #e0e0e0;
}

.price-detail {
    font-size: 30px;
    font-weight: bold;
    color: #ffd700;
    margin-bottom: 30px;
}

.add-to-cart-section {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-top: 30px;
}

.quantity-selector {
    width: 80px;
    padding: 10px;
    border-radius: 5px;
    border: dashed 2px white;
    background-color: #7a3a2d; /* Цвет фона селектора количества */
    color: white;
    font-size: 18px;
    text-align: center;
}

.add-to-cart-button {
    background-color: #884535;
    color: white;
    padding: 15px 30px;
    border: none;
    border-radius: 5px;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    border: dashed 2px white;
}

.add-to-cart-button:hover {
    background-color: #a05e4a;
}

.add-to-cart-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.loading-message {
    text-align: center;
    font-size: 24px;
    color: #ccc;
    padding: 50px;
}

/* Новые стили для сообщения об авторизации */
.auth-message {
    font-size: 18px;
    color: #ccc;
    text-align: center;
    width: 100%;
    margin: 20px 0;
}

.login-prompt-link, .register-prompt-link {
    color: #ffd700;
    text-decoration: underline;
    font-weight: bold;
    transition: color 0.2s ease;
}

.login-prompt-link:hover, .register-prompt-link:hover {
    color: #ffb700;
}

/* Стили для рейтинга на странице продукта */
.product-rating-detail {
    font-size: 20px;
    color: white;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
    justify-content: center; /* Центрируем, если вся инфо центрирована */
}

.star-filled-detail {
    color: gold;
    font-size: 24px;
}

.star-empty-detail {
    color: #ccc;
    font-size: 24px;
}


/* Адаптация для мобильных устройств */
@media (max-width: 900px) {
    .product-card-detail {
        flex-direction: column;
        align-items: center;
    }

    .image-gallery, .product-info {
        min-width: unset;
        width: 100%;
        padding-left: 0;
        border-left: none;
    }

    .product-info {
        text-align: center;
        padding-top: 30px;
        border-top: 1px dashed #555;
    }

    .title-detail {
        font-size: 28px;
    }

    .price-detail {
        font-size: 24px;
    }
}

@media (max-width: 600px) {
    .product-detail-container {
        padding: 20px;
    }
    .add-to-cart-section {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
    }

    .quantity-selector {
        width: 100%;
        max-width: 150px;
        margin: 0 auto;
    }

    .add-to-cart-button {
        width: 100%;
        padding: 12px 20px;
    }
    .auth-message {
        font-size: 16px;
    }
    .product-rating-detail {
        font-size: 18px;
    }
    .star-filled-detail, .star-empty-detail {
        font-size: 20px;
    }
}
</style>