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
    if ($app['debug']) {
        return;
    }
    $msg = 'We are sorry, but something went terribly wrong.';
    return new Response($msg);
});

$app->get('/next-runs', function(Request $request) use($app) {
    $formValues = $request->query->get('form');
    
    $runDates = array();
    $cron = Cron\CronExpression::factory($formValues['expr']);
    $prevRundate = null;
    for ($i = 0; $i < $formValues['runs']; $i++) {
        $runDate = $cron->getNextRunDate('now', $i);
        
        if ($prevRundate) {
            $diff = $prevRundate->diff($runDate);
            $runDates[] = '<span class="diff">'. $app->escape($diff->format('%R%H:%I:%S')) .'</span>';
        }
        $runDates[] = $app->escape($runDate->format('Y-m-d H:i:s'));
        
        $prevRundate = $runDate;
    }
    return implode("<br />", $runDates);
});

$app->get('/', function(Request $request) use ($app) {
    $runs = array();
    foreach( range(5, 50, 5) as $step) {
        $runs[$step] = $step;
    }
    
    $defaults = array(
        'runs' => 15,
    );
    
    $form = $app['form.factory']->createBuilder('form', $defaults)
        ->add('expr')
        ->add('runs', 'choice', array(
                'choices' => $runs
        ))
        ->getForm();
    
    $form->handleRequest($request);
    
    // display the form
    return $app['twig']->render('index.twig', array('form' => $form->createView()));    
});

$app->run();