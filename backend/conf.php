<?php
session_start();
$GLOBALS['mysql_con_properties'] = array(
    "hostname" => "localhost",
    "username" => "root",
    "password" => "root",
    "database" => "hashtag_sorter"
);
$GLOBALS['registered'] = false;
$GLOBALS['title'] = 'Hashtag sorter';
$private_key_pem = "-----BEGIN RSA PRIVATE KEY-----
MIIBOgIBAAJBAOqJJl7GCpDtRYoX5H3Xkv2yex7VlkP88U5+fLin7vLUVP91Puip
oRLqXovn7WkdzwFNcLXxpxiAqDl9vgq3Jr8CAwEAAQJAWVaM6yT8+V+oUYXwX48y
Srkl9QTfDF7ZfFDqKVyuxIgqbcanVVKccmhoiZUg0fDlaE27ZwR0h2f+hhFy7hmT
YQIhAPrys6Rhbu8IL2qkIHyDNDleZLg08gXHlmTeTNt5dmyrAiEA70HdZzawXGy3
UBiInb3Rt9V+kP6utESvoIfHai5Zxj0CIQDbVMUuJvqOcNkAm/LI4OQEQDxHw/7E
jm0kPCdwq/iVgQIgbNEpEJt4BOFRUnWGFTBTJOhOA0ZCUSz4L+vxT6K7JAUCIAEd
7hSV6EadaFYZ6glJoRUdBuf3xgbYpQC2k5cO2GnV
-----END RSA PRIVATE KEY-----";

$GLOBALS['private_key'] = openssl_pkey_get_private($private_key_pem);
$public_key_pem = openssl_pkey_get_details($GLOBALS['private_key'])['key'];
$GLOBALS['public_key']= openssl_pkey_get_public($public_key_pem);

// PEM PRIVATE AND PUBLIC KEYS

$GLOBALS['private_key_pem'] = $private_key_pem;
$GLOBALS['public_key_pem'] = $public_key_pem;
