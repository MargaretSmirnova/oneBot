<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.07.2017
 * Time: 20:40
 */
$out = file_get_contents('php://input');
file_put_contents('log.txt',$out);