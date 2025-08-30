# Write a program that checks if a given year is a leap year or not. 200Implement the logic for leap years (divisible by 4 but not by 100 unles also divisible by 400).
# year = int(input("Enter a year: "))
# if (year % 4 == 0 and year % 100 != 0) or (year % 400 == 0):
#     print(f'The year {year} is a leap year.')
# else:
#     print(f'The year {year} is not a leap year.')

# Implement a program that prints the multiplication table for a given 5number. Allow the user to input the number.
# number = int(input("Enter a number: "))
# for i in range(1, 11):
#     print(f'{number} x {i} = {number * i}')

# Write a Python program that calculates the factorial of a given number using a loop. Take the number as input.
# number = int(input("Enter a number: "))
# factorial = 1
# for i in range(1, number + 1):
#     factorial *= i
# print(f'The factorial of {number} is {factorial}.')

# Develop a program that prints the Fibonacci sequence up to a certain number of terms. Allow the user to input the number of terms
# num_terms = int(input("Enter the number of terms: "))
# a, b = 0, 1
# for _ in range(num_terms):
#     print(a, end=' ')
#     a, b = b, a + b

# Write a program to calculate the sum of all prime numbers within a given range (input by the user).
# start = int(input("Enter the start of the range: "))
# end = int(input("Enter the end of the range: "))
# prime_sum = 0
# for num in range(start, end + 1):
#     if num > 1:
#         for i in range(2, int(num**0.5) + 1):
#             if num % i == 0:
#                 break
#         else:
#             prime_sum += num
# print(f'The sum of all prime numbers between {start} and {end} is {prime_sum}.')

# Develop a program that takes numbers as input and calculates thesum until it encounters a negative number, using the break statement.
# sum_numbers = 0
# while True:
#     number = float(input("Enter a number (negative to stop): "))
#     if number < 0:
#         break
#     sum_numbers += number
# print(f'The sum of the numbers is {sum_numbers}.')
# Write a Python program to calculate the factorial of a given number using a user-defined function.

# def factorial(n):
#     if n == 0:
#         return 1
#     else:
#         return n * factorial(n-1)

# number = int(input("Enter a number: "))
# print(f'The factorial of {number} is {factorial(number)}.')
# Develop a Python program to determine whether a given string is a palindrome (reads the same backward and forward) using loops and conditionals.

# def is_palindrome(s):
#     s = s.lower()
#     left = 0
#     right = len(s) - 1
#     while left < right:
#         if s[left] != s[right]:
#             return False
#         left += 1
#         right -= 1
#     return True

# input_string = input("Enter a string: ")
# if is_palindrome(input_string):
#     print(f'The string "{input_string}" is a palindrome.')
# else:
#     print(f'The string "{input_string}" is not a palindrome.')

# Write a Python program that calculates the volume of various 3D shapes (cube, sphere, cylinder) using user-defined functions for each shape.
# def volume_sphere(radius):
#     from math import pi
#     return (4/3) * pi * (radius ** 3)
# def volume_cube(side):
#     return side ** 3
# def volume_cylinder(radius, height):
#     from math import pi
#     return pi * (radius ** 2) * height
# def main():
#     print("Choose a shape to calculate the volume:")
#     print("1. Sphere")
#     print("2. Cube")
#     print("3. Cylinder")
#     choice = input("Enter the number of your choice: ")

#     if choice == '1':
#         radius = float(input("Enter the radius of the sphere: "))
#         print(f'The volume of the sphere is {volume_sphere(radius)}.')
#     elif choice == '2':
#         side = float(input("Enter the side length of the cube: "))
#         print(f'The volume of the cube is {volume_cube(side)}.')
#     elif choice == '3':
#         radius = float(input("Enter the radius of the cylinder: "))
#         height = float(input("Enter the height of the cylinder: "))
#         print(f'The volume of the cylinder is {volume_cylinder(radius, height)}.')
#     else:
#         print("Invalid choice.")

# Create a Python module that contains functions for basic geometric calculations (area of circle, rectangle, triangle). Import this module into another script and use its functions.
# # geometric_calculations.py
# def area_circle(radius):
#     from math import pi
#     return pi * (radius ** 2)
# def area_rectangle(length, width):
#     return length * width
# def area_triangle(base, height):
#     return 0.5 * base * height
# # main.py
# from geometric_calculations import area_circle, area_rectangle, area_triangle
# def main():
#     print("Choose a geometric calculation:")
#     print("1. Area of Circle")
#     print("2. Area of Rectangle")
#     print("3. Area of Triangle")
#     choice = input("Enter the number of your choice: ")
#     if choice == '1':
#         radius = float(input("Enter the radius of the circle: "))
#         print(f'The area of the circle is {area_circle(radius)}.')
#     elif choice == '2':
#         length = float(input("Enter the length of the rectangle: "))
#         width = float(input("Enter the width of the rectangle: "))
#         print(f'The area of the rectangle is {area_rectangle(length, width)}.')
#     elif choice == '3':
#         base = float(input("Enter the base of the triangle: "))
#         height = float(input("Enter the height of the triangle: "))
#         print(f'The area of the triangle is {area_triangle(base, height)}.')
#     else:
#         print("Invalid choice.")
# if __name__ == "__main__":
#     main()    
