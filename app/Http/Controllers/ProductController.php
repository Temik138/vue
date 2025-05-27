<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request; // Обязательно добавьте эту строку
use Illuminate\Support\Facades\Storage; // Обязательно добавьте эту строку
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        // Определяем, откуда пришел запрос: из админ-панели или из каталога
        // Это простой способ различать, но для более сложной логики лучше использовать отдельные контроллеры
        if (request()->routeIs('admin.products.index')) {
            // Если запрос пришел из админ-панели
            return Inertia::render('Admin/Products/Index', [
                'products' => Product::orderBy('id', 'desc')->paginate(10)->through(fn ($product) => [ // Добавим пагинацию
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image, // Основное изображение
                ]),
            ]);
        } else {
            // Если запрос пришел из публичного каталога
            return Inertia::render('Catalog', [
                'products' => Product::all()->map(function ($product) {
                    // Преобразуем images из JSON строки в массив, если это строка
                    $images_array = is_string($product->images)
                        ? json_decode($product->images, true)
                        : ($product->images ?? []); // Если уже массив или null

                    // Если images пуст, но есть основное изображение, добавляем его в галерею
                    if (empty($images_array) && $product->image) {
                        $images_array = [$product->image];
                    }

                    // Возвращаем все необходимые поля, включая image (основное) и images (галерея)
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'image' => $product->image, // Основное изображение для карточки каталога
                        'images' => $images_array, // Массив изображений для галереи на странице продукта
                        'category' => $product->category, // Для фильтрации
                    ];
                })->toArray()
            ]);
        }
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        $images_array = [];
        if (is_string($product->images)) {
            $decoded_images = json_decode($product->images, true);
            $images_array = is_array($decoded_images) ? $decoded_images : [];
        } elseif (is_array($product->images)) {
            $images_array = $product->images;
        }

        // Убедимся, что основное изображение всегда в начале списка, если оно есть и не повторяется
        if (!empty($product->image) && !in_array($product->image, $images_array)) {
            array_unshift($images_array, $product->image);
        } elseif (empty($images_array) && !empty($product->image)) {
            $images_array = [$product->image];
        }


        $product_for_vue = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'image' => $product->image,  // Основное изображение
            'images' => array_values(array_unique($images_array)), // Убираем дубликаты и переиндексируем
        ];

        return Inertia::render('Product', [
            'productData' => $product_for_vue
        ]);
    }

    // МЕТОД для главной страницы
    public function home()
    {
        $displayProducts = Product::orderBy('id', 'asc')->take(3)->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
            ];
        })->toArray();

        return Inertia::render('Home', [
            'featuredProducts' => $displayProducts
        ]);
    }

    /**
     * Отображает форму для создания нового товара.
     */
    public function create()
    {
        return Inertia::render('Admin/Products/Create');
    }

    /**
     * Сохраняет новый товар в базе данных.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Макс 2МБ
            // 'images' => 'nullable|array', // Если вы будете загружать несколько изображений
            // 'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'image' => $imagePath,
            'images' => json_encode($request->images), // Предполагается, что images может быть массивом
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Товар успешно добавлен.');
    }

    /**
     * Отображает форму для редактирования существующего товара.
     */
    public function edit(Product $product) // Используем Route Model Binding
    {
        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
        ]);
    }

    /**
     * Обновляет существующий товар в базе данных.
     */
    public function update(Request $request, Product $product) // Используем Route Model Binding
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'images' => 'nullable|array',
            // 'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $product->image; // Сохраняем текущее изображение по умолчанию

        if ($request->hasFile('image')) {
            // Удаляем старое изображение, если оно есть
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'image' => $imagePath,
            'images' => json_encode($request->images),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Товар успешно обновлен.');
    }

    /**
     * Удаляет товар из базы данных.
     */
    public function destroy(Product $product) // Используем Route Model Binding
    {
        // Удаляем основное изображение, если оно есть
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Если у вас хранятся несколько изображений в поле 'images', вам также нужно их удалить
        // foreach ($product->images as $image) {
        //     if (Storage::disk('public')->exists($image)) {
        //         Storage::disk('public')->delete($image);
        //     }
        // }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Товар успешно удален.');
    }
}