# Диаграмма классов (Class Diagram)

## Основные классы системы:

### 1. Класс Restaurant (Ресторан)
**Атрибуты:**
- id: integer (primary key)
- name: string (название ресторана)
- description: text (описание, nullable)
- address: string (адрес)
- phone: string (телефон, nullable)
- image: string (путь к изображению, nullable)
- is_active: boolean (активен ли ресторан)
- created_at: timestamp
- updated_at: timestamp

**Методы:**
- dishes(): hasMany - связь с блюдами
- orders(): hasManyThrough - связь с заказами через блюда

### 2. Класс Dish (Блюдо)
**Атрибуты:**
- id: integer (primary key)
- restaurant_id: integer (foreign key)
- name: string (название блюда)
- description: text (описание, nullable)
- price: decimal (цена)
- image: string (путь к изображению, nullable)
- is_available: boolean (доступно ли блюдо)
- created_at: timestamp
- updated_at: timestamp

**Методы:**
- restaurant(): belongsTo - связь с рестораном
- orderItems(): hasMany - связь с элементами заказов

### 3. Класс Order (Заказ)
**Атрибуты:**
- id: integer (primary key)
- customer_name: string (имя заказчика)
- customer_email: string (email заказчика)
- customer_phone: string (телефон заказчика)
- delivery_address: text (адрес доставки)
- total_amount: decimal (общая сумма)
- status: enum (статус заказа)
- delivery_time: timestamp (время доставки, nullable)
- notes: text (дополнительные пожелания, nullable)
- created_at: timestamp
- updated_at: timestamp

**Методы:**
- orderItems(): hasMany - связь с элементами заказа
- dishes(): belongsToMany - связь с блюдами через order_items

### 4. Класс OrderItem (Элемент заказа)
**Атрибуты:**
- id: integer (primary key)
- order_id: integer (foreign key)
- dish_id: integer (foreign key)
- quantity: integer (количество)
- price: decimal (цена на момент заказа)
- special_instructions: text (особые пожелания, nullable)
- created_at: timestamp
- updated_at: timestamp

**Методы:**
- order(): belongsTo - связь с заказом
- dish(): belongsTo - связь с блюдом

### 5. Класс RestaurantController
**Методы:**
- index(): отображение списка ресторанов
- show($id): отображение ресторана с меню

### 6. Класс DishController
**Методы:**
- show($id): отображение деталей блюда

### 7. Класс CartController
**Методы:**
- index(): отображение корзины
- add(Request $request, Dish $dish): добавление блюда в корзину
- update(Request $request, $dishId): обновление количества
- remove($dishId): удаление блюда из корзины
- clear(): очистка корзины

### 8. Класс OrderController
**Методы:**
- create(): форма оформления заказа
- store(Request $request): сохранение заказа
- show($id): отображение деталей заказа
- success(): страница успешного оформления

## Отношения между классами:

1. **Restaurant** --1..*--> **Dish** (Один ко многим)
2. **Restaurant** --1..*--> **Order** (Один ко многим, через блюда)
3. **Order** --1..*--> **OrderItem** (Один ко многим)
4. **Dish** --1..*--> **OrderItem** (Один ко многим)
5. **Order** --*..*--> **Dish** (Многие ко многим, через OrderItem)

## Наследование:
- Все контроллеры наследуются от базового класса Controller
- Все модели наследуются от базового класса Model

## Описание в формате PlantUML:

```plantuml
@startuml Class Diagram

class Restaurant {
    - id: int
    - name: string
    - description: text
    - address: string
    - phone: string
    - image: string
    - is_active: boolean
    - created_at: timestamp
    - updated_at: timestamp
    + dishes(): hasMany
    + orders(): hasManyThrough
}

class Dish {
    - id: int
    - restaurant_id: int
    - name: string
    - description: text
    - price: decimal
    - image: string
    - is_available: boolean
    - created_at: timestamp
    - updated_at: timestamp
    + restaurant(): belongsTo
    + orderItems(): hasMany
}

class Order {
    - id: int
    - customer_name: string
    - customer_email: string
    - customer_phone: string
    - delivery_address: text
    - total_amount: decimal
    - status: enum
    - delivery_time: timestamp
    - notes: text
    - created_at: timestamp
    - updated_at: timestamp
    + orderItems(): hasMany
    + dishes(): belongsToMany
}

class OrderItem {
    - id: int
    - order_id: int
    - dish_id: int
    - quantity: int
    - price: decimal
    - special_instructions: text
    - created_at: timestamp
    - updated_at: timestamp
    + order(): belongsTo
    + dish(): belongsTo
}

class RestaurantController {
    + index()
    + show(id)
}

class DishController {
    + show(id)
}

class CartController {
    + index()
    + add(request, dish)
    + update(request, dishId)
    + remove(dishId)
    + clear()
}

class OrderController {
    + create()
    + store(request)
    + show(id)
    + success()
}

Restaurant ||--o{ Dish : "1..*"
Restaurant ||--o{ Order : "1..*" через блюда
Order ||--o{ OrderItem : "1..*"
Dish ||--o{ OrderItem : "1..*"
Order }o--o{ Dish : "*..*" через OrderItem

@enduml
```
