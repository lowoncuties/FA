import requests
import itertools
import time
from concurrent.futures import ThreadPoolExecutor

def check_password(password, complexity):
    payload = {'password': password, 'complexity': complexity}
    response = requests.post('http://localhost:3000/check_password.php', data=payload)
    return response.text

def crack_password_worker(password, complexity):
    if check_password(password, complexity) == 'OK':
        return password

def check_password(password, complexity):
    payload = {'password': password, 'complexity': complexity}
    response = requests.post('http://localhost:3000/check_password.php', data=payload)
    return response.text

def crack_password(complexity):
    start_time = time.time() 
    characters = {
        '0': '0123456789',
        '1': '0123456789',
        '2': 'abcdefghijklmnopqrstuvwxyz',
        '3': 'abcdefghijklmnopqrstuvwxyz0123456789',
        '4': 'abcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+-=[]{}|;:,.<>?'
    }

    character_set = characters[complexity]
    max_length = 4
    found = False
    for length in range(1, max_length + 1):
        for attempt in itertools.product(character_set, repeat=length):
            password = ''.join(attempt)
            if check_password(password, complexity) == 'OK':
                end_time = time.time()
                time_taken = end_time - start_time
                print(f"Password cracked: {password}")
                print(f"Time taken: {time_taken} seconds")
                found = True
                break
        if found:
            break

crack_password('4')
