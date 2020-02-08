<?php

interface Handler
{
    public function setNext(Handler $handler): Handler;

    public function handle(string $request): ?string;
}

abstract class AbstractHandler implements Handler
{
    /**
     * @var Handler
     */
    private $nextHandler;

    public function setNext(Handler $handler): Handler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(string $request): ?string
    {
        if ($this->nextHandler) {
            return $this->nextHandler->handle($request);
        }
        
        return null;
    }
}

class PoliceHandler extends AbstractHandler
{
    public function handle(string $request): ?string
    {
        if ($request === "Police") {
            return "You've called to police department.\n";
        } else {
            return parent::handle($request);
        }
    }
}

class UrgencyHandler extends AbstractHandler
{
    public function handle(string $request): ?string
    {
        if ($request === "Urgency") {
            return "You've called to urgency department.\n";
        } else {
            return parent::handle($request);
        }
    }
}

class FireHandler extends AbstractHandler
{
    public function handle(string $request): ?string
    {
        if ($request === "Fire") {
            return "You've called to fire department.\n";
        } else {
            return parent::handle($request);
        }
    }
}

function clientCode(Handler $handler)
{
    foreach (["Police", "Urgency", "Fire"] as $department) {
        echo "Client: I need to call to " . $department . "\n";
        $result = $handler->handle($department);
        if ($result) {
            echo "  " . $result;
        } else {
            echo "  " . $department . " was not called.\n";
        }
    }
}

$police = new PoliceHandler;
$urgency = new UrgencyHandler;
$fire = new FireHandler;

$police->setNext($urgency)->setNext($fire);

echo "Chain: Police > urgency > fire\n\n";
clientCode($police);