<?php declare(strict_types=1);
    namespace Models\Interfaces;

    interface IUser{
        public static  function GetToken(int $id) : string;
        public static function SetToken(string $token,int $id);

        public function GetId() : int;

        public function GetUserName() : string;

        public function GetTokenN() : string;

        public function GetType(): int;

        public function GetCompanyId(): int;

        public function GetCompanyName(): string;
    }