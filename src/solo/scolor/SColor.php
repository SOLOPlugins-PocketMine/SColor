<?php

namespace solo\scolor;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\server\ServerCommandEvent;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

use solo\scolor\color\Color;
use solo\scolor\style\Style;

class SColor extends PluginBase{

  private static $instance = null;

  public static function getInstance(){
    return self::$instance;
  }

  /** @var Config */
  private $setting;

  /** @var Color[] */
  private $knownColors = [];

  /** @var Style[] */
  private $knownStyles = [];

  /** @var Color|Style[] */
  private $list = [];

  /** @var Color|Style[] */
  private $allowList = [];

  public function onLoad(){
    if(self::$instance !== null){
      throw new \InvalidStateException();
    }
    self::$instance = $this;
  }

  public function onEnable(){
    @mkdir($this->getDataFolder());
    $this->saveResource("setting.yml");

    $this->setting = new Config($this->getDataFolder() . "setting.yml", Config::YAML);

    foreach($this->setting->get("allow-colors", []) as $code){
      $this->allowList[$code] = $code;
    }

    foreach([
      "Black", "DarkBlue", "DarkGreen", "DarkAqua", "DarkRed", "DarkPurple",
      "Gold", "Gray", "DarkGray", "Blue", "Green", "Aqua", "Red", "LightPurple",
      "Yellow", "White"
    ] as $color){
      $class = "\\solo\\scolor\\color\\" . $color;
      $colorInstance = new $class();
      $this->registerColor($colorInstance);
    }

    foreach([
      "Bold", "Italic", "Obfuscated", "Reset"
    ] as $style){
      $class = "\\solo\\scolor\\style\\" . $style;
      $styleInstance = new $class();
      $this->registerStyle($styleInstance);
    }

    $this->getServer()->getCommandMap()->register("scolor", new \solo\scolor\command\ColorCommand($this));

    $this->getServer()->getPluginManager()->registerEvents(new class($this) implements Listener{
      public function __construct(SColor $owner){
        $this->owner = $owner;
      }

      /**
       * @priority LOW
       *
       * @ignoreCancelled true
       */
      public function handlePlayerCommandPreprocess(PlayerCommandPreprocessEvent $event){
        if($event->getPlayer()->isOp() or $this->owner->getSetting()->get("allow-color-on-chat", true) === true){
          $event->setMessage($this->colorize($event->getMessage(), $event->getPlayer()));
        }else{
          $event->setMessage(TextFormat::clean($event->getMessage()));
        }
      }

      /**
       * @priority LOW
       *
       * @ignoreCancelled true
       */
      public function handleSignChange(SignChangeEvent $event){
        if($event->getPlayer()->isOp() or $this->owner->getSetting()->get("allow-color-on-sign", true) === true){
          for($i = 0; $i < 4; $i++){
            $event->setLine($i, $this->owner->colorize($event->getLine($i), $event->getPlayer()));
          }
        }else{
          for($i = 0; $i < 4; $i++){
            $event->setLine($i, TextFormat::clean($event->getLine($i)));
          }
        }
      }

      /**
       * @priority LOW
       *
       * @ignoreCancelled true
       */
      public function handleServerCommand(ServerCommandEvent $event){
        $event->setCommand($this->owner->colorize($event->getCommand()));
      }
    }, $this);
  }

  public function onDisable(){
    self::$instance = null;
  }

  public function getSetting() : Config{
    return $this->setting;
  }

  public function registerColor(Color $color) : bool{
    if(!isset($this->allowList[$color->getCode()])){
      return false;
    }
    $this->knownColors[$color->getCode()] = $color;
    $this->list[$color->getCode()] = $color;
    return true;
  }

  public function registerStyle(Style $style) : bool{
    if(!isset($this->allowList[$style->getCode()])){
      return false;
    }
    $this->knownStyles[$style->getCode()] = $style;
    $this->list[$style->getCode()] = $style;
    return true;
  }

  public function getRegisteredColors() : array{
    return $this->knownColors;
  }

  public function getRegisteredStyles() : array{
    return $this->knownStyles;
  }

  public function colorize(string $raw, CommandSender $sender = null) : string{
    if(strpos($raw, '§') === false and strpos($raw, '&') === false){
      return $raw;
    }
    $len = strlen($raw);
    $offset = 0;
    $ret = '';
    while($offset < $len){
      $token = $raw{$offset};
      if($token == '&' or $token == '§'){
        $offset++;
        if($offset < $len){
          $code = $raw{$offset};
          $color = $this->list[$code] ?? null;
          if($color !== null){
            if($sender !== null and !$sender->hasPermission($color->getPermission())){
              // Can't use the color
              if($token == '&'){
                $ret .= '&';
              }
              $offset++;
              continue;
            }
            // has permission to use the color
            $ret .= '§' . $code;
            $offset++;
            continue;
          }
          // color not exists
          if($token == '&'){
            $ret .= '&';
          }
          $ret .= $code;
          $offset++;
          continue;
        }
        // offset out of range
        $offset++;
        continue;
      }
      $ret .= $token; // just character
      $offset++;
    }
    return $ret;
  }
}
