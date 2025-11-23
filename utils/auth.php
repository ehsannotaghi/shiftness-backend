<?php
// Authentication helpers (simple JWT implementation)

// Secret used to sign tokens. For production, set JWT_SECRET env var and keep it secret.
$JWT_SECRET = getenv('JWT_SECRET') ? getenv('JWT_SECRET') : 'please_change_this_secret';

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    $pad = 4 - (strlen($data) % 4);
    if ($pad < 4) {
        $data .= str_repeat('=', $pad);
    }
    return base64_decode(strtr($data, '-_', '+/'));
}

function generateToken($userId, $email, $ttl = 3600) {
    global $JWT_SECRET;
    $header = ['alg' => 'HS256', 'typ' => 'JWT'];
    $now = time();
    $payload = [
        'user_id' => $userId,
        'email' => $email,
        'iat' => $now,
        'exp' => $now + $ttl
    ];

    $segments = [];
    $segments[] = base64url_encode(json_encode($header));
    $segments[] = base64url_encode(json_encode($payload));
    $signing_input = implode('.', $segments);
    $signature = hash_hmac('sha256', $signing_input, $JWT_SECRET, true);
    $segments[] = base64url_encode($signature);

    return implode('.', $segments);
}

function verifyToken($token) {
    global $JWT_SECRET;
    $parts = explode('.', $token);
    if (count($parts) !== 3) return false;

    list($b64header, $b64payload, $b64sig) = $parts;
    $header = json_decode(base64url_decode($b64header), true);
    $payload = json_decode(base64url_decode($b64payload), true);
    $sig = base64url_decode($b64sig);

    if (!$header || !$payload || !$sig) return false;

    $expected = hash_hmac('sha256', $b64header . '.' . $b64payload, $JWT_SECRET, true);
    if (!hash_equals($expected, $sig)) return false;

    if (isset($payload['exp']) && time() > $payload['exp']) return false;

    return $payload;
}

?>