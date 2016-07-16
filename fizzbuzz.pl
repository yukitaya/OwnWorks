#!/usr/bin/perl

$num = 0;
while($num < 100){
    $num++;
    if ($num % 15 == 0){
        print "$num FizzBuzz\n"
    }elsif ($num % 3 == 0){
        print "$num Fizz\n";
    }elsif ($num % 5 == 0){
        print "$num Buzz\n";
    }else{
        print "$num\n";
    }
}

