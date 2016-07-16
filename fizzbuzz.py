#!/usr/bin/python

num = 0
while num < 100:
    num = num + 1
    if num % 15 == 0:
        print num,"FizzBuzz"
    elif num % 3 == 0:
        print num,"fizz"
    elif num % 5 == 0:
        print num,"buzz"
    else:
        print num
