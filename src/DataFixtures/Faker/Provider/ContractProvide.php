<?php

#api/src/DataFixtures/Faker/Provider/ContractProvide.php
namespace App\DataFixtures\Faker\Provider;

class ContractProvide
{
    public static function getContractType()
    {
        $type = ['CDI', 'CDD', 'Stage', 'Student'];
        return $type[rand(0, 3)];
    }
}
