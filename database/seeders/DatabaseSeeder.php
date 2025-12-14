<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\Restaurant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем админа, если его нет
        $this->call(AdminUserSeeder::class);

        // Создаем рестораны и столовые Минска (включая БГУИР)
        $restaurants = [
            [
                'name' => 'Столовая БГУИР',
                'description' => 'Столовая Белорусского государственного университета информатики и радиоэлектроники',
                'address' => 'ул. П. Бровки, 6 (главный корпус БГУИР)',
                'phone' => '+375 (29) 293-80-80',
                'is_active' => true,
            ],
            [
                'name' => 'Кафе "Гости"',
                'description' => 'Популярное кафе в центре Минска с европейской и белорусской кухней',
                'address' => 'ул. Интернациональная, 25 (рядом с ул. Гикало)',
                'phone' => '+375 (29) 123-45-67',
                'is_active' => true,
            ],
            [
                'name' => 'Ресторан "Старое Место"',
                'description' => 'Исторический ресторан в центре Минска с традиционной белорусской кухней',
                'address' => 'ул. Революционная, 8 (близко к ул. Гикало)',
                'phone' => '+375 (29) 227-55-99',
                'is_active' => true,
            ],
            [
                'name' => 'Кафе "Вкуснятина"',
                'description' => 'Семейное кафе с домашними обедами и десертами',
                'address' => 'ул. Максима Богдановича, 12',
                'phone' => '+375 (29) 345-67-89',
                'is_active' => true,
            ],
            [
                'name' => 'Столовая БНТУ',
                'description' => 'Столовая Белорусского национального технического университета',
                'address' => 'пр. Независимости, 65 (главный корпус БНТУ)',
                'phone' => '+375 (29) 292-13-45',
                'is_active' => true,
            ],
            [
                'name' => 'Кафе "Трактир"',
                'description' => 'Традиционный белорусский трактир с аутентичной кухней',
                'address' => 'ул. Октябрьская, 16 (Верхний город)',
                'phone' => '+375 (29) 876-54-32',
                'is_active' => true,
            ],
            [
                'name' => 'Ресторан "Минск"',
                'description' => 'Элегантный ресторан в центре столицы с современной европейской кухней',
                'address' => 'пр. Независимости, 81 (отель Минск)',
                'phone' => '+375 (29) 229-19-19',
                'is_active' => true,
            ],
            [
                'name' => 'Столовая БГУ',
                'description' => 'Столовая Белорусского государственного университета',
                'address' => 'пр. Независимости, 4 (главный корпус БГУ)',
                'phone' => '+375 (29) 209-51-00',
                'is_active' => true,
            ],
            [
                'name' => 'Ресторан "Пивария"',
                'description' => 'Немецкая пивная с традиционными блюдами и крафтовым пивом',
                'address' => 'ул. Козлова, 5 (ТЦ Галилео)',
                'phone' => '+375 (29) 112-22-33',
                'is_active' => true,
            ],
            [
                'name' => 'Кафе "Здоровое питание"',
                'description' => 'Вегетарианское кафе с органическими продуктами и здоровым питанием',
                'address' => 'ул. Максима Богдановича, 15',
                'phone' => '+375 (29) 445-66-77',
                'is_active' => true,
            ],
            [
                'name' => 'Столовая МГЛУ',
                'description' => 'Столовая Минского государственного лингвистического университета',
                'address' => 'ул. Захарова, 21',
                'phone' => '+375 (29) 284-18-00',
                'is_active' => true,
            ],
            [
                'name' => 'Ресторан "Бабочка"',
                'description' => 'Итальянская кухня в центре Минска с аутентичными рецептами',
                'address' => 'ул. Октябрьская, 10а',
                'phone' => '+375 (29) 778-88-99',
                'is_active' => true,
            ],
            [
                'name' => 'Кафе "БыстроЕда"',
                'description' => 'Быстрое питание с белорусскими и европейскими блюдами',
                'address' => 'ул. Ленина, 15',
                'phone' => '+375 (29) 999-00-11',
                'is_active' => true,
            ],
            // НОВЫЕ РЕАЛЬНЫЕ ЗАВЕДЕНИЯ МИНСКА
            [
                'name' => 'Столовая "У Наташи"',
                'description' => 'Популярная студенческая столовая в центре Минска',
                'address' => 'ул. Максима Богдановича, 10',
                'phone' => '+375 (29) 123-45-67',
                'is_active' => true,
            ],
            [
                'name' => 'Столовая "Академия"',
                'description' => 'Столовая при академии наук с домашней кухней',
                'address' => 'пр. Независимости, 66',
                'phone' => '+375 (29) 234-56-78',
                'is_active' => true,
            ],
            [
                'name' => 'Столовая "Центральная"',
                'description' => 'Классическая столовая в центре города',
                'address' => 'ул. Ленина, 22',
                'phone' => '+375 (29) 345-67-89',
                'is_active' => true,
            ],
            [
                'name' => 'Кафе "Вареничная №1"',
                'description' => 'Знаменитая вареничная с традиционной украинской кухней',
                'address' => 'ул. Революционная, 7',
                'phone' => '+375 (29) 456-78-90',
                'is_active' => true,
            ],
            [
                'name' => 'Кафе "Кофе Хауз"',
                'description' => 'Сеть кофеен с европейской кухней и десертами',
                'address' => 'пр. Независимости, 19',
                'phone' => '+375 (29) 567-89-01',
                'is_active' => true,
            ],
            [
                'name' => 'Кафе "МакДональдс"',
                'description' => 'Международная сеть быстрого питания',
                'address' => 'пр. Победителей, 9',
                'phone' => '+375 (29) 678-90-12',
                'is_active' => true,
            ],
            [
                'name' => 'Кафе "Старбакс"',
                'description' => 'Международная кофейня с премиум-кофе',
                'address' => 'ул. Немига, 5',
                'phone' => '+375 (29) 789-01-23',
                'is_active' => true,
            ],
            [
                'name' => 'Ресторан "Лидо"',
                'description' => 'Легендарный ресторан с белорусской и европейской кухней',
                'address' => 'пр. Независимости, 13',
                'phone' => '+375 (29) 890-12-34',
                'is_active' => true,
            ],
            [
                'name' => 'Ресторан "Баклажан"',
                'description' => 'Ресторан грузинской кухни в центре Минска',
                'address' => 'ул. Октябрьская, 5',
                'phone' => '+375 (29) 901-23-45',
                'is_active' => true,
            ],
            [
                'name' => 'Ресторан "Пицца Темпо"',
                'description' => 'Итальянский ресторан с пиццей и пастой',
                'address' => 'ул. Притыцкого, 83',
                'phone' => '+375 (29) 012-34-56',
                'is_active' => true,
            ],
            [
                'name' => 'Ресторан "Васильки"',
                'description' => 'Белорусский ресторан с национальной кухней',
                'address' => 'ул. Интернациональная, 33',
                'phone' => '+375 (29) 123-45-67',
                'is_active' => true,
            ],
        ];

        foreach ($restaurants as $restaurantData) {
            Restaurant::create($restaurantData);
        }

        // Создаем полное меню с категориями для каждого заведения
        $dishes = [
            // ===== СТОЛОВЫЕ (бюджетная студенческая еда) =====

            // Столовая БГУИР (ID 1) - полное меню
            // СУПЫ
            ['restaurant_id' => 1, 'category' => 'Супы', 'name' => 'Борщ', 'description' => 'Красный борщ с пампушками', 'price' => 3.50, 'is_available' => true],
            ['restaurant_id' => 1, 'category' => 'Супы', 'name' => 'Щи', 'description' => 'Капустные щи с мясом', 'price' => 3.20, 'is_available' => true],
            ['restaurant_id' => 1, 'category' => 'Супы', 'name' => 'Солянка', 'description' => 'Мясная солянка с оливками', 'price' => 4.00, 'is_available' => true],
            ['restaurant_id' => 1, 'category' => 'Супы', 'name' => 'Гороховый суп', 'description' => 'Суп из гороха с копчёностями', 'price' => 3.80, 'is_available' => true],

            // ГОРЯЧЕЕ
            ['restaurant_id' => 1, 'category' => 'Горячее', 'name' => 'Картофельное пюре с котлетой', 'description' => 'Картофельное пюре с мясной котлетой', 'price' => 4.20, 'is_available' => true],
            ['restaurant_id' => 1, 'category' => 'Горячее', 'name' => 'Гречневая каша с мясом', 'description' => 'Гречневая каша с тушёным мясом', 'price' => 4.50, 'is_available' => true],
            ['restaurant_id' => 1, 'category' => 'Горячее', 'name' => 'Рис с овощами', 'description' => 'Отварной рис с овощным гарниром', 'price' => 3.50, 'is_available' => true],
            ['restaurant_id' => 1, 'category' => 'Горячее', 'name' => 'Макароны с сосиской', 'description' => 'Макароны с сосиской и томатным соусом', 'price' => 3.80, 'is_available' => true],
            ['restaurant_id' => 1, 'category' => 'Горячее', 'name' => 'Котлета по-киевски', 'description' => 'Котлета по-киевски с картофелем', 'price' => 5.00, 'is_available' => true],

            // КОМПЛЕКСНЫЕ ОБЕДЫ
            ['restaurant_id' => 1, 'category' => 'Комплексные обеды', 'name' => 'Студенческий обед', 'description' => 'Комплекс: суп + котлета с гарниром + компот', 'price' => 6.50, 'is_available' => true],
            ['restaurant_id' => 1, 'category' => 'Комплексные обеды', 'name' => 'Бизнес-ланч', 'description' => 'Суп + горячее + салат + напиток', 'price' => 8.00, 'is_available' => true],

            // НАПИТКИ
            ['restaurant_id' => 1, 'category' => 'Напитки', 'name' => 'Компот', 'description' => 'Свежезаваренный компот из сухофруктов', 'price' => 1.50, 'is_available' => true],
            ['restaurant_id' => 1, 'category' => 'Напитки', 'name' => 'Чай', 'description' => 'Чёрный чай с лимоном', 'price' => 1.00, 'is_available' => true],
            ['restaurant_id' => 1, 'category' => 'Напитки', 'name' => 'Кофе', 'description' => 'Растворимый кофе', 'price' => 1.50, 'is_available' => true],

            // ДЕСЕРТЫ
            ['restaurant_id' => 1, 'category' => 'Десерты', 'name' => 'Печенье', 'description' => 'Песочное печенье', 'price' => 1.50, 'is_available' => true],
            ['restaurant_id' => 1, 'category' => 'Десерты', 'name' => 'Йогурт', 'description' => 'Натуральный йогурт', 'price' => 2.00, 'is_available' => true],

            // ===== КАФЕ "ГОСТИ" (ID 2) - европейская кухня =====
            // САЛАТЫ
            ['restaurant_id' => 2, 'category' => 'Салаты', 'name' => 'Цезарь с курицей', 'description' => 'Салат с курицей, пармезаном и соусом цезарь', 'price' => 22.00, 'is_available' => true],
            ['restaurant_id' => 2, 'category' => 'Салаты', 'name' => 'Греческий салат', 'description' => 'Овощной салат с сыром фета и оливками', 'price' => 18.00, 'is_available' => true],
            ['restaurant_id' => 2, 'category' => 'Салаты', 'name' => 'Оливье', 'description' => 'Классический салат оливье', 'price' => 15.00, 'is_available' => true],

            // ГОРЯЧЕЕ
            ['restaurant_id' => 2, 'category' => 'Горячее', 'name' => 'Стейк из говядины', 'description' => 'Сочный стейк с овощами гриль', 'price' => 45.00, 'is_available' => true],
            ['restaurant_id' => 2, 'category' => 'Горячее', 'name' => 'Лосось на гриле', 'description' => 'Стейк лосося с овощами', 'price' => 38.00, 'is_available' => true],
            ['restaurant_id' => 2, 'category' => 'Горячее', 'name' => 'Паста карбонара', 'description' => 'Итальянская паста с беконом и сливками', 'price' => 25.00, 'is_available' => true],
            ['restaurant_id' => 2, 'category' => 'Горячее', 'name' => 'Котлета по-киевски', 'description' => 'Классическая котлета по-киевски', 'price' => 28.00, 'is_available' => true],

            // БЕЛОРУССКАЯ КУХНЯ
            ['restaurant_id' => 2, 'category' => 'Белорусская кухня', 'name' => 'Драники с грибами', 'description' => 'Картофельные драники с лесными грибами', 'price' => 15.50, 'is_available' => true],
            ['restaurant_id' => 2, 'category' => 'Белорусская кухня', 'name' => 'Жаркое по-деревенски', 'description' => 'Мясное жаркое с овощами', 'price' => 24.00, 'is_available' => true],
            ['restaurant_id' => 2, 'category' => 'Белорусская кухня', 'name' => 'Колдуны', 'description' => 'Белорусские пельмени со сметаной', 'price' => 19.00, 'is_available' => true],

            // НАПИТКИ
            ['restaurant_id' => 2, 'category' => 'Напитки', 'name' => 'Капучино', 'description' => 'Кофе с молочной пенкой', 'price' => 8.50, 'is_available' => true],
            ['restaurant_id' => 2, 'category' => 'Напитки', 'name' => 'Эспрессо', 'description' => 'Крепкий кофе', 'price' => 6.00, 'is_available' => true],
            ['restaurant_id' => 2, 'category' => 'Напитки', 'name' => 'Чай', 'description' => 'Ассорти чая (чёрный, зелёный, травяной)', 'price' => 5.50, 'is_available' => true],
            ['restaurant_id' => 2, 'category' => 'Напитки', 'name' => 'Сок', 'description' => 'Свежие соки (апельсиновый, яблочный)', 'price' => 7.00, 'is_available' => true],

            // ДЕСЕРТЫ
            ['restaurant_id' => 2, 'category' => 'Десерты', 'name' => 'Тирамису', 'description' => 'Классический итальянский десерт', 'price' => 18.00, 'is_available' => true],
            ['restaurant_id' => 2, 'category' => 'Десерты', 'name' => 'Чизкейк', 'description' => 'Нью-йоркский чизкейк', 'price' => 16.00, 'is_available' => true],
            ['restaurant_id' => 2, 'category' => 'Десерты', 'name' => 'Мороженое', 'description' => 'Пломбир с фруктами', 'price' => 12.00, 'is_available' => true],

            // ===== РЕСТОРАН "СТАРОЕ МЕСТО" (ID 3) - белорусская кухня =====
            // СУПЫ
            ['restaurant_id' => 3, 'category' => 'Супы', 'name' => 'Борщ', 'description' => 'Красный борщ с пампушками и салом', 'price' => 16.00, 'is_available' => true],
            ['restaurant_id' => 3, 'category' => 'Супы', 'name' => 'Солянка', 'description' => 'Мясная солянка с оливками', 'price' => 18.00, 'is_available' => true],

            // ГОРЯЧЕЕ
            ['restaurant_id' => 3, 'category' => 'Горячее', 'name' => 'Жаркое по-деревенски', 'description' => 'Мясное жаркое в горшочке', 'price' => 28.00, 'is_available' => true],
            ['restaurant_id' => 3, 'category' => 'Горячее', 'name' => 'Колдуны', 'description' => 'Белорусские пельмени со сметаной', 'price' => 19.00, 'is_available' => true],
            ['restaurant_id' => 3, 'category' => 'Горячее', 'name' => 'Мачанка', 'description' => 'Свинина с картофелем в сметанном соусе', 'price' => 25.00, 'is_available' => true],
            ['restaurant_id' => 3, 'category' => 'Горячее', 'name' => 'Бабка картофельная', 'description' => 'Запеканка из картофеля с мясом', 'price' => 22.00, 'is_available' => true],

            // БЛЮДА ИЗ КАРТОФЕЛЯ
            ['restaurant_id' => 3, 'category' => 'Картофельные блюда', 'name' => 'Драники', 'description' => 'Картофельные оладьи со сметаной', 'price' => 15.00, 'is_available' => true],
            ['restaurant_id' => 3, 'category' => 'Картофельные блюда', 'name' => 'Бульба с салом', 'description' => 'Отварной картофель с салом', 'price' => 14.00, 'is_available' => true],

            // ДЕСЕРТЫ
            ['restaurant_id' => 3, 'category' => 'Десерты', 'name' => 'Сырники', 'description' => 'Творожные оладьи с вареньем', 'price' => 14.00, 'is_available' => true],
            ['restaurant_id' => 3, 'category' => 'Десерты', 'name' => 'Блины', 'description' => 'Блины с мёдом и сметаной', 'price' => 16.00, 'is_available' => true],

            // НАПИТКИ
            ['restaurant_id' => 3, 'category' => 'Напитки', 'name' => 'Квас', 'description' => 'Домашний хлебный квас', 'price' => 5.50, 'is_available' => true],
            ['restaurant_id' => 3, 'category' => 'Напитки', 'name' => 'Медовуха', 'description' => 'Традиционный белорусский напиток', 'price' => 12.00, 'is_available' => true],
            ['restaurant_id' => 3, 'category' => 'Напитки', 'name' => 'Компот', 'description' => 'Компот из сухофруктов', 'price' => 6.00, 'is_available' => true],

            // ===== КАФЕ "ВКУСНЯТИНА" (ID 4) - семейное кафе =====
            // ЗАВТРАКИ
            ['restaurant_id' => 4, 'category' => 'Завтраки', 'name' => 'Овсяная каша', 'description' => 'Овсяная каша с фруктами и мёдом', 'price' => 8.00, 'is_available' => true],
            ['restaurant_id' => 4, 'category' => 'Завтраки', 'name' => 'Блины', 'description' => 'Блины с маслом и мёдом', 'price' => 10.00, 'is_available' => true],
            ['restaurant_id' => 4, 'category' => 'Завтраки', 'name' => 'Сырники', 'description' => 'Творожные оладьи со сметаной', 'price' => 12.00, 'is_available' => true],

            // СУПЫ
            ['restaurant_id' => 4, 'category' => 'Супы', 'name' => 'Щи', 'description' => 'Капустные щи с мясом и сметаной', 'price' => 8.00, 'is_available' => true],
            ['restaurant_id' => 4, 'category' => 'Супы', 'name' => 'Борщ', 'description' => 'Красный борщ с пампушками', 'price' => 9.00, 'is_available' => true],

            // ГОРЯЧЕЕ
            ['restaurant_id' => 4, 'category' => 'Горячее', 'name' => 'Курица с картофелем', 'description' => 'Жареная курица с картофелем и салатом', 'price' => 16.00, 'is_available' => true],
            ['restaurant_id' => 4, 'category' => 'Горячее', 'name' => 'Котлеты домашние', 'description' => 'Домашние котлеты с картофельным пюре', 'price' => 14.00, 'is_available' => true],
            ['restaurant_id' => 4, 'category' => 'Горячее', 'name' => 'Гуляш', 'description' => 'Гуляш с гречкой', 'price' => 15.00, 'is_available' => true],

            // САЛАТЫ
            ['restaurant_id' => 4, 'category' => 'Салаты', 'name' => 'Оливье', 'description' => 'Классический салат оливье', 'price' => 10.00, 'is_available' => true],
            ['restaurant_id' => 4, 'category' => 'Салаты', 'name' => 'Свекольный', 'description' => 'Салат из свёклы с чесноком', 'price' => 8.00, 'is_available' => true],

            // ДЕСЕРТЫ
            ['restaurant_id' => 4, 'category' => 'Десерты', 'name' => 'Запеканка творожная', 'description' => 'Творожная запеканка с изюмом', 'price' => 9.00, 'is_available' => true],
            ['restaurant_id' => 4, 'category' => 'Десерты', 'name' => 'Пирог с яблоками', 'description' => 'Домашний яблочный пирог', 'price' => 12.00, 'is_available' => true],
            ['restaurant_id' => 4, 'category' => 'Десерты', 'name' => 'Компот', 'description' => 'Домашний компот', 'price' => 5.00, 'is_available' => true],

            // НАПИТКИ
            ['restaurant_id' => 4, 'category' => 'Напитки', 'name' => 'Чай', 'description' => 'Чёрный чай с лимоном и мёдом', 'price' => 4.00, 'is_available' => true],
            ['restaurant_id' => 4, 'category' => 'Напитки', 'name' => 'Кофе', 'description' => 'Кофе со сливками', 'price' => 6.00, 'is_available' => true],
            ['restaurant_id' => 4, 'category' => 'Напитки', 'name' => 'Морс', 'description' => 'Клюквенный морс', 'price' => 5.00, 'is_available' => true],

            // ===== РЕСТОРАН "МИНСК" (ID 7) - современная европейская кухня =====
            // ЗАКУСКИ
            ['restaurant_id' => 7, 'category' => 'Закуски', 'name' => 'Брускетта', 'description' => 'Тосты с томатами и базиликом', 'price' => 14.00, 'is_available' => true],
            ['restaurant_id' => 7, 'category' => 'Закуски', 'name' => 'Карпаччо', 'description' => 'Тонко нарезанная говядина с пармезаном', 'price' => 22.00, 'is_available' => true],

            // САЛАТЫ
            ['restaurant_id' => 7, 'category' => 'Салаты', 'name' => 'Цезарь', 'description' => 'Салат цезарь с курицей', 'price' => 20.00, 'is_available' => true],
            ['restaurant_id' => 7, 'category' => 'Салаты', 'name' => 'Капрезе', 'description' => 'Томаты с моцареллой и базиликом', 'price' => 18.00, 'is_available' => true],

            // ОСНОВНЫЕ БЛЮДА
            ['restaurant_id' => 7, 'category' => 'Основные блюда', 'name' => 'Утка конфи', 'description' => 'Утиная ножка по-французски', 'price' => 38.00, 'is_available' => true],
            ['restaurant_id' => 7, 'category' => 'Основные блюда', 'name' => 'Лосось на гриле', 'description' => 'Стейк лосося с овощами', 'price' => 42.00, 'is_available' => true],
            ['restaurant_id' => 7, 'category' => 'Основные блюда', 'name' => 'Стейк из говядины', 'description' => 'Рибай стейк с овощами гриль', 'price' => 45.00, 'is_available' => true],
            ['restaurant_id' => 7, 'category' => 'Основные блюда', 'name' => 'Тартар из говядины', 'description' => 'Французский стейк тартар', 'price' => 35.00, 'is_available' => true],
            ['restaurant_id' => 7, 'category' => 'Основные блюда', 'name' => 'Паста карбонара', 'description' => 'Спагетти с беконом и сливками', 'price' => 28.00, 'is_available' => true],

            // ДЕСЕРТЫ
            ['restaurant_id' => 7, 'category' => 'Десерты', 'name' => 'Шоколадный мусс', 'description' => 'Мусс с ягодами', 'price' => 16.00, 'is_available' => true],
            ['restaurant_id' => 7, 'category' => 'Десерты', 'name' => 'Тирамису', 'description' => 'Классический итальянский десерт', 'price' => 18.00, 'is_available' => true],
            ['restaurant_id' => 7, 'category' => 'Десерты', 'name' => 'Панна котта', 'description' => 'Десерт с ягодным соусом', 'price' => 15.00, 'is_available' => true],

            // НАПИТКИ
            ['restaurant_id' => 7, 'category' => 'Напитки', 'name' => 'Вино красное', 'description' => 'Красное сухое вино (бокал)', 'price' => 12.00, 'is_available' => true],
            ['restaurant_id' => 7, 'category' => 'Напитки', 'name' => 'Вино белое', 'description' => 'Белое сухое вино (бокал)', 'price' => 12.00, 'is_available' => true],
            ['restaurant_id' => 7, 'category' => 'Напитки', 'name' => 'Коктейль', 'description' => 'Авторский коктейль', 'price' => 15.00, 'is_available' => true],

            // ===== КАФЕ "ЗДОРОВОЕ ПИТАНИЕ" (ID 11) - вегетарианское кафе =====
            // САЛАТЫ
            ['restaurant_id' => 11, 'category' => 'Салаты', 'name' => 'Цезарь вегетарианский', 'description' => 'Салат с тофу и овощами', 'price' => 10.50, 'is_available' => true],
            ['restaurant_id' => 11, 'category' => 'Салаты', 'name' => 'Греческий', 'description' => 'Овощной салат с фетой', 'price' => 9.50, 'is_available' => true],
            ['restaurant_id' => 11, 'category' => 'Салаты', 'name' => 'Киноа-салат', 'description' => 'Киноа с овощами и семечками', 'price' => 11.00, 'is_available' => true],

            // ГОРЯЧЕЕ
            ['restaurant_id' => 11, 'category' => 'Горячее', 'name' => 'Киноа с овощами', 'description' => 'Киноа с запечёнными овощами', 'price' => 12.00, 'is_available' => true],
            ['restaurant_id' => 11, 'category' => 'Горячее', 'name' => 'Тофу стир-фрай', 'description' => 'Обжаренный тофу с овощами', 'price' => 13.50, 'is_available' => true],
            ['restaurant_id' => 11, 'category' => 'Горячее', 'name' => 'Фалафель', 'description' => 'Нутовые котлеты с овощами', 'price' => 11.50, 'is_available' => true],
            ['restaurant_id' => 11, 'category' => 'Горячее', 'name' => 'Рататуй', 'description' => 'Запечённые овощи по-французски', 'price' => 12.50, 'is_available' => true],

            // СУПЫ
            ['restaurant_id' => 11, 'category' => 'Супы', 'name' => 'Вегетарианский борщ', 'description' => 'Борщ без мяса с грибами', 'price' => 8.00, 'is_available' => true],
            ['restaurant_id' => 11, 'category' => 'Супы', 'name' => 'Гаспачо', 'description' => 'Холодный томатный суп', 'price' => 9.00, 'is_available' => true],
            ['restaurant_id' => 11, 'category' => 'Супы', 'name' => 'Линзовый суп', 'description' => 'Полезный суп из красной чечевицы', 'price' => 8.50, 'is_available' => true],

            // ДЕСЕРТЫ
            ['restaurant_id' => 11, 'category' => 'Десерты', 'name' => 'Чиа пуддинг', 'description' => 'Пуддинг с кокосовым молоком', 'price' => 7.00, 'is_available' => true],
            ['restaurant_id' => 11, 'category' => 'Десерты', 'name' => 'Фруктовый салат', 'description' => 'Свежие фрукты с мёдом', 'price' => 6.50, 'is_available' => true],
            ['restaurant_id' => 11, 'category' => 'Десерты', 'name' => 'Йогурт с мюсли', 'description' => 'Йогурт с мюсли и мёдом', 'price' => 7.50, 'is_available' => true],

            // НАПИТКИ
            ['restaurant_id' => 11, 'category' => 'Напитки', 'name' => 'Зелёный смузи', 'description' => 'Смузи из шпината и банана', 'price' => 8.00, 'is_available' => true],
            ['restaurant_id' => 11, 'category' => 'Напитки', 'name' => 'Чай зелёный', 'description' => 'Органический зелёный чай', 'price' => 6.00, 'is_available' => true],
            ['restaurant_id' => 11, 'category' => 'Напитки', 'name' => 'Фреш', 'description' => 'Свежие соки (апельсин, яблоко)', 'price' => 9.00, 'is_available' => true],

            // ===== ДОПОЛНИТЕЛЬНЫЕ БЛЮДА ДЛЯ НЕДОСТАЮЩИХ ЗАВЕДЕНИЙ =====

            // ДОПОЛНИМ Столовую БНТУ (ID 5)
            ['restaurant_id' => 5, 'category' => 'Горячее', 'name' => 'Плов с мясом', 'description' => 'Рис с бараниной и овощами', 'price' => 6.50, 'is_available' => true],
            ['restaurant_id' => 5, 'category' => 'Горячее', 'name' => 'Котлеты с пюре', 'description' => 'Мясные котлеты с картофельным пюре', 'price' => 5.80, 'is_available' => true],
            ['restaurant_id' => 5, 'category' => 'Горячее', 'name' => 'Макароны по-флотски', 'description' => 'Макароны с фаршем', 'price' => 4.50, 'is_available' => true],
            ['restaurant_id' => 5, 'category' => 'Горячее', 'name' => 'Гречаники', 'description' => 'Гречневые котлеты', 'price' => 4.20, 'is_available' => true],
            ['restaurant_id' => 5, 'category' => 'Напитки', 'name' => 'Компот', 'description' => 'Свежезаваренный компот', 'price' => 1.80, 'is_available' => true],
            ['restaurant_id' => 5, 'category' => 'Десерты', 'name' => 'Кисель', 'description' => 'Клюквенный кисель', 'price' => 2.50, 'is_available' => true],

            // ДОПОЛНИМ Кафе "Трактир" (ID 6)
            ['restaurant_id' => 6, 'category' => 'Горячее', 'name' => 'Жаркое по-белорусски', 'description' => 'Мясное жаркое с картофелем', 'price' => 24.00, 'is_available' => true],
            ['restaurant_id' => 6, 'category' => 'Белорусская кухня', 'name' => 'Клецки', 'description' => 'Мучные клецки со сметаной', 'price' => 16.00, 'is_available' => true],
            ['restaurant_id' => 6, 'category' => 'Белорусская кухня', 'name' => 'Сало с хлебом', 'description' => 'Сало с бородинским хлебом', 'price' => 18.00, 'is_available' => true],
            ['restaurant_id' => 6, 'category' => 'Напитки', 'name' => 'Квас домашний', 'description' => 'Традиционный белорусский квас', 'price' => 5.00, 'is_available' => true],
            ['restaurant_id' => 6, 'category' => 'Напитки', 'name' => 'Медовуха', 'description' => 'Белорусский медовый напиток', 'price' => 14.00, 'is_available' => true],
            ['restaurant_id' => 6, 'category' => 'Десерты', 'name' => 'Блины с вареньем', 'description' => 'Тонкие блины с ягодным вареньем', 'price' => 15.00, 'is_available' => true],

            // ДОПОЛНИМ Столовую БГУ (ID 8)
            ['restaurant_id' => 8, 'category' => 'Горячее', 'name' => 'Курица с рисом', 'description' => 'Курица с отварным рисом', 'price' => 6.20, 'is_available' => true],
            ['restaurant_id' => 8, 'category' => 'Горячее', 'name' => 'Рыба запеченная', 'description' => 'Запеченная рыба с овощами', 'price' => 7.50, 'is_available' => true],
            ['restaurant_id' => 8, 'category' => 'Горячее', 'name' => 'Пельмени', 'description' => 'Русские пельмени со сметаной', 'price' => 5.80, 'is_available' => true],
            ['restaurant_id' => 8, 'category' => 'Горячее', 'name' => 'Биточки', 'description' => 'Паровые мясные биточки', 'price' => 5.00, 'is_available' => true],
            ['restaurant_id' => 8, 'category' => 'Напитки', 'name' => 'Чай с лимоном', 'description' => 'Чёрный чай с лимоном', 'price' => 2.20, 'is_available' => true],
            ['restaurant_id' => 8, 'category' => 'Десерты', 'name' => 'Творог', 'description' => 'Творог с сахаром', 'price' => 3.00, 'is_available' => true],

            // ДОПОЛНИМ Ресторан "Пивария" (ID 10) - НЕМЕЦКАЯ КУХНЯ
            ['restaurant_id' => 10, 'category' => 'Горячее', 'name' => 'Свиные рёбрышки', 'description' => 'Медово-горчичные свиные рёбрышки с капустой', 'price' => 32.00, 'is_available' => true],
            ['restaurant_id' => 10, 'category' => 'Горячее', 'name' => 'Баварские колбаски', 'description' => 'Традиционные немецкие колбаски с квашеной капустой', 'price' => 24.00, 'is_available' => true],
            ['restaurant_id' => 10, 'category' => 'Горячее', 'name' => 'Свиная рулька', 'description' => 'Запечённая свиная рулька с квашеной капустой', 'price' => 35.00, 'is_available' => true],
            ['restaurant_id' => 10, 'category' => 'Горячее', 'name' => 'Котлеты по-немецки', 'description' => 'Немецкие мясные котлеты с соусом', 'price' => 28.00, 'is_available' => true],
            ['restaurant_id' => 10, 'category' => 'Горячее', 'name' => 'Сосиски с пюре', 'description' => 'Немецкие сосиски с картофельным пюре', 'price' => 22.00, 'is_available' => true],
            ['restaurant_id' => 10, 'category' => 'Закуски', 'name' => 'Квашеная капуста', 'description' => 'Традиционная немецкая квашеная капуста', 'price' => 8.00, 'is_available' => true],
            ['restaurant_id' => 10, 'category' => 'Закуски', 'name' => 'Маринованные огурцы', 'description' => 'Немецкие маринованные огурцы', 'price' => 9.00, 'is_available' => true],
            ['restaurant_id' => 10, 'category' => 'Напитки', 'name' => 'Пиво светлое', 'description' => 'Немецкое светлое пиво 0.5л', 'price' => 6.00, 'is_available' => true],
            ['restaurant_id' => 10, 'category' => 'Напитки', 'name' => 'Пиво тёмное', 'description' => 'Немецкое тёмное пиво 0.5л', 'price' => 7.00, 'is_available' => true],
            ['restaurant_id' => 10, 'category' => 'Напитки', 'name' => 'Пшеничное пиво', 'description' => 'Немецкое пшеничное пиво 0.5л', 'price' => 8.00, 'is_available' => true],
            ['restaurant_id' => 10, 'category' => 'Десерты', 'name' => 'Шварцвальдский торт', 'description' => 'Классический немецкий шоколадный торт с вишней', 'price' => 18.00, 'is_available' => true],
            ['restaurant_id' => 10, 'category' => 'Десерты', 'name' => 'Яблочный штрудель', 'description' => 'Немецкий яблочный штрудель', 'price' => 16.00, 'is_available' => true],

            // ДОПОЛНИМ Столовую МГЛУ (ID 12) - АЗИАТСКАЯ КУХНЯ
            ['restaurant_id' => 12, 'category' => 'Горячее', 'name' => 'Лагман', 'description' => 'Уйгурская лапша с мясом и овощами', 'price' => 8.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Горячее', 'name' => 'Плов', 'description' => 'Узбекский плов с бараниной и рисом', 'price' => 9.50, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Горячее', 'name' => 'Беляши', 'description' => 'Татарские пирожки с мясом', 'price' => 6.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Горячее', 'name' => 'Самса', 'description' => 'Узбекская самса с мясом', 'price' => 5.50, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Горячее', 'name' => 'Чебуреки', 'description' => 'Крымские чебуреки', 'price' => 7.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Напитки', 'name' => 'Чай зелёный', 'description' => 'Китайский зелёный чай', 'price' => 3.50, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Напитки', 'name' => 'Компот', 'description' => 'Фруктовый компот', 'price' => 2.50, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Десерты', 'name' => 'Халва', 'description' => 'Восточная халва с семечками', 'price' => 4.50, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Десерты', 'name' => 'Чак-чак', 'description' => 'Татарский чак-чак', 'price' => 5.00, 'is_available' => true],

            // РЕСТОРАН "БАБОЧКА" (ID 12) - ИТАЛЬЯНСКАЯ КУХНЯ
            ['restaurant_id' => 12, 'category' => 'Пицца', 'name' => 'Маргарита', 'description' => 'Классическая пицца с томатами, моцареллой и базиликом', 'price' => 24.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Пицца', 'name' => 'Пепперони', 'description' => 'Пицца с пепперони и сыром', 'price' => 28.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Пицца', 'name' => 'Четыре сыра', 'description' => 'Пицца с четырьмя видами сыра', 'price' => 30.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Пицца', 'name' => 'Гавайская', 'description' => 'Пицца с ветчиной и ананасом', 'price' => 26.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Паста', 'name' => 'Карбонара', 'description' => 'Паста с беконом и сливками', 'price' => 22.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Паста', 'name' => 'Болоньезе', 'description' => 'Спагетти с мясным соусом болоньезе', 'price' => 20.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Паста', 'name' => 'Песто', 'description' => 'Паста с соусом песто и пармезаном', 'price' => 21.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Основные блюда', 'name' => 'Ризотто с морепродуктами', 'description' => 'Итальянское ризотто с морепродуктами', 'price' => 35.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Основные блюда', 'name' => 'Оссобуко', 'description' => 'Тушёная говяжья голень по-милански', 'price' => 38.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Закуски', 'name' => 'Брускетта', 'description' => 'Тосты с томатами и базиликом', 'price' => 14.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Закуски', 'name' => 'Антипасти', 'description' => 'Итальянская закуска с ветчиной и сыром', 'price' => 18.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Напитки', 'name' => 'Вино красное', 'description' => 'Итальянское красное вино (бокал)', 'price' => 12.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Напитки', 'name' => 'Вино белое', 'description' => 'Итальянское белое вино (бокал)', 'price' => 12.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Напитки', 'name' => 'Эспрессо', 'description' => 'Крепкий итальянский кофе', 'price' => 5.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Напитки', 'name' => 'Капучино', 'description' => 'Кофе с молочной пенкой', 'price' => 7.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Десерты', 'name' => 'Тирамису', 'description' => 'Классический итальянский десерт', 'price' => 15.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Десерты', 'name' => 'Панна котта', 'description' => 'Итальянский десерт с ягодным соусом', 'price' => 13.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Десерты', 'name' => 'Джелато', 'description' => 'Итальянское мороженое (3 шарика)', 'price' => 10.00, 'is_available' => true],

            // ДОПОЛНИМ КАФЕ "БЫСТРОЕДА" (ID 13) - ФАСТФУД
            ['restaurant_id' => 13, 'category' => 'Бургеры', 'name' => 'Бургер с говядиной', 'description' => 'Классический бургер с говяжьей котлетой', 'price' => 12.00, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Бургеры', 'name' => 'Чизбургер', 'description' => 'Бургер с говядиной и сыром', 'price' => 14.00, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Бургеры', 'name' => 'Биг Мак', 'description' => 'Двойной бургер с сыром', 'price' => 16.00, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Бургеры', 'name' => 'Чикенбургер', 'description' => 'Бургер с курицей', 'price' => 13.00, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Бургеры', 'name' => 'Вегетарианский бургер', 'description' => 'Бургер с овощной котлетой', 'price' => 11.00, 'is_available' => true],

            // КУРИЦА
            ['restaurant_id' => 13, 'category' => 'Курица', 'name' => 'Куриные наггетсы', 'description' => 'Хрустящие куриные наггетсы (6 шт)', 'price' => 10.00, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Курица', 'name' => 'Крылышки BBQ', 'description' => 'Крылышки в соусе BBQ (8 шт)', 'price' => 13.00, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Курица', 'name' => 'Стrips куриные', 'description' => 'Куриные полоски в панировке', 'price' => 11.00, 'is_available' => true],

            // ГАРНИРЫ
            ['restaurant_id' => 13, 'category' => 'Гарниры', 'name' => 'Картофель фри', 'description' => 'Жареный картофель фри', 'price' => 6.00, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Гарниры', 'name' => 'Картофель по-деревенски', 'description' => 'Запечённый картофель с травами', 'price' => 7.00, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Гарниры', 'name' => 'Овощи гриль', 'description' => 'Жареные овощи на гриле', 'price' => 8.00, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Гарниры', 'name' => 'Салат', 'description' => 'Свежий овощной салат', 'price' => 5.00, 'is_available' => true],

            // НАПИТКИ
            ['restaurant_id' => 13, 'category' => 'Напитки', 'name' => 'Кока-кола', 'description' => 'Газированный напиток 0.5л', 'price' => 3.50, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Напитки', 'name' => 'Спрайт', 'description' => 'Лимонад 0.5л', 'price' => 3.50, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Напитки', 'name' => 'Фанта', 'description' => 'Апельсиновый напиток 0.5л', 'price' => 3.50, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Напитки', 'name' => 'Кофе', 'description' => 'Американо', 'price' => 4.00, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Напитки', 'name' => 'Чай', 'description' => 'Чёрный чай с лимоном', 'price' => 3.00, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Напитки', 'name' => 'Молочный коктейль', 'description' => 'Ванильный молочный коктейль', 'price' => 5.00, 'is_available' => true],

            // ДЕСЕРТЫ
            ['restaurant_id' => 13, 'category' => 'Десерты', 'name' => 'Мороженое', 'description' => 'Ванильное мороженое в рожке', 'price' => 4.00, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Десерты', 'name' => 'Пирожное', 'description' => 'Шоколадное пирожное', 'price' => 6.00, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Десерты', 'name' => 'Чизкейк', 'description' => 'Нью-йоркский чизкейк', 'price' => 8.00, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Десерты', 'name' => 'Пончики', 'description' => 'Свежие пончики с глазурью', 'price' => 3.50, 'is_available' => true],

            // ===== МЕНЮ ДЛЯ НОВЫХ РЕСТОРАНОВ =====

            // СТОЛОВАЯ "У НАТАШИ" (ID 14)
            ['restaurant_id' => 14, 'category' => 'Супы', 'name' => 'Борщ', 'description' => 'Красный борщ с пампушками', 'price' => 3.50, 'is_available' => true],
            ['restaurant_id' => 14, 'category' => 'Супы', 'name' => 'Щи', 'description' => 'Капустные щи с мясом', 'price' => 3.20, 'is_available' => true],
            ['restaurant_id' => 14, 'category' => 'Горячее', 'name' => 'Картофельное пюре с котлетой', 'description' => 'Картофельное пюре с мясной котлетой', 'price' => 4.20, 'is_available' => true],
            ['restaurant_id' => 14, 'category' => 'Горячее', 'name' => 'Гречневая каша с мясом', 'description' => 'Гречневая каша с тушёным мясом', 'price' => 4.50, 'is_available' => true],
            ['restaurant_id' => 14, 'category' => 'Горячее', 'name' => 'Макароны по-флотски', 'description' => 'Макароны с фаршем', 'price' => 3.80, 'is_available' => true],
            ['restaurant_id' => 14, 'category' => 'Комплексные обеды', 'name' => 'Обед №1', 'description' => 'Суп + второе + компот', 'price' => 7.50, 'is_available' => true],
            ['restaurant_id' => 14, 'category' => 'Комплексные обеды', 'name' => 'Обед №2', 'description' => 'Салат + суп + второе', 'price' => 8.00, 'is_available' => true],
            ['restaurant_id' => 14, 'category' => 'Напитки', 'name' => 'Компот', 'description' => 'Свежезаваренный компот', 'price' => 1.80, 'is_available' => true],
            ['restaurant_id' => 14, 'category' => 'Напитки', 'name' => 'Чай', 'description' => 'Чёрный чай с лимоном', 'price' => 2.00, 'is_available' => true],
            ['restaurant_id' => 14, 'category' => 'Десерты', 'name' => 'Кисель', 'description' => 'Клюквенный кисель', 'price' => 2.50, 'is_available' => true],
            ['restaurant_id' => 14, 'category' => 'Десерты', 'name' => 'Творог', 'description' => 'Творог с сахаром', 'price' => 3.00, 'is_available' => true],

            // СТОЛОВАЯ "АКАДЕМИЯ" (ID 15)
            ['restaurant_id' => 15, 'category' => 'Супы', 'name' => 'Солянка', 'description' => 'Мясная солянка с оливками', 'price' => 4.00, 'is_available' => true],
            ['restaurant_id' => 15, 'category' => 'Супы', 'name' => 'Гороховый суп', 'description' => 'Суп из гороха с копчёностями', 'price' => 3.80, 'is_available' => true],
            ['restaurant_id' => 15, 'category' => 'Горячее', 'name' => 'Рыба запеченная', 'description' => 'Запеченная рыба с овощами', 'price' => 5.50, 'is_available' => true],
            ['restaurant_id' => 15, 'category' => 'Горячее', 'name' => 'Курица с рисом', 'description' => 'Курица с отварным рисом', 'price' => 4.80, 'is_available' => true],
            ['restaurant_id' => 15, 'category' => 'Горячее', 'name' => 'Пельмени', 'description' => 'Русские пельмени со сметаной', 'price' => 4.20, 'is_available' => true],
            ['restaurant_id' => 15, 'category' => 'Комплексные обеды', 'name' => 'Бизнес-ланч', 'description' => 'Суп + салат + второе + напиток', 'price' => 9.50, 'is_available' => true],
            ['restaurant_id' => 15, 'category' => 'Напитки', 'name' => 'Квас', 'description' => 'Домашний квас', 'price' => 2.20, 'is_available' => true],
            ['restaurant_id' => 15, 'category' => 'Десерты', 'name' => 'Блины', 'description' => 'Блины со сметаной', 'price' => 3.50, 'is_available' => true],

            // СТОЛОВАЯ "ЦЕНТРАЛЬНАЯ" (ID 16)
            ['restaurant_id' => 16, 'category' => 'Супы', 'name' => 'Уха', 'description' => 'Рыбная уха из свежей рыбы', 'price' => 4.50, 'is_available' => true],
            ['restaurant_id' => 16, 'category' => 'Горячее', 'name' => 'Биточки', 'description' => 'Паровые мясные биточки', 'price' => 4.00, 'is_available' => true],
            ['restaurant_id' => 16, 'category' => 'Горячее', 'name' => 'Котлеты с пюре', 'description' => 'Мясные котлеты с картофельным пюре', 'price' => 4.80, 'is_available' => true],
            ['restaurant_id' => 16, 'category' => 'Горячее', 'name' => 'Голубцы', 'description' => 'Капустные голубцы с мясом', 'price' => 5.20, 'is_available' => true],
            ['restaurant_id' => 16, 'category' => 'Комплексные обеды', 'name' => 'Студенческий обед', 'description' => 'Суп + второе + десерт', 'price' => 6.50, 'is_available' => true],
            ['restaurant_id' => 16, 'category' => 'Напитки', 'name' => 'Морс', 'description' => 'Клюквенный морс', 'price' => 2.00, 'is_available' => true],
            ['restaurant_id' => 16, 'category' => 'Десерты', 'name' => 'Запеканка', 'description' => 'Творожная запеканка', 'price' => 3.20, 'is_available' => true],

            // КАФЕ "ВАРЕНИЧНАЯ №1" (ID 17) - УКРАИНСКАЯ КУХНЯ
            ['restaurant_id' => 17, 'category' => 'Вареники', 'name' => 'Вареники с картошкой', 'description' => 'Традиционные вареники с картофелем', 'price' => 8.00, 'is_available' => true],
            ['restaurant_id' => 17, 'category' => 'Вареники', 'name' => 'Вареники с вишней', 'description' => 'Сладкие вареники с вишней', 'price' => 9.00, 'is_available' => true],
            ['restaurant_id' => 17, 'category' => 'Вареники', 'name' => 'Вареники с мясом', 'description' => 'Вареники с мясной начинкой', 'price' => 10.00, 'is_available' => true],
            ['restaurant_id' => 17, 'category' => 'Горячие блюда', 'name' => 'Деруны', 'description' => 'Картофельные оладьи с салом', 'price' => 12.00, 'is_available' => true],
            ['restaurant_id' => 17, 'category' => 'Горячие блюда', 'name' => 'Борщ', 'description' => 'Украинский борщ с пампушками', 'price' => 7.50, 'is_available' => true],
            ['restaurant_id' => 17, 'category' => 'Закуски', 'name' => 'Сало', 'description' => 'Сало с хлебом и чесноком', 'price' => 8.50, 'is_available' => true],
            ['restaurant_id' => 17, 'category' => 'Напитки', 'name' => 'Компот', 'description' => 'Свежий компот из сухофруктов', 'price' => 3.00, 'is_available' => true],
            ['restaurant_id' => 17, 'category' => 'Десерты', 'name' => 'Пампушки', 'description' => 'Сладкие пампушки с маком', 'price' => 5.00, 'is_available' => true],

            // КАФЕ "КОФЕ ХАУЗ" (ID 18) - ЕВРОПЕЙСКАЯ КУХНЯ
            ['restaurant_id' => 18, 'category' => 'Завтраки', 'name' => 'Овсяная каша', 'description' => 'Овсяная каша с фруктами и йогуртом', 'price' => 5.50, 'is_available' => true],
            ['restaurant_id' => 18, 'category' => 'Завтраки', 'name' => 'Яичница с беконом', 'description' => 'Яичница с беконом и тостами', 'price' => 8.00, 'is_available' => true],
            ['restaurant_id' => 18, 'category' => 'Салаты', 'name' => 'Цезарь', 'description' => 'Салат Цезарь с курицей и пармезаном', 'price' => 12.00, 'is_available' => true],
            ['restaurant_id' => 18, 'category' => 'Салаты', 'name' => 'Греческий', 'description' => 'Овощной салат с фетой и оливками', 'price' => 10.00, 'is_available' => true],
            ['restaurant_id' => 18, 'category' => 'Горячие блюда', 'name' => 'Паста Карбонара', 'description' => 'Паста с беконом и сливками', 'price' => 15.00, 'is_available' => true],
            ['restaurant_id' => 18, 'category' => 'Горячие блюда', 'name' => 'Стейк', 'description' => 'Говяжий стейк с овощами гриль', 'price' => 25.00, 'is_available' => true],
            ['restaurant_id' => 18, 'category' => 'Десерты', 'name' => 'Чизкейк', 'description' => 'Нью-йоркский чизкейк', 'price' => 8.00, 'is_available' => true],
            ['restaurant_id' => 18, 'category' => 'Десерты', 'name' => 'Тирамису', 'description' => 'Классический итальянский десерт', 'price' => 9.00, 'is_available' => true],
            ['restaurant_id' => 18, 'category' => 'Напитки', 'name' => 'Капучино', 'description' => 'Кофе с молочной пенкой', 'price' => 5.00, 'is_available' => true],
            ['restaurant_id' => 18, 'category' => 'Напитки', 'name' => 'Фреш', 'description' => 'Свежий апельсиновый сок', 'price' => 6.00, 'is_available' => true],

            // КАФЕ "МАКДОНАЛЬДС" (ID 19) - ФАСТФУД
            ['restaurant_id' => 19, 'category' => 'Бургеры', 'name' => 'Биг Мак', 'description' => 'Двойной бургер с сыром', 'price' => 8.50, 'is_available' => true],
            ['restaurant_id' => 19, 'category' => 'Бургеры', 'name' => 'Чизбургер', 'description' => 'Бургер с говядиной и сыром', 'price' => 6.50, 'is_available' => true],
            ['restaurant_id' => 19, 'category' => 'Бургеры', 'name' => 'Чикенбургер', 'description' => 'Бургер с курицей', 'price' => 7.00, 'is_available' => true],
            ['restaurant_id' => 19, 'category' => 'Курица', 'name' => 'Куриные наггетсы', 'description' => 'Хрустящие куриные наггетсы (6 шт)', 'price' => 7.50, 'is_available' => true],
            ['restaurant_id' => 19, 'category' => 'Курица', 'name' => 'Крылышки', 'description' => 'Куриные крылышки BBQ', 'price' => 9.00, 'is_available' => true],
            ['restaurant_id' => 19, 'category' => 'Гарниры', 'name' => 'Картофель фри', 'description' => 'Жареный картофель фри', 'price' => 4.50, 'is_available' => true],
            ['restaurant_id' => 19, 'category' => 'Гарниры', 'name' => 'Картофель по-деревенски', 'description' => 'Запечённый картофель с травами', 'price' => 5.00, 'is_available' => true],
            ['restaurant_id' => 19, 'category' => 'Напитки', 'name' => 'Кола', 'description' => 'Газированный напиток', 'price' => 3.00, 'is_available' => true],
            ['restaurant_id' => 19, 'category' => 'Напитки', 'name' => 'Кофе', 'description' => 'Американо', 'price' => 3.50, 'is_available' => true],
            ['restaurant_id' => 19, 'category' => 'Десерты', 'name' => 'Мороженое', 'description' => 'Ванильное мороженое в рожке', 'price' => 3.00, 'is_available' => true],

            // КАФЕ "СТАРБАКС" (ID 20) - КОФЕЙНЯ
            ['restaurant_id' => 20, 'category' => 'Кофе', 'name' => 'Эспрессо', 'description' => 'Крепкий кофе эспрессо', 'price' => 4.50, 'is_available' => true],
            ['restaurant_id' => 20, 'category' => 'Кофе', 'name' => 'Капучино', 'description' => 'Кофе с молочной пенкой', 'price' => 6.00, 'is_available' => true],
            ['restaurant_id' => 20, 'category' => 'Кофе', 'name' => 'Латте', 'description' => 'Кофе латте с молоком', 'price' => 7.00, 'is_available' => true],
            ['restaurant_id' => 20, 'category' => 'Кофе', 'name' => 'Фраппучино', 'description' => 'Холодный кофейный напиток', 'price' => 8.50, 'is_available' => true],
            ['restaurant_id' => 20, 'category' => 'Чай', 'name' => 'Зелёный чай', 'description' => 'Китайский зелёный чай', 'price' => 5.00, 'is_available' => true],
            ['restaurant_id' => 20, 'category' => 'Чай', 'name' => 'Чёрный чай', 'description' => 'Классический чёрный чай', 'price' => 4.50, 'is_available' => true],
            ['restaurant_id' => 20, 'category' => 'Десерты', 'name' => 'Круассан', 'description' => 'Свежий французский круассан', 'price' => 4.00, 'is_available' => true],
            ['restaurant_id' => 20, 'category' => 'Десерты', 'name' => 'Маффин', 'description' => 'Шоколадный маффин', 'price' => 5.50, 'is_available' => true],
            ['restaurant_id' => 20, 'category' => 'Сэндвичи', 'name' => 'Клаб-сэндвич', 'description' => 'Тройной сэндвич с курицей', 'price' => 12.00, 'is_available' => true],
            ['restaurant_id' => 20, 'category' => 'Сэндвичи', 'name' => 'Тунец-сэндвич', 'description' => 'Сэндвич с тунцом и овощами', 'price' => 10.50, 'is_available' => true],

            // РЕСТОРАН "ЛИДО" (ID 21) - БЕЛОРУССКАЯ КУХНЯ
            ['restaurant_id' => 21, 'category' => 'Закуски', 'name' => 'Сало с чесноком', 'description' => 'Традиционное белорусское сало', 'price' => 12.00, 'is_available' => true],
            ['restaurant_id' => 21, 'category' => 'Закуски', 'name' => 'Грибы солёные', 'description' => 'Солёные белорусские грибы', 'price' => 15.00, 'is_available' => true],
            ['restaurant_id' => 21, 'category' => 'Супы', 'name' => 'Борщ', 'description' => 'Красный борщ с пампушками', 'price' => 8.00, 'is_available' => true],
            ['restaurant_id' => 21, 'category' => 'Белорусская кухня', 'name' => 'Драники', 'description' => 'Картофельные оладьи с сметаной', 'price' => 14.00, 'is_available' => true],
            ['restaurant_id' => 21, 'category' => 'Белорусская кухня', 'name' => 'Клецки', 'description' => 'Мучные клецки со сметаной', 'price' => 16.00, 'is_available' => true],
            ['restaurant_id' => 21, 'category' => 'Горячие блюда', 'name' => 'Жаркое', 'description' => 'Мясное жаркое с картофелем', 'price' => 22.00, 'is_available' => true],
            ['restaurant_id' => 21, 'category' => 'Горячие блюда', 'name' => 'Бабка', 'description' => 'Картофельная бабка с мясом', 'price' => 18.00, 'is_available' => true],
            ['restaurant_id' => 21, 'category' => 'Напитки', 'name' => 'Квас', 'description' => 'Традиционный белорусский квас', 'price' => 5.00, 'is_available' => true],
            ['restaurant_id' => 21, 'category' => 'Десерты', 'name' => 'Блины', 'description' => 'Тонкие блины с вареньем', 'price' => 12.00, 'is_available' => true],

            // РЕСТОРАН "БАКЛАЖАН" (ID 22) - ГРУЗИНСКАЯ КУХНЯ
            ['restaurant_id' => 22, 'category' => 'Закуски', 'name' => 'Бадриджан', 'description' => 'Запечённые баклажаны с орехами', 'price' => 12.00, 'is_available' => true],
            ['restaurant_id' => 22, 'category' => 'Закуски', 'name' => 'Пхали', 'description' => 'Грузинская закуска из шпината', 'price' => 14.00, 'is_available' => true],
            ['restaurant_id' => 22, 'category' => 'Горячие блюда', 'name' => 'Харчо', 'description' => 'Грузинский мясной суп', 'price' => 16.00, 'is_available' => true],
            ['restaurant_id' => 22, 'category' => 'Горячие блюда', 'name' => 'Чурчхела', 'description' => 'Грузинская сладость', 'price' => 8.00, 'is_available' => true],
            ['restaurant_id' => 22, 'category' => 'Горячие блюда', 'name' => 'Лобио', 'description' => 'Фасоль по-грузински', 'price' => 15.00, 'is_available' => true],
            ['restaurant_id' => 22, 'category' => 'Горячие блюда', 'name' => 'Оджахури', 'description' => 'Жареное мясо с луком', 'price' => 24.00, 'is_available' => true],
            ['restaurant_id' => 22, 'category' => 'Горячие блюда', 'name' => 'Сациви', 'description' => 'Курица в ореховом соусе', 'price' => 22.00, 'is_available' => true],
            ['restaurant_id' => 22, 'category' => 'Напитки', 'name' => 'Вино грузинское', 'description' => 'Красное грузинское вино (бокал)', 'price' => 12.00, 'is_available' => true],
            ['restaurant_id' => 22, 'category' => 'Десерты', 'name' => 'Пахлава', 'description' => 'Грузинская пахлава', 'price' => 10.00, 'is_available' => true],

            // РЕСТОРАН "ПИЦЦА ТЕМПО" (ID 23) - ИТАЛЬЯНСКАЯ КУХНЯ
            ['restaurant_id' => 23, 'category' => 'Пицца', 'name' => 'Маргарита', 'description' => 'Классическая пицца с томатами и моцареллой', 'price' => 18.00, 'is_available' => true],
            ['restaurant_id' => 23, 'category' => 'Пицца', 'name' => 'Пепперони', 'description' => 'Пицца с пепперони и сыром', 'price' => 22.00, 'is_available' => true],
            ['restaurant_id' => 23, 'category' => 'Пицца', 'name' => 'Четыре сыра', 'description' => 'Пицца с четырьмя видами сыра', 'price' => 25.00, 'is_available' => true],
            ['restaurant_id' => 23, 'category' => 'Пицца', 'name' => 'Гавайская', 'description' => 'Пицца с ветчиной и ананасом', 'price' => 23.00, 'is_available' => true],
            ['restaurant_id' => 23, 'category' => 'Паста', 'name' => 'Карбонара', 'description' => 'Паста с беконом и сливками', 'price' => 19.00, 'is_available' => true],
            ['restaurant_id' => 23, 'category' => 'Паста', 'name' => 'Болоньезе', 'description' => 'Спагетти с мясным соусом', 'price' => 17.00, 'is_available' => true],
            ['restaurant_id' => 23, 'category' => 'Паста', 'name' => 'Песто', 'description' => 'Паста с соусом песто', 'price' => 16.00, 'is_available' => true],
            ['restaurant_id' => 23, 'category' => 'Закуски', 'name' => 'Брускетта', 'description' => 'Тосты с томатами и базиликом', 'price' => 12.00, 'is_available' => true],
            ['restaurant_id' => 23, 'category' => 'Напитки', 'name' => 'Вино красное', 'description' => 'Итальянское красное вино (бокал)', 'price' => 10.00, 'is_available' => true],
            ['restaurant_id' => 23, 'category' => 'Десерты', 'name' => 'Тирамису', 'description' => 'Классический итальянский десерт', 'price' => 14.00, 'is_available' => true],

            // РЕСТОРАН "ВАСИЛЬКИ" (ID 24) - БЕЛОРУССКАЯ КУХНЯ
            ['restaurant_id' => 24, 'category' => 'Закуски', 'name' => 'Сыр', 'description' => 'Белорусский сыр с мёдом', 'price' => 15.00, 'is_available' => true],
            ['restaurant_id' => 24, 'category' => 'Закуски', 'name' => 'Колбаса', 'description' => 'Домашняя белорусская колбаса', 'price' => 18.00, 'is_available' => true],
            ['restaurant_id' => 24, 'category' => 'Супы', 'name' => 'Солянка', 'description' => 'Мясная солянка с оливками', 'price' => 12.00, 'is_available' => true],
            ['restaurant_id' => 24, 'category' => 'Белорусская кухня', 'name' => 'Мачанка', 'description' => 'Свиной желудок с овощами', 'price' => 20.00, 'is_available' => true],
            ['restaurant_id' => 24, 'category' => 'Белорусская кухня', 'name' => 'Цепелины', 'description' => 'Картофельные зразы с мясом', 'price' => 22.00, 'is_available' => true],
            ['restaurant_id' => 24, 'category' => 'Горячие блюда', 'name' => 'Свинина', 'description' => 'Жареная свинина с гарниром', 'price' => 28.00, 'is_available' => true],
            ['restaurant_id' => 24, 'category' => 'Горячие блюда', 'name' => 'Рыба', 'description' => 'Запечённая рыба с овощами', 'price' => 24.00, 'is_available' => true],
            ['restaurant_id' => 24, 'category' => 'Напитки', 'name' => 'Квас', 'description' => 'Домашний квас', 'price' => 6.00, 'is_available' => true],
            ['restaurant_id' => 24, 'category' => 'Десерты', 'name' => 'Кулага', 'description' => 'Белорусский кисель с ягодами', 'price' => 10.00, 'is_available' => true],

            // ===== ДОБАВИМ ПОЛНЫЕ МЕНЮ ДЛЯ ОСТАЛЬНЫХ ЗАВЕДЕНИЙ =====

            // Столовая БНТУ (ID 5) - расширенное меню
            ['restaurant_id' => 5, 'category' => 'Супы', 'name' => 'Суп харчо', 'description' => 'Острый грузинский суп', 'price' => 4.00, 'is_available' => true],
            ['restaurant_id' => 5, 'category' => 'Горячее', 'name' => 'Макароны с сосиской', 'description' => 'Макароны с сосиской', 'price' => 3.80, 'is_available' => true],
            ['restaurant_id' => 5, 'category' => 'Горячее', 'name' => 'Рис с овощами', 'description' => 'Рис с овощами', 'price' => 2.90, 'is_available' => true],
            ['restaurant_id' => 5, 'category' => 'Комплексные обеды', 'name' => 'Студенческий комплекс', 'description' => 'Суп + второе + напиток', 'price' => 7.00, 'is_available' => true],
            ['restaurant_id' => 5, 'category' => 'Напитки', 'name' => 'Чай с лимоном', 'description' => 'Чай с лимоном', 'price' => 2.00, 'is_available' => true],

            // Кафе "Трактир" (ID 6) - расширенное меню
            ['restaurant_id' => 6, 'category' => 'Белорусская кухня', 'name' => 'Бульба с салом', 'description' => 'Картофель с салом', 'price' => 12.00, 'is_available' => true],
            ['restaurant_id' => 6, 'category' => 'Горячее', 'name' => 'Свинина с клецками', 'description' => 'Свинина с клецками', 'price' => 22.00, 'is_available' => true],
            ['restaurant_id' => 6, 'category' => 'Белорусская кухня', 'name' => 'Драники с мясом', 'description' => 'Драники с мясом', 'price' => 16.00, 'is_available' => true],
            ['restaurant_id' => 6, 'category' => 'Напитки', 'name' => 'Квас', 'description' => 'Домашний квас', 'price' => 4.50, 'is_available' => true],
            ['restaurant_id' => 6, 'category' => 'Десерты', 'name' => 'Мёд с блинами', 'description' => 'Блины с мёдом', 'price' => 14.00, 'is_available' => true],

            // Столовая БГУ (ID 8) - расширенное меню
            ['restaurant_id' => 8, 'category' => 'Супы', 'name' => 'Уха', 'description' => 'Рыбный суп', 'price' => 4.50, 'is_available' => true],
            ['restaurant_id' => 8, 'category' => 'Горячее', 'name' => 'Гуляш с гречкой', 'description' => 'Гуляш с гречкой', 'price' => 5.20, 'is_available' => true],
            ['restaurant_id' => 8, 'category' => 'Горячее', 'name' => 'Овсяная каша', 'description' => 'Каша с фруктами', 'price' => 3.00, 'is_available' => true],
            ['restaurant_id' => 8, 'category' => 'Комплексные обеды', 'name' => 'Комплексный обед', 'description' => 'Суп + горячее + салат', 'price' => 8.50, 'is_available' => true],
            ['restaurant_id' => 8, 'category' => 'Десерты', 'name' => 'Йогурт', 'description' => 'Йогурт с ягодами', 'price' => 2.00, 'is_available' => true],

            // Ресторан "Пивария" (ID 10) - расширенное меню
            ['restaurant_id' => 10, 'category' => 'Горячее', 'name' => 'Свиные рёбрышки', 'description' => 'Рёбрышки в медово-горчичном соусе', 'price' => 32.00, 'is_available' => true],
            ['restaurant_id' => 10, 'category' => 'Горячее', 'name' => 'Баварские колбаски', 'description' => 'Колбаски с капустой', 'price' => 24.00, 'is_available' => true],
            ['restaurant_id' => 10, 'category' => 'Горячее', 'name' => 'Свиная рулька', 'description' => 'Запечённая рулька', 'price' => 35.00, 'is_available' => true],
            ['restaurant_id' => 10, 'category' => 'Напитки', 'name' => 'Пиво светлое', 'description' => 'Немецкое пиво 0.5л', 'price' => 6.00, 'is_available' => true],
            ['restaurant_id' => 10, 'category' => 'Десерты', 'name' => 'Шварцвальдский торт', 'description' => 'Шоколадный торт с вишней', 'price' => 18.00, 'is_available' => true],

            // Столовая МГЛУ (ID 12) - расширенное меню
            ['restaurant_id' => 12, 'category' => 'Горячее', 'name' => 'Лагман', 'description' => 'Уйгурская лапша', 'price' => 6.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Горячее', 'name' => 'Плов', 'description' => 'Узбекский плов', 'price' => 7.50, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Горячее', 'name' => 'Беляши', 'description' => 'Татарские пирожки', 'price' => 4.00, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Напитки', 'name' => 'Чай зелёный', 'description' => 'Зелёный чай', 'price' => 2.50, 'is_available' => true],
            ['restaurant_id' => 12, 'category' => 'Десерты', 'name' => 'Халва', 'description' => 'Восточная халва', 'price' => 3.50, 'is_available' => true],

            // Ресторан "Итальянский" (ID 13) - расширенное меню
            ['restaurant_id' => 13, 'category' => 'Основные блюда', 'name' => 'Пицца Маргарита', 'description' => 'Классическая пицца', 'price' => 22.00, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Основные блюда', 'name' => 'Паста Болоньезе', 'description' => 'Паста с мясным соусом', 'price' => 25.00, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Основные блюда', 'name' => 'Оссобуко', 'description' => 'Тушёная говяжья голень', 'price' => 45.00, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Основные блюда', 'name' => 'Ризотто', 'description' => 'Ризотто с морепродуктами', 'price' => 38.00, 'is_available' => true],
            ['restaurant_id' => 13, 'category' => 'Десерты', 'name' => 'Тирамису', 'description' => 'Итальянский десерт', 'price' => 15.00, 'is_available' => true],
        ];

        foreach ($dishes as $dishData) {
            Dish::create($dishData);
        }
    }
}
