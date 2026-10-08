<?php
// Crea las tablas y los productos de ejemplo. Se activa con la variable SETUP_TOKEN: /install?token=...
// Después de usarlo, borrá SETUP_TOKEN del panel de Vercel para desactivarlo.
header('Content-Type: text/plain; charset=utf-8');
$token = env('SETUP_TOKEN');
if (!$token || !hash_equals($token, (string)($_GET['token'] ?? ''))) {
    http_response_code(404);
    exit("404\n");
}
$driver = db_driver();
$file = __DIR__ . '/../../database/' . ($driver === 'pgsql' ? 'postgres' : ($driver === 'mysql' ? 'mysql' : 'sqlite')) . '.sql';
$n = run_sql_file($file);
echo "Motor: $driver\nSentencias ejecutadas: $n\n";
echo "Listo. Ahora borrá la variable SETUP_TOKEN.\n";
