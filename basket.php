<?php
declare(strict_types=1);
const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;

$operations = [
    OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
    OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
    OPERATION_DELETE => OPERATION_DELETE . '. Удалить товар из списка покупок.',
    OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
];

$items = [];

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
    }

    echo "\n ----- \n";
} while ($operationNumber !== "0");

echo 'Программа завершена' . PHP_EOL;

function ShowShoppingList ($items, $isItemsEmpty): void
{
    if($isItemsEmpty) {
        echo 'Ваш список покупок пуст.' . PHP_EOL;
        return;
    }
    echo "Ваш список покупок: " . PHP_EOL;
    foreach ($items as $item) {
        echo "\t" . $item . PHP_EOL;
    }
    echo 'Всего ' . count($items) . ' позиций. '. PHP_EOL;
    echo 'Нажмите enter для продолжения';
    fgets(STDIN);
}

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

function AddItem (&$items, &$isItemsEmpty): void
{
    echo "Введение название товара для добавления в список: \n> ";
    $itemName = trim(fgets(STDIN));
    if($itemName !== ""){
        $items[] = $itemName;
        SetIsItemsEmpty($items, $isItemsEmpty);
        ShowShoppingList($items, $isItemsEmpty);
    }
}

function DeleteItem (&$items, &$isItemsEmpty): bool
{
    if($isItemsEmpty){
        echo "Ваш список покупок пуст, удалять не чего!" . PHP_EOL;
        return false;
    }
    // Проверить, есть ли товары в списке? Если нет, то сказать об этом и попросить ввести другую операцию
    echo 'Введение название товара для удаления из списка:' . PHP_EOL . '> ';
    $itemName = trim(fgets(STDIN));

    if (in_array($itemName, $items, true) !== false) {
        while (($key = array_search($itemName, $items, true)) !== false) {
            unset($items[$key]);
        }
        echo "\nТовар <" . $itemName ."> удален из списка" . PHP_EOL;
        ShowShoppingList($items, $isItemsEmpty);
    }else{
        echo "Товара с названием <" . $itemName . "> в списке нет!" . PHP_EOL;
        return false;
    }
    SetIsItemsEmpty($items, $isItemsEmpty);
    return true;
}

function SetIsItemsEmpty ($items, &$isItemsEmpty): void
{
    if(count($items) === 0) {
        $isItemsEmpty = true;
        return;
    }
    $isItemsEmpty = false;
}