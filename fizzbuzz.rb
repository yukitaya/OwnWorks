#! /usr/bin/env ruby

num = 0
while num < 100 do
    num = num + 1
    if num % 15 == 0 then
        print "#{num} FizzBuzz\n"
    elsif num % 3 == 0 then
        print "#{num} fizz\n"
    elsif num % 5 == 0 then
        print "#{num} buzz\n"
    else
        print "#{num}\n"
    end
end
