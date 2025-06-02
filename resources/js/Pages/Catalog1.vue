<template>
    <section class="main-section">
        <div class="catalog">КАТАЛОГ</div>
        <div class="content-wrapper">
            <div class="products-grid">
                <template v-if="products && products.length > 0">
                    <Link v-for="product in products" :key="product.id" :href="`/products/${product.id}`"
                        class="product-card">
                        <div class="image-container">
                            <img class="product-image" :src="`/images/${product.image}`" :alt="product.name">
                        </div>
                        <div class="product-info">
                            <div class="title">{{ product.name }}</div>
                            <div class="price">{{ product.price }}р</div>
                            <div class="product-rating">
                                Рейтинг: {{ product.rating }} / 5.0
                                <span v-for="n in 5" :key="n"
                                    :class="{'star-filled': n <= product.rating, 'star-empty': n > product.rating}">★</span>
                            </div>
                        </div>
                    </Link>
                </template>
                <div v-else class="no-products-message">
                    Нет товаров, соответствующих выбранным фильтрам.
                </div>
            </div>

            <div class="filter-container">
                <div class="filter">
                    <h3 class="filter-title">Поиск</h3>
                    <div>
                        <input type="text" v-model="form.search" placeholder="Название товара..."
                            class="price-field" @input="applyFiltersWithDebounce">
                    </div>

                    <h3 class="filter-title">Категории</h3>
                    <div>
                        <input type="checkbox" id="check-kurs" value="kurs" v-model="form.categories"
                            @change="applyFilters" />
                        <label for="check-kurs">Курсы</label>
                    </div>
                    <div>
                        <input type="checkbox" id="check-books" value="books" v-model="form.categories"
                            @change="applyFilters" />
                        <label for="check-books">Книги</label>
                    </div>
                    <div>
                        <input type="checkbox" id="check-shabl" value="shabl" v-model="form.categories"
                            @change="applyFilters" />
                        <label for="check-shabl">Шаблоны</label>
                    </div>


                    <h3 class="filter-title">Цена</h3>
                    <div class="price-filter">
                        <div class="price-input">
                            <label for="min-price">От</label>
                            <input type="number" id="min-price" v-model.number="form.min_price" placeholder="0" min="0"
                                class="price-field" @input="applyFiltersWithDebounce">
                        </div>
                        <div class="price-input">
                            <label for="max-price">До</label>
                            <input type="number" id="max-price" v-model.number="form.max_price" placeholder="0" min="0"
                                class="price-field" @input="applyFiltersWithDebounce">
                        </div>
                    </div>

                    <h3 class="filter-title">Рейтинг</h3>
                    <select v-model="form.rating" @change="applyFilters" class="filter-select">
                        <option :value="null">Любой рейтинг</option>
                        <option value="4.0">От 4.0 звезд и выше</option>
                        <option value="3.0">От 3.0 звезд и выше</option>
                        <option value="2.0">От 2.0 звезд и выше</option>
                        <option value="1.0">От 1.0 звезды и выше</option>
                    </select>
                </div>
            </div>
        </div>

        </section>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import { throttle } from 'lodash';

export default {
    components: {
        Link,
    },
    props: {
        products: { // ИЗМЕНЕНО: Теперь это Array
            type: Array, // <-- Должно быть Array
            required: true,
        },
        filters: {
            type: Object,
            default: () => ({
                categories: [],
                min_price: null,
                max_price: null,
                rating: null,
                search: null,
            }),
        },
    },
    data() {
        return {
            form: useForm({
                categories: this.filters.categories || [],
                min_price: this.filters.min_price || null,
                max_price: this.filters.max_price || null,
                rating: this.filters.rating || null,
                search: this.filters.search || null,
            }),
            applyFiltersWithDebounce: throttle(this.applyFilters, 500),
        };
    },
    methods: {
        applyFilters() {
            const categories = this.form.categories.filter(Boolean);

            const params = {
                categories: categories, // <-- ИЗМЕНЕНО: Передаем как массив
                min_price: this.form.min_price,
                max_price: this.form.max_price,
                rating: this.form.rating,
                search: this.form.search,
            };

            Object.keys(params).forEach(key => {
                if (params[key] === null || params[key] === '' || (Array.isArray(params[key]) && params[key].length === 0)) {
                    delete params[key];
                }
            });

            this.form.get(route('catalog'), {
                data: params,
                preserveScroll: true,
                preserveState: true,
                replace: true,
                onSuccess: () => {},
                onError: (errors) => {
                    console.error('Ошибка при применении фильтров:', errors);
                }
            });
        },
    },
    watch: {
        filters: {
            handler(newFilters) {
                this.form.categories = newFilters.categories || [];
                this.form.min_price = newFilters.min_price || null;
                this.form.max_price = newFilters.max_price || null;
                this.form.rating = newFilters.rating || null;
                this.form.search = newFilters.search || null;
            },
            deep: true,
            immediate: true,
        }
    }
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
    background-color: #884535; /* Добавил цвет фона для всей секции */
}

