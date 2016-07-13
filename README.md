# FormCheck
## Что это?
FormCheck - простой PHP-класс для проверки корректности введенных данных.

Небольшой пример, проверяющий элементы $_POST:
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
print_r($form->get_errors());`
```
Результат:
```
array
'login' => 
  array
    0 => string 'Поле "Логин" должно содержать только английские буквы, цифры и символ нижнего подчеркивания.'
    1 => string 'Поле "Логин" не должно быть короче 3 символов.'
'password' => 
  array
    0 => string 'Поле "Пароль" содержит недопустимые символы.'
'password2' => 
  array
    0 => string 'Поле "Повтор пароля" не совпадает с полем "Пароль"'
'email' => 
  array
    0 => string 'Поле "Почтовый ящик" содержит недопустимые символы.'
'name' => 
  array
    0 => string 'Поле "Имя" должно содержать только английские или русские буквы, символ пробела и дефис.'
'surname' => 
  array
    0 => string 'Поле "Фамилия" должно содержать только английские или русские буквы, символ пробела и дефис.'
'test' => 
  array
    0 => string 'Поле "Дополнительное поле" равно 111'
```
