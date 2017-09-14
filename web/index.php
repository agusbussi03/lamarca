<?php
use Silex\Provider\AssetServiceProvider;
require_once __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'path'     => __DIR__.'/app.db',
        'dbname'   => 'lamarca'
    ),
));
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->register(new AssetServiceProvider());

$app['debug'] = true;
$app->get('/', function() use($app) {
   $provincias=array("Buenos Aires","Catamarca","Chaco","Chubut","Córdoba","Corrientes","Entre Ríos",
                     "Formosa","Jujuy","La Pampa","La Rioja","Mendoza","Misiones","Neuquén","Río Negro",
                     "Salta","San Juan","San Luis","Santa Cruz","Santa Fe","Santiago del Estero",
                     "Tierra del Fuego, Antártida e Isla del Atlántico Sur","Tucumán");
    $sql = "SELECT * FROM marcas";
    $marcas = $app['db']->fetchAll($sql, array());
    return $app['twig']->render('index.twig', array(
        'provincias'=>$provincias,'marcas'=>$marcas
    ));
});

$app->get('/modelos/{marca}', function($marca) use($app) {
    $sql = "SELECT * FROM modelos where marca=?";
    $modelos = $app['db']->fetchAll($sql, array((int)$marca));
    return $app->json($modelos);
});

$app->get('/versiones/{marca}/{modelo}', function($marca,$modelo) use($app) {
    $sql = "SELECT * FROM versiones where marca=? and modelo=?";
    $versiones = $app['db']->fetchAll($sql, array((int)$marca,(int)$modelo));
    return $app->json($versiones);
});

$app->post('/submit', function() use($app) {
    $sql = "SELECT * FROM versiones where marca=? and modelo=?";
    $versiones = $app['db']->fetchAll($sql, array((int)$marca,(int)$modelo));
    return $app->json($versiones);
});


$app->run();