.catalog {
    font-size: 32px;
    margin-top: 50px;
    text-align: center;
    margin-bottom: 40px;
}

.content-wrapper {
    display: flex;
    max-width: 1400px;
    margin: 0 auto;
    gap: 40px;
}

.filter-container {
    width: 250px;
    position: sticky;
    top: 20px;
    height: fit-content;
}

.filter {
    padding: 20px;
    border: dashed 2px white;
    border-radius: 8px;
    font-size: 18px;
    width: 100%; /* Убедитесь, что ширина 100% для box-sizing */
    box-sizing: border-box;
    background-color: #7a3a2d; /* Цвет фона фильтра */
}

.filter h3 {
    font-size: 20px;
    margin-bottom: 15px;
    color: #ffd700;
    font-weight: bold;
}

.filter input[type="checkbox"] {
    margin-right: 10px;
    accent-color: #ffd700; /* Цвет чекбокса */
    width: 18px;
    height: 18px;
}

.filter label {
    cursor: pointer;
    margin-bottom: 8px; /* Отступ между элементами списка категорий */
    display: inline-block; /* Для правильного отображения отступа */
}

.products-grid {
    flex: 1;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 30px;
    align-content: start;
}

.product-card {
    border: dashed 2px white;
    border-radius: 8px;
    padding: 15px;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    background-color: #7a3a2d; /* Цвет фона карточки товара */
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    border-color: #ffd700;
}

.image-container {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    min-height: 200px;
}

.product-image {
    max-width: 100%;
    max-height: 250px;
    width: auto;
    height: auto;
    border-radius: 5px;
    object-fit: contain;
}

.product-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0 10px;
}

.title {
    font-size: 18px;
    margin-bottom: 8px;
    text-align: center;
    word-break: break-word;
    width: 100%;
    color: #ffd700; /* Цвет заголовка товара */
}

.price {
    font-size: 20px;
    font-weight: bold;
    color: #ffd700;
    text-align: center;
    margin-top: auto;
}

.price-filter {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 100%;
}

.price-input {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
}

.price-input label {
    width: 30px;
    flex-shrink: 0;
}

.price-field {
    width: 100%;
    max-width: calc(100% - 40px);
    padding: 8px;
    border-radius: 4px;
    background-color: #884535;
    color: white;
    font-family: "Montserrat";
    box-sizing: border-box;
    border: dashed 2px white;
}

.price-field:focus {
    outline: none;
    border-color: #ffd700;
}

/* Стили для рейтинга */
.product-rating {
    font-size: 16px;
    color: white;
    margin-top: 5px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.star-filled {
    color: gold;
    font-size: 20px;
}

.star-empty {
    color: #ccc; /* Светло-серый для незаполненных звезд */
    font-size: 20px;
}

.filter-select {
    width: 100%;
    padding: 10px;
    border: dashed 1px white;
    border-radius: 5px;
    background-color: #884535;
    color: white;
    font-size: 16px;
    outline: none;
}

.no-products-message {
    font-size: 20px;
    color: white;
    text-align: center;
    grid-column: 1 / -1; /* Чтобы сообщение занимало всю ширину грида */
    padding: 50px 0;
}

/* Стили для пагинации */
.pagination {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 30px;
    padding-bottom: 40px; /* Отступ снизу для пагинации */
}

.pagination-link {
    padding: 10px 15px;
    background-color: #7a3a2d;
    border: dashed 1px white;
    border-radius: 5px;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
}

.pagination-link:hover {
    background-color: #884535;
    border-color: #ffd700;
}

.pagination-link.active {
    background-color: #ffd700;
    color: #7a3a2d;
    border-color: #ffd700;
    pointer-events: none; /* Делаем активную ссылку некликабельной */
}

.pagination-link.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.filter div input[type="text"] {
    width: 100%;
    padding: 8px;
    border-radius: 4px;
    background-color: #884535;
    color: white;
    font-family: "Montserrat";
    box-sizing: border-box;
    border: dashed 2px white;
    margin-bottom: 15px; /* Отступ после поля поиска */
}
.filter div input[type="text"]:focus {
    outline: none;
    border-color: #ffd700;
}

/* Медиа-запросы для адаптивности */
@media (max-width: 1200px) {
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    }
}

@media (max-width: 900px) {
    .content-wrapper {
        flex-direction: column;
    }

    .filter-container {
        position: static;
        width: 100%;
        margin-bottom: 30px;
    }

    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
}

@media (max-width: 600px) {
    .products-grid {
        grid-template-columns: 1fr;
    }

    .product-card {
        flex-direction: row;
        align-items: center;
        gap: 15px;
    }

    .image-container {
        min-height: auto;
        margin-bottom: 0;
        flex: 0 0 120px;
    }

    .product-info {
        align-items: flex-start;
        text-align: left;
    }

    .title,
    .price {
        text-align: left;
    }

    .price-filter {
        flex-direction: row;
        flex-wrap: wrap;
    }

    .price-input {
        flex: 1 1 calc(50% - 5px);
        min-width: 0;
    }

    .price-field {
        max-width: none;
    }
}
</style>