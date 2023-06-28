<?php

use extas\components\repositories\RepoItem;

return [
    "name" => "jeyroik/extas-conditions",
    "tables" => [
        'conditions' => [
            "namespace" => "extas\\repositories",
            "item_class" => "extas\\components\\conditions\\Condition",
            "pk" => "name",
            "aliases" => ["conditions"],
            "hooks" => [],
            "code" => [
                'create-before' => '\\' . RepoItem::class . '::setId($item);'
                                .'\\' . RepoItem::class . '::throwIfExist($this, $item, [\'name\']);'
                                .'\\' . RepoItem::class . '::addNameToAliases($item);'
            ]
        ]
    ]
];
