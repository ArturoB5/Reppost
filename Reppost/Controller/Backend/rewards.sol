// SPDX-License-Identifier: MIT
pragma solidity ^0.8.0;

contract RewardSystem {

    // Mapeo de direcciones de usuario a su saldo de tokens
    mapping(address => uint256) public balances;

    // Evento para emitir cuando un usuario recibe tokens
    event TokensAwarded(address indexed user, uint256 amount);

    // Función para que el usuario reciba tokens cuando publique algo
    function publish() external {
        // Supongamos que por publicar, el usuario recibe 10 tokens
        uint256 reward = 10;

        // Aumentar el saldo del usuario que hizo la publicación
        balances[msg.sender] += reward;

        // Emitir un evento para informar que se otorgaron tokens
        emit TokensAwarded(msg.sender, reward);
    }

    // Función para que el usuario reciba tokens cuando reaccione a una publicación
    function react() external {
        // Supongamos que por reaccionar, el usuario recibe 5 tokens
        uint256 reward = 5;

        // Aumentar el saldo del usuario que reaccionó
        balances[msg.sender] += reward;

        // Emitir un evento para informar que se otorgaron tokens
        emit TokensAwarded(msg.sender, reward);
    }

    // Función para obtener el balance de tokens de un usuario
    function getBalance() external view returns (uint256) {
        return balances[msg.sender];
    }
}
