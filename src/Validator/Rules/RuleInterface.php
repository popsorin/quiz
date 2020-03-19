<?php


namespace Quiz\Validator\Rules;


interface RuleInterface
{
    public function getMessageError(): string;
    public function checkRule(): bool;
}