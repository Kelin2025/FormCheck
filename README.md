# О FormCheck
**FormCheck** - простой PHP-класс для проверки корректности введенных данных.

Небольшой пример, проверяющий элементы `$_POST`:
```php
$form = new FormCheck($_POST);
$form
 ->check('Логин','login')->type('login')->min(3)->max(50)
 ->check('Пароль','password')->type('password')->min(3)->max(50)
 ->check('Повтор пароля','password2')->equal('password')
 ->check('Почтовый ящик','email')->type('mail')->max(100)
 ->check('Имя','name')->type('name')->min(2)->max(50)
 ->check('Фамилия','surname')->type('name')->min(2)->max(50)
 ->check('Отчество','lastname')->type('name')->min(2)->max(50)
 ->check('Дополнительное поле','test')->custom(function($self,$value){
   if($value == '111') $self->add_error('равно 111');
 });
print_r($form->get_errors());
```
Результат:
```php
[
  'login' => [
    0 => 'Поле "Логин" должно содержать только английские буквы, цифры и символ нижнего подчеркивания.',
    1 => 'Поле "Логин" не должно быть короче 3 символов.'
  ],
  'password' => [ 
    0 => 'Поле "Пароль" содержит недопустимые символы.'
  ],
  'password2' => [
    0 => 'Поле "Повтор пароля" не совпадает с полем "Пароль"'
  ],
  'email' => [
    0 => 'Поле "Почтовый ящик" содержит недопустимые символы.'
  ],
  'name' => [
    0 => 'Поле "Имя" должно содержать только английские или русские буквы, символ пробела и дефис.'
  ],
  'surname' => [
    0 => 'Поле "Фамилия" должно содержать только английские или русские буквы, символ пробела и дефис.'
  ],
  'test' =>  [
    0 => 'Поле "Дополнительное поле" равно 111'
  ]
]
```
# Работа с классом
## Создание экземпляра класса
```php
$form = new FormCheck($_POST)
```
Массив `$_POST` будет помещен в `$form->input` для дальнейшей работы с ним.
## Выбор элемента
```php
$form->check('Пароль','password')
```
Выбор элемента массива `$form->input` с ключом `password`. Значение первого аргумента (`Пароль`) используется для вывода ошибок.
## Регулярные выражения
```php
$form->type('password')
```
Проверка по распространенным регулярным выражениям.

**Примечание**: список всех доступных регулярных выражений можно посмотреть [здесь](https://github.com/Kelin2025/FormCheck/blob/master/regexps.md)
```php
$form->reg('/^[1-9]{2}$/')
```
Проверка текущего элемента на соответствие заданному регулярному выражению.
## Проверка длины
Проверка текущего элемента на соответствие введенному регулярному выражению.
```php
$form->min(5)
```
Проверка текущего элемента на минимальную длину.
```php
$form->max(50)
```
Проверка текущего элемента на максимальную длину.
## Эквивалентность
```php
$form->equal('Повторите пароль','password2')
```
Проверка на идентичность текущего элемнта и элемента с ключом `password2`. Значение первого аргумента (`Повторите пароль`) используется для вывода ошибок.
## Пользовательские проверки
```php
$form->custom(function($self,$value){
 if($value == '111') $self->add_error('равно 111');
});
```
В первый аргумент (`$self`) передается экземпляр класса, во второй (`$value`) - значение текущего элемента.

В данном случае будет добавлена ошибка, если значение текущего элемента будет равно `111`.
## Добавление ошибок
```php
$form->add_error('некорректно');
```
Добавление ошибки у текущего поля. Перед ним будет дописано **название поля**, то есть писать его вручную **не нужно**.

В данном случае сообщение об ошибке будет таким:
```
Поле "Пароль" некорректно
```
## Вывод ошибок
```php
$form->get_errors();
$form->get_errors(true);
```
Функция возвращает массив ошибок. В первом случае функция вернет массив, в котором для каждого элемента будет свой массив ошибок.
Во втором - все ошибки будут в одном массиве.

Пример работы `$form->get_errors()`:
```php
[
  'login' => [
    0 => 'Поле "Логин" должно содержать только английские буквы, цифры и символ нижнего подчеркивания.',
    1 => 'Поле "Логин" не должно быть короче 3 символов.'
  ],
  'password' => [ 
    0 => 'Поле "Пароль" содержит недопустимые символы.'
  ],
  'password2' => [
    0 => 'Поле "Повтор пароля" не совпадает с полем "Пароль"'
  ],
  'email' => [
    0 => 'Поле "Почтовый ящик" содержит недопустимые символы.'
  ],
  'name' => [
    0 => 'Поле "Имя" должно содержать только английские или русские буквы, символ пробела и дефис.'
  ],
  'surname' => [
    0 => 'Поле "Фамилия" должно содержать только английские или русские буквы, символ пробела и дефис.'
  ],
  'test' =>  [
    0 => 'Поле "Дополнительное поле" равно 111'
  ]
]
```
Пример работы `$form->get_errors(true)`:
```php
[
  0 => 'Поле "Логин" должно содержать только английские буквы, цифры и символ нижнего подчеркивания.'
  1 => 'Поле "Логин" не должно быть короче 3 символов.'
  2 => 'Поле "Имя" должно содержать только английские или русские буквы, символ пробела и дефис.'
  3 => 'Поле "Фамилия" должно содержать только английские или русские буквы, символ пробела и дефис.'
  4 => 'Поле "Отчество" должно содержать только английские или русские буквы, символ пробела и дефис.'
  5 => 'Поле "Тест" равно 111'
]
```
