<?php
// class Fruit{
//     public $name;
//     public $color;

//     function __construct($name, $color){
//         $this->name = $name;
//         $this->color = $color;
//     }

//     function get_details(){
//         echo "Name: " . $this->name . " Color: " . $this->color . "<br>";
//     }
// }
// $apple = new Fruit();
// $apple->set_details("Apple", "Red");
// $apple->get_details();

// $banana = new Fruit("Banana", "Yellow");
// $banana->get_details();
//

// class Fruit{
//     protected $name;

//     public function setType($name){
//         $this->name = $name;

//     }
// }
// class Apple extends Fruit{
//     public function getType(){
//      echo "Name : " .$this->name. "<br>";
//     }
// }
//  $apple = new Apple();
//  $apple->setType("Apple");
//  echo $apple->getType();

// class Fruit{
//     public $name;
//     public $color;

//     public function __construct($name,$color){
//         $this->name = $name;
//         $this->color = $color;
//     }
//     public function intro(){
//         echo "The fruit is {$this->name} and the color is {$this->color}.<br>";
//     }
// }

// class Mango extends Fruit{
//     // public function meassage(){
//     //     echo "Am I a mango? <br>";
//     // }

//     public $weight;

// public function __construct($name,$color,$weight){
//     $this->name = $name;
//     $this->color = $color;
//     $this->weight = $weight;    
// }    
// public function intro(){
//     echo "A $this->name is $this->color , and the weight is $this->weight gram.<br>";
// }
// }

// $mango = new Mango("Mango", "Yellow", 500);
// $mango->intro();
// $mango->meassage();

// class Goodbye{
//     const MESSAGE = "Thnks for visiting";

//     public function bye() {
//         echo self::MESSAGE;
//     } 
// }
// $goodbye = new Goodbye();
// $goodbye->bye();   

// abstract class Car{
//     public $name;

//     public function __construct($name){
//         $this->name =$name;
//     }
//     abstract public function intro();
// }

// class Audi extends Car{
//     public function intro(){
//         echo "My name is $this->name <br>";
//     }
// }
// class Volvo extends Car{
//     public function intro(){
//         echo "My name is $this->name <br>";
//     }
// }

// $audi = new Audi("Audi");
// echo $audi->intro();
// echo "<br>";
// $volvo = new Volvo("Volvo");
// $volvo->intro();

// abstract class ParentClass{
//     abstract protected function prefixName($name);
// }
// class ChildClass extends ParentClass{
//     public function prefixName($name){
//         if($name=="john"){
//             $prefix ="mr";
//         }
//         elseif($name=="Jane"){
//             $prefix ="Mrs";
//         }
//         else{
//             $prefix ="Unknown";
//         }
//         return "$prefix $name";
//     }
    
// }
// $class = new ChildClass();
// echo $class->prefixName("john");
// echo "<br>";    
// echo $class->prefixName("Jane");    

// class ClassName implements InterfaceName{
//     public function method1(){

//     }

// }

// interface Animal{
//     public function makeSound();

// }
// class Cat implements Animal{
//     public function makeSound(){
//         echo "Meow";}

// }
// class Dog implements Animal{
//     public function makeSound(){
//         echo "Woof";
//     }
// }

// $cat = new Cat();
// $cat->makeSound();


// $dog=new Dog();
// $dog->makeSound();

// class greeting{
//     public static function welcome(){
//         echo "Hello World";

//     }
// }
// greeting::welcome(); 

// class greetings{
//     public static function welcome(){
//         echo "Hello World";
//     }
// }
// greetings::welcome();

// class calc{
//     public static function sum($x,$y){
//         return $x + $y;
//     }
// }
// $result = calc::sum(5,10);
// echo $result;

// class A {
//   public static function welcome() {
//     echo "Hello World!";
//   }
// }

// class B {
//   public function message() {
//     A::welcome();
//   }
// }

// $obj = new B();
// echo $obj -> message(); 

class A{
    protected static function welcome(){
        return "Good One";
    }
}
class B extends A{
    public $website;
    public function __construct(){
        $this->website = parent::welcome();
    }
}
$B = new B;
echo $B->website;
?>
 