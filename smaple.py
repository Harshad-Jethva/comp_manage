# Write a simple Python script to print your name to the console
# name = 'Harshad'
# print(f'My name is {name}')

# Write a program that takes user input (name, age, etc.) and prints a personalized message.
# user_name = input("Enter your name: ")
# user_age = input("Enter your age: ")
# print(f'Hello {user_name}!')
# print(f'Your age is {user_age}!')

# Create a Python script that takes two numbers as user input and prints the result of their multiplication. Include appropriate user prompts and output statements.
# num1 = float(input("Enter the first number: "))
# num2 = float(input("Enter the second number: "))
# result = num1 * num2
# print(f'The result of multiplying {num1} and {num2} is {result}.')                

# Develop a Python script that calculates and prints the result of raising a user-input base to a user-input exponent without using the ** operator.
# base = float(input("Enter the base: "))
# exponent = int(input("Enter the exponent: "))
# result = 1
# for _ in range(exponent):
#     # result *= base
#     result = result * base
# print(f'{base} raised to the power of {exponent} is {result}.')

# Write a program that takes coefficients of a quadratic equation as input and solves for the roots. Handle both real and complex roots.
# import cmath
# a = float(input("Enter coefficient a: "))
# b = float(input("Enter coefficient b: "))
# c = float(input("Enter coefficient c: "))
# discriminant = b**2 - 4*a*c
# if discriminant > 0:
#     root1 = (-b + cmath.sqrt(discriminant)) / (2 * a)
#     root2 = (-b - cmath.sqrt(discriminant)) / (2 * a)
#     print(f'The roots are real and different: {root1} and {root2}')
# elif discriminant == 0:
#     root = -b / (2 * a)
#     print(f'The root is real and repeated: {root}')

# else:
#     root1 = (-b + cmath.sqrt(discriminant)) / (2 * a)
#     root2 = (-b - cmath.sqrt(discriminant)) / (2 * a)
#     print(f'The roots are complex: {root1} and {root2}')     

# Write a program that takes two input strings and concatenates them.
# string1 = input("Enter the first string: ")
# string2 = input("Enter the second string: ")
# result = string1 + string2
# print(f'The concatenated string is: {result}')          

# Write a Python program that takes a number as input and determines whether it is positive, negative, or zero. Use if, else, and elif statements for decision-making.
# number = float(input("Enter a number: "))
# if number > 0:
#     print(f'The number {number} is positive.')
# elif number < 0:
#     print(f'The number {number} is negative.')
# else:
#     print('The number is zero.')

# Write a Python program that takes a user's age as input and prints whether they are eligible to vote or not (considering the legal voting age is 18).
# age = int(input("Enter your age: "))
# if age >= 18:
#     print("You are eligible to vote.")
# else:
#     print("You are not eligible to vote.")

# Implement a program that classifies a given angle (input in degrees) into categories such as acute, right, obtuse, or invalid.
# angle = float(input("Enter an angle in degrees: "))
# if 0 < angle < 90:
#     print(f'The angle {angle} is acute.')
# elif angle == 90:
#     print(f'The angle {angle} is right.')
# elif 90 < angle < 180:
#     print(f'The angle {angle} is obtuse.')
# else:
#     print('Invalid angle.')

# Write a program that takes coefficients of a quadratic equation as input and solves for the roots. Handle both real and complex roots.
import cmath
a = float(input("Enter coefficient a: "))
b = float(input("Enter coefficient b: "))
c = float(input("Enter coefficient c: "))   



