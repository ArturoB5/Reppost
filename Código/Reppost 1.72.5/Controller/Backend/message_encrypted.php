<?php
// Función para cifrar un mensaje
function encryptMessage($message, $key)
{
    $method = 'AES-256-CBC';
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
    $encryptedMessage = openssl_encrypt($message, $method, $key, 0, $iv);

    // Retornar mensaje cifrado junto con el IV
    return base64_encode($iv . '::' . $encryptedMessage);
}

// Función para descifrar un mensaje
function decryptMessage($encryptedMessage, $key)
{
    $method = 'AES-256-CBC';
    $data = base64_decode($encryptedMessage);

    // Separar el IV y el mensaje cifrado
    list($iv, $encryptedData) = explode('::', $data, 2);

    // Desencriptar el mensaje
    return openssl_decrypt($encryptedData, $method, $key, 0, $iv);
}
