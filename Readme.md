Этот код демонстрирует проявление бага, связанного с тем, что symfony/validator [полагается](https://github.com/symfony/symfony/blob/7bab5b265614de241913db27cb6654cca91f477d/src/Symfony/Component/Validator/Validator/RecursiveContextualValidator.php#L112) [на](https://github.com/symfony/symfony/blob/7bab5b265614de241913db27cb6654cca91f477d/src/Symfony/Component/Validator/Validator/RecursiveContextualValidator.php#L341) результат функции `spl_object_hash`, которая не гарантирует уникальность хеша из-за gc. Когда на класс перестают ссылаться, он уничтожается, однако при создании нового объекта, хеш вышеупомянутого уничтоженного класса может быть присвоен новому объекту.

Таким образом, непровалидированный объект, получая хеш уничтоженного успешно провалидированного объекта, [считается валидированным](https://github.com/symfony/symfony/blob/7bab5b265614de241913db27cb6654cca91f477d/src/Symfony/Component/Validator/Validator/RecursiveContextualValidator.php#L484-L489) и его проверка скипается.

```php
# http://php.net/manual/en/function.spl-object-hash.php#76220
# https://3v4l.org/gDgic
class Foo {}
class Bar {
    public $baz = 42;
}

$a = new Foo;
$hashA = spl_object_hash($a);

unset($a);

$b = new Bar;
$hashB = spl_object_hash($b);

var_dump($hashA === $hashB); // true
```

------

Здесь реализована валидация данных в `\App\Entity\Fields\Field::$value`, основываясь на типе поля `\App\Entity\Fields\Field::$type`.

------
**Суть проблемы:** при использовании кастомного валидатора, невалидные данные в полях **иногда** не проверяются и пропускаются как валидные.

**Решение:** хранить объекты, создаваемые в `Validator/Constraints/ValueByTypeValidator.php:30` до конца валидации.
Но, в целом, это поведение неочевидно и его последствия могут долго проявляться в виде плавающего бага.
