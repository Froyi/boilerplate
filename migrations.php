<?php /** @noinspection ALL */

return [
    "2019-08-14" => [
        1 => "create table user
(
    userId       varchar(200) not null
        primary key,
    email        varchar(200) not null,
    passwordHash varchar(200) not null
);"
    ]
];