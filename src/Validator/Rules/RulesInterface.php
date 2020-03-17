<?php


namespace Quiz\Validator\Rules;


interface RulesInterface
{
    public function getMessageError(): string;
    public function checkRule(): bool;
}