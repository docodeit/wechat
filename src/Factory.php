<?php
namespace JinWeChat;

class Factory{
    public static function make($name,array $args){
        $namespace = '';
        return true;
    }
    public static function __callStatic($name,$args){
        return self::make($name,...$args);
    }
}