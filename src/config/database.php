<?php

function getDatabaseConfig(): array
{
    return [
        "database" => [
            "test" => [
                "url" => "pgsql:dbname=nurin_levart_test;host=db",
                "username" => "postgres",
                "password" => "postgres",
            ]
        ]
    ];
}
