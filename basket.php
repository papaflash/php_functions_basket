<?php
declare(strict_types=1);
const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;
const OPERATION_RENAME = 4;
const OPERATION_ADD_QNT = 5;

$operations = [
    OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
    OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
    OPERATION_DELETE => OPERATION_DELETE . '. Удалить товар из списка покупок.',
    OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
    OPERATION_RENAME => OPERATION_RENAME . '. Изменить наименование',
    OPERATION_ADD_QNT => OPERATION_ADD_QNT . '. Указать кол-во товара'
];

$items = [];
//Флаг переменная, указывающая на пустой список
$isItemsEmpty = false;

do {
   // system('clear');
   system('cls'); // windows

    $operationNumber = ShowStartMenu($items, $operations, $isItemsEmpty);
    if(!CheckOperationNumber($operationNumber, $operations)) {
        continue;
    }

    switch ($operationNumber) {
        case OPERATION_ADD:
            AddItem($items, $isItemsEmpty);
            break;
        case OPERATION_DELETE:
            DeleteItem($items,$isItemsEmpty);
            break;
        case OPERATION_PRINT:
            ShowShoppingList($items, $isItemsEmpty);
            break;
        case OPERATION_RENAME:
             EditItemName($items, $isItemsEmpty);
             break;
        case OPERATION_ADD_QNT:
             AddQuantity($items, $isItemsEmpty);
             break;
    }

    echo "\n ----- \n";
} while ($operationNumber !== "0");

echo 'Программа завершена' . PHP_EOL;

//Показать список покупок
function ShowShoppingList ($items, $isItemsEmpty): void
{
    if($isItemsEmpty) {
        echo 'Ваш список покупок пуст.' . PHP_EOL;
        return;
    }
    echo "Ваш список покупок: " . PHP_EOL;
    foreach ($items as $item => $value) {
        echo "\t" . $item . " - " . $value . " шт." . PHP_EOL;
    }
    echo 'Всего ' . count($items) . ' позиций. '. PHP_EOL;
    echo 'Нажмите enter для продолжения';
    fgets(STDIN);
}

//Вывести стартового меню
function ShowStartMenu ($items ,$operations, &$isItemsEmpty): string
{
    // Проверить, есть ли товары в списке? Если нет, то не отображать пункт про удаление товаров
    SetIsItemsEmpty($items, $isItemsEmpty);
    echo 'Выберите операцию для выполнения: ' . PHP_EOL;

    foreach ($operations as $operation) {
        if($isItemsEmpty && mb_stristr($operation, (string)OPERATION_DELETE, true) !== false) {
            continue;
        }
        echo $operation . PHP_EOL;
    }
    echo PHP_EOL . '> ';
    return trim(fgets(STDIN));
}

//Проверить введенное пользователем число для выбора операции
function CheckOperationNumber ($operationNumber, $operations): bool
{
    if(array_key_exists($operationNumber, $operations)){
        echo 'Выбрана операция: '  . $operations[$operationNumber] . PHP_EOL;
        return true;
    }
    system('cls');
    echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL;
    return false;
}

//Добавить товар в список
function AddItem (&$items, &$isItemsEmpty): void
{
    echo "Введение название товара для добавления в список: \n> ";
    $itemName = trim(fgets(STDIN));
    if($itemName !== ""){
        $items[$itemName] = 1;
        SetIsItemsEmpty($items, $isItemsEmpty);
        ShowShoppingList($items, $isItemsEmpty);
    }
}

//Удалить товара из списка
function DeleteItem (&$items, &$isItemsEmpty): void
{
    if($isItemsEmpty){
        echo "Ваш список покупок пуст, удалять не чего!" . PHP_EOL;
        return;
    }
    // Проверить, есть ли товары в списке? Если нет, то сказать об этом и попросить ввести другую операцию
    echo 'Введите название товара для удаления из списка:' . PHP_EOL . '> ';
    $itemName = trim(fgets(STDIN));

    if (array_key_exists($itemName, $items) !== false) {
        unset($items[$itemName]);
        echo "\nТовар <" . $itemName ."> удален из списка" . PHP_EOL;
        ShowShoppingList($items, $isItemsEmpty);
    }else{
        echo "Товара с названием <" . $itemName . "> в списке нет!" . PHP_EOL;
    }
    SetIsItemsEmpty($items, $isItemsEmpty);
}
//Изменить имя товара
function EditItemName (&$items, &$isItemsEmpty): void
{
    if(!$isItemsEmpty){
        echo "\nВведите текущее название товара: ";
        $currName = trim(fgets(STDIN));
        if(array_key_exists($currName, $items) !== false) {
            echo "\nВведите новое название товара: ";
            $newName = trim(fgets(STDIN));
            if($newName !== $currName && $newName !== ""){
                $items[$newName] = $items[$currName];
                unset($items[$currName]);
                ShowShoppingList($items, $isItemsEmpty);
            }
        }else{
            echo "Товара с названием <" . $currName . "> в списке нет!" . PHP_EOL;
        }
    }else{
        echo "\nСписок пуст!" . PHP_EOL;
    }
}
//Указать кол-во товара
function AddQuantity (&$items, $isItemsEmpty): void
{
        if($isItemsEmpty){
            echo "\nСписок пуст" . PHP_EOL;
            return;
        }
        echo "\nУкажите название товара: ";
        $name = trim(fgets(STDIN));
        if($name !== ""){
            echo "\nВведите кол-во товара: ";
            $qnt = trim(fgets(STDIN));
            if(is_numeric($qnt) && $qnt > 0){
                $items[$name] = $qnt;
                ShowShoppingList($items, $isItemsEmpty);
            }
        }
}
//Установить флаг в true/false в зависимости от того пустой список или нет
function SetIsItemsEmpty ($items, &$isItemsEmpty): void
{
    if(count($items) === 0) {
        $isItemsEmpty = true;
        return;
    }
    $isItemsEmpty = false;
}