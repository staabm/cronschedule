<?php
require_once __DIR__.'/vendor/autoload.php';

use Silex\Provider\FormServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\TranslationServiceProvider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app['debug'] = true;
$app->register(new FormServiceProvider());
$app->register(new TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/views',
));
$app->register(new TranslationServiceProvider(), array(
        'translator.messages' => array(),
));

$app->error(function (\Exception $e, $code) use($app) {
    $msg = 'We are sorry, but something went terribly wrong.';
    if ($app['debug']) {
        $msg .= $e->getMessage();
    }
    return new Response($msg);
});

$app->get('/next-runs', function(Request $request) use($app) {
    $formValues = $request->query->get('form');
    
    $runDates = array();
    $cron = Cron\CronExpression::factory($formValues['expr']);
    for ($i = 0; $i < 10; $i++) {
        $runDates[] = $app->escape($cron->getNextRunDate('now', $i)->format('Y-m-d H:i:s'));
    }
    return implode("<br />", $runDates);
});

$app->get('/', function(Request $request) use ($app) {
    $form = $app['form.factory']->createBuilder('form')
        ->add('expr')
        ->getForm();
    
    $form->handleRequest($request);
    
    // display the form
    return $app['twig']->render('index.twig', array('form' => $form->createView()));    
});

$app->run();