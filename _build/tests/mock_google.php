<?php
// Simula el endpoint /token de Google: el "code" trae los claims codificados.
$code = $_POST['code'] ?? '';
$claims = json_decode(base64_decode(strtr($code, '-_', '+/')), true) ?: [];
$b = fn($a) => rtrim(strtr(base64_encode(json_encode($a)), '+/', '-_'), '=');
header('Content-Type: application/json');
echo json_encode(['id_token' => $b(['alg' => 'RS256']) . '.' . $b($claims) . '.sig', 'access_token' => 'x']);
