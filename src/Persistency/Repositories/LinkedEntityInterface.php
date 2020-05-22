<?php
namespace Quiz\Persistency\Repositories;

interface LinkedEntityInterface {
    public function insertIntoLinkTable(int $id, int $linkId): bool;
    public function getLinkTableName() : string;
}