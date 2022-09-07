<?php

return [
    'entity_types' => [
        ['title' => 'Объявление', 'slug' => 'announcement'],
        ['title' => 'Новость', 'slug' => 'article'],
        ['title' => 'Профиль', 'slug' => 'profile'],
        ['title' => 'Телефон', 'slug' => 'phones'],
        ['title' => 'Фотофакт', 'slug' => 'photo fact'],
        ['title' => 'Организация', 'slug' => 'organization'],
        ['title' => 'Вакансия', 'slug' => 'vacancy'],
        ['title' => 'Резюме', 'slug' => 'cv'],
        ['title' => 'Афиша', 'slug' => 'posters'],
        ['title' => 'Фотогаллерея', 'slug' => 'photo gallery'],
        ['title' => 'Статическая страница', 'slug' => 'static'],
    ],
    'category_types' => [
        ['category_title' => 'Объявление', 'category_slug' => 'announcement'],
        ['category_title' => 'Новость', 'category_slug' => 'article'],
    ],
    'categories' => [
        ['category_id' => 2, 'category_title' => 'Жлобин', 'category_slug' => 'zhlobin'],
        ['category_id' => 2, 'category_title' => 'Гомельская область', 'category_slug' => 'gomel region'],
        ['category_id' => 2, 'category_title' => 'Беларусь', 'category_slug' => 'belarus'],
        ['category_id' => 2, 'category_title' => 'Мир', 'category_slug' => 'earth'],
        ['category_id' => 2, 'category_title' => 'Спорт', 'category_slug' => 'sport'],
        ['category_id' => 2, 'category_title' => 'Культура', 'category_slug' => 'culture'],
        ['category_id' => 2, 'category_title' => 'Происшествия', 'category_slug' => 'incidents'],
        ['category_id' => 2, 'category_title' => 'Праздник', 'category_slug' => 'celebration'],
        ['category_id' => 1, 'category_title' => 'Продаю', 'category_slug' => 'sell'],
        ['category_id' => 1, 'category_title' => 'Покупаю', 'category_slug' => 'buy'],
        ['category_id' => 1, 'category_title' => 'Сдам', 'category_slug' => 'rent'],
        ['category_id' => 1, 'category_title' => 'Сниму', 'category_slug' => 'rent an'],
        ['category_id' => 1, 'category_title' => 'Потеря', 'category_slug' => 'loss'],
        ['category_id' => 1, 'category_title' => 'Находка', 'category_slug' => 'found'],
    ],
    'statutes' => [
        ['code' => 'checking'],
        ['code' => 'active'],
        ['code' => 'blocked'],
    ],
    'roles' => [
        ['code' => 'admin'],
        ['code' => 'editor'],
        ['code' => 'author'],
    ],
    'entity_statuses' => [
        ['code' => 'active'],
        ['code' => 'under_moderation'],
    ]
];